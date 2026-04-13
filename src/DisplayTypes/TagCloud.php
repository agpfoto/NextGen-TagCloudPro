<?php

/**
 * NextGEN Pro TagCloud display type.
 *
 * Extends the built-in Search display type with "required-container" support:
 * the gallery pre-populates with images that match a set of required tag names
 * without any visitor interaction.  Visitors then narrow results by clicking
 * related-tag filter buttons.
 *
 * On pages whose URL contains "tagsearch" a full text-search form is also shown.
 *
 * EXTRA SETTINGS  (added on top of all Search settings):
 *
 *   required_container_ids        Comma-separated tag names.  An image must
 *                                 carry ALL listed tags to appear by default.
 *                                 Multi-word names are supported, e.g.
 *                                 "blue ocean,coastal scenery".
 *
 *   related_tag_maxcount          Max related-tag buttons on desktop.
 *                                 Top N by usage count, displayed A-Z.
 *
 *   related_tag_maxcount_mobile   Same limit when wp_is_mobile() is true.
 *
 * @package NextGEN Gallery Pro
 */

namespace Imagely\NGGPro\DisplayTypes;

use Imagely\NGGPro\Bootloader;
use Imagely\NGGPro\Display\StaticAssets;
use Imagely\NGGPro\Display\View;
use Imagely\NGG\DataTypes\DisplayedGallery;
use Imagely\NGG\DisplayedGallery\Renderer as GalleryRenderer;
use Imagely\NGG\Util\Transient;
use Imagely\NGG\Settings\Settings;
use Imagely\NGG\Util\Router;

class TagCloud extends Search {

	// =========================================================================
	// Static state  (same pattern as parent Search)
	// =========================================================================

	/** @var array<string,bool> */
	public static $galleries_displayed = [];

	/** @var array<string,string> */
	public static $displayed_galleries_rendering = [];

	/** @var array<string,DisplayedGallery> */
	public static $alternate_displayed_galleries = [];

	// =========================================================================
	// Install
	// =========================================================================

	public function install( $reset = false ) {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		\dbDelta( "CREATE TABLE {$wpdb->prefix}ngg_pictures (
			description mediumtext NULL,
			alttext     mediumtext NULL,
			FULLTEXT KEY `alttext_search`    (`alttext`),
			FULLTEXT KEY `description_search`(`description`),
			FULLTEXT KEY `combined_search`   (`alttext`,`description`)
		);" );

		$this->install_display_type(
			NGG_PRO_TAGCLOUD,
			[
				'title'          => __( 'NextGEN Pro TagCloud', 'nextgen-gallery-pro' ),
				'entity_types'   => [ 'gallery', 'album', 'image' ],
				'default_source' => 'tags',
				'hidden_from_ui' => false,
				'view_order'     => NGG_DISPLAY_PRIORITY_BASE + ( NGG_DISPLAY_PRIORITY_STEP * 10 ) + 65,
				'settings'       => $this->get_default_settings(),
				'aliases'        => [],
			],
			$reset
		);
	}

	// =========================================================================
	// Default settings
	// =========================================================================

	public function get_default_settings() : array {
		return \apply_filters( 'ngg_pro_tagcloud_default_settings', array_merge(
			parent::get_default_settings(),
			[
				'required_container_ids'      => '',
				'related_tag_maxcount'        => '20',
				'related_tag_maxcount_mobile' => '10',
				// Override the Search default (photocrati-nextgen_basic_thumbnails) with Mosaic.
				'gallery_display_type'        => NGG_PRO_MOSAIC,
			]
		) );
	}

	// =========================================================================
	// Validation  (parent disallows 'rand'; we re-allow it)
	// =========================================================================

	public function validate_displayed_gallery_settings( array $settings ) : array {
		$settings = parent::validate_displayed_gallery_settings( $settings );
		if ( ! in_array( $settings['order_by'],
			[ 'pid', 'galleryid', 'filename', 'imagedate', 'rand' ], true ) ) {
			$settings['order_by'] = 'pid';
		}
		return $settings;
	}

	// =========================================================================
	// Preview image
	// =========================================================================

	public function get_preview_image_url() : string {
		return StaticAssets::get_url( 'DisplayTypes/TagCloud/preview.png' );
	}

	// =========================================================================
	// resolve_nggsearch_term
	//
	// The parent declares this private; we redeclare it here as protected with
	// identical logic so our overridden methods can call it.
	// =========================================================================

	protected function resolve_nggsearch_term() : string {
		$router = Router::get_instance();
		$raw    = $router->get_parameter( 'nggsearch' );

		if ( ! is_string( $raw ) || '' === $raw ) {
			return '';
		}

		$term = \wp_unslash( $raw );
		$prev = '';
		$i    = 0;
		while ( $term !== $prev && $i < 3 ) {
			$prev = $term;
			$term = rawurldecode( $term );
			++$i;
		}

		return (string) \apply_filters( 'ngg_pro_frontend_search_term', trim( $term ), $raw );
	}

	// =========================================================================
	// Cache action – always re-render so required images show without search
	// =========================================================================

	public function cache_action( $displayed_gallery ) : string {
		return $this->index_action( $displayed_gallery, true );
	}

	// =========================================================================
	// Index action
	// =========================================================================

	public function index_action( $displayed_gallery, $output = false ) : string {
		$gid = $displayed_gallery->id();

		if ( isset( self::$galleries_displayed[ $gid ] ) ) {
			return '';
		}
		self::$galleries_displayed[ $gid ] = true;

		$search_term = $this->resolve_nggsearch_term();

		if ( $search_term && Router::get_instance()->get_parameter( 'nggsearch-do-redirect' ) ) {
			\wp_safe_redirect(
				$this->set_parameter_value( 'nggsearch', $search_term, null, false, \get_page_link() )
			);
			exit;
		}

		if ( empty( self::$displayed_galleries_rendering[ $gid ] ) ) {
			$this->get_alternate_displayed_gallery( $displayed_gallery );
		}

		return self::$displayed_galleries_rendering[ $gid ];
	}

	// =========================================================================
	// get_alternate_displayed_gallery  (core rendering logic)
	// =========================================================================

	public function get_alternate_displayed_gallery( DisplayedGallery $displayed_gallery ) : DisplayedGallery {
		$gid = $displayed_gallery->id();

		if ( ! empty( self::$alternate_displayed_galleries[ $gid ] ) ) {
			return self::$alternate_displayed_galleries[ $gid ];
		}

		$router      = Router::get_instance();
		$search_term = $this->resolve_nggsearch_term();

		$displayed_gallery->display_settings =
			$this->validate_displayed_gallery_settings( $displayed_gallery->display_settings );
		$ds = $displayed_gallery->display_settings;

		$gallery_ids = [];
		if ( 'galleries' === $displayed_gallery->source ) {
			$gallery_ids = $displayed_gallery->container_ids;
		} elseif ( 'albums' === $displayed_gallery->source ) {
			$gallery_ids = $this->get_album_children( $displayed_gallery );
		}

		$tag_limit = (int) ( \wp_is_mobile()
			? ( $ds['related_tag_maxcount_mobile'] ?? 10 )
			: ( $ds['related_tag_maxcount'] ?? 20 ) );

		$params = $ds;
		$params['i18n']               = $this->get_i18n();
		$params['search_term']        = $search_term;
		$params['form_submit_url']    = \get_page_link();
		$params['form_redirect_url']  = $router->get_routed_app()->set_parameter(
			'nggsearch', 'ngg-search-placeholder', null, false, \get_page_link()
		);
		$params['related_term_links'] = null;
		$params['gallery_display']    = '';

		$search_results        = [];
		$new_displayed_gallery = null;

		// ── Phase 1: required_container_ids (no user search) ──────────────────
		$required_ids = trim( $ds['required_container_ids'] ?? '' );

		if ( $required_ids !== '' && $search_term === '' ) {
			$tagfilter = $this->get_tagfilter_slugs( $ds );

			$search_results = $this->search_required_images(
				$required_ids,
				$gallery_ids,
				$this->get_term_ids( $tagfilter, false ),
				$ds
			);

			if ( ! empty( $ds['enable_tag_filter'] ) && ! empty( $search_results ) ) {
				$params['related_term_links'] = $this->get_required_related_terms_links(
					$required_ids, $search_results, $tagfilter, $tag_limit
				);
			}
		}

		// ── Phase 2: user search term (overrides phase 1) ─────────────────────
		if ( $search_term !== '' ) {
			$tagfilter = $this->get_tagfilter_slugs( $ds );

			$search_results = $this->search_images(
				$search_term,
				$gallery_ids,
				$this->get_term_ids( $tagfilter, false ),
				$ds
			);

			$params['related_term_links'] = null;
			if ( ! empty( $ds['enable_tag_filter'] ) && ! empty( $search_results ) ) {
				$params['related_term_links'] = $this->get_related_terms_links(
					$search_term, $search_results, $tagfilter, $tag_limit
				);
			}
		}

		// ── Render child gallery ───────────────────────────────────────────────
		if ( empty( $search_results ) ) {
			if ( $search_term !== '' ) {
				$no_images = new View(
					'GalleryDisplay/NoImagesFound', [],
					'photocrati-nextgen_gallery_display#no_images_found'
				);
				$params['gallery_display'] = $no_images->render( true );
			}
		} else {
			$renderer      = GalleryRenderer::get_instance();
			$child_gallery = $renderer->params_to_displayed_gallery( [
				'source'               => 'images',
				'image_ids'            => $search_results,
				'order_by'             => 'RAND()',
				'display_type'         => $this->resolve_child_display_type( $ds ),
				'is_ecommerce_enabled' => $ds['is_ecommerce_enabled'] ?? false,
			] );

			if ( $child_gallery && $child_gallery->is_valid() ) {
				if ( is_null( $child_gallery->id() ) ) {
					$child_gallery->id( md5( \wp_json_encode( $child_gallery->get_entity() ) ) );
				}
				self::$alternate_displayed_galleries[ $gid ] = $child_gallery;
				$new_displayed_gallery                        = $child_gallery;
				$params['gallery_display']                    = $renderer->render( $child_gallery, true );
			}
		}

		// ── Choose template ────────────────────────────────────────────────────
		// Pages whose URL contains "tagsearch" get the full text-search form.
		$params   = $this->prepare_display_parameters( $displayed_gallery, $params );
		$template = ( strpos( \get_page_link(), 'tagsearch' ) !== false )
		            ? 'searchdefault' : 'default';

		$view = new View(
			'DisplayTypes/TagCloud/' . $template,
			$params,
			NGG_PRO_TAGCLOUD . '#' . $template
		);

		self::$displayed_galleries_rendering[ $gid ] = $view->render( true );

		if ( ! empty( $new_displayed_gallery ) ) {
			return $new_displayed_gallery;
		}

		return $displayed_gallery;
	}

	// =========================================================================
	// Asset enqueueing
	// =========================================================================

	public function enqueue_frontend_resources( $displayed_gallery ) {
		\wp_enqueue_style(
			'nextgen_pro_tagcloud_style',
			StaticAssets::get_url( 'DisplayTypes/TagCloud/style.css', NGG_PRO_TAGCLOUD . '#style.css' ),
			[ 'dashicons' ],
			Bootloader::$script_version
		);

		\wp_enqueue_script(
			'nextgen_pro_tagcloud_script',
			StaticAssets::get_url( 'DisplayTypes/TagCloud/search.js', NGG_PRO_TAGCLOUD . '#search.js' ),
			[],
			Bootloader::$script_version,
			true
		);
	}

	// =========================================================================
	// search_required_images
	// =========================================================================

	/**
	 * Returns PIDs of images that carry ALL of the required tag names.
	 *
	 * Uses exact slug matching + AND semantics (every tag must be present).
	 * FULLTEXT relevance on alttext / description is applied when enabled.
	 *
	 * @param string $required_terms  Comma-separated tag names.
	 * @param int[]  $gallery_ids     Restrict to these galleries ([] = all).
	 * @param int[]  $term_ids        Visitor's active tagfilter term IDs.
	 * @param array  $ds              Display settings.
	 * @return int[]
	 */
	public function search_required_images(
		string $required_terms,
		array  $gallery_ids = [],
		array  $term_ids    = [],
		array  $ds          = []
	) : array {

		$tm        = Transient::get_instance();
		$cache_key = $tm->generate_key( NGG_PRO_TAGCLOUD, [ $required_terms, $gallery_ids, $term_ids, $ds ] );
		$hit       = $tm->get( $cache_key, null );
		if ( ! is_null( $hit ) ) {
			return $hit;
		}

		global $wpdb;

		$where = 'WHERE `nggpictures`.`pid` IS NOT NULL';
		if ( ! empty( $gallery_ids ) ) {
			$ph    = rtrim( str_repeat( '%d,', count( $gallery_ids ) ), ',' );
			$where .= $wpdb->prepare( " AND `nggpictures`.`galleryid` IN ({$ph})", $gallery_ids ); // phpcs:ignore
		}

		$select_extra = '';
		$having       = '';
		$ft_fields    = [];
		if ( ! empty( $ds['search_alttext'] ) )    { $ft_fields[] = 'alttext'; }
		if ( ! empty( $ds['search_description'] ) ) { $ft_fields[] = 'description'; }

		if ( ! empty( $ft_fields ) ) {
			$mode         = ( 'boolean' === ( $ds['search_mode'] ?? '' ) ) ? 'IN BOOLEAN MODE' : '';
			$flds         = implode( ',', $ft_fields );
			$select_extra = $wpdb->prepare( ", MATCH({$flds}) AGAINST (%s {$mode}) AS `relevance`", $required_terms ); // phpcs:ignore
			$having       = 'HAVING `relevance` >= 1';
		}

		if ( ! empty( $ds['search_tags'] ) ) {
			$names   = $this->_split_text_comma( $required_terms );
			$slugs   = array_values( array_unique( array_map( 'sanitize_title', $names ) ) );
			$req_ids = ! empty( $slugs ) ? $this->get_term_ids( $slugs, false ) : [];

			if ( ! empty( $req_ids ) ) {
				$ph_t         = rtrim( str_repeat( '%d,', count( $req_ids ) ), ',' );
				$count_having = $wpdb->prepare( 'HAVING COUNT(*) >= %d', count( $req_ids ) ); // phpcs:ignore
				$sub          = $wpdb->prepare( // phpcs:ignore
					"SELECT tr.`object_id`
					 FROM `{$wpdb->term_relationships}` AS tr
					 INNER JOIN `{$wpdb->term_taxonomy}` AS tt
					        ON tr.`term_taxonomy_id` = tt.`term_taxonomy_id`
					 WHERE tt.`taxonomy` IN ('ngg_tag')
					 AND   tt.`term_id`  IN ({$ph_t})
					 GROUP BY tr.`object_id`
					 {$count_having}",
					$req_ids
				);
				$having .= ( '' !== $having ? ' OR ' : 'HAVING ' )
				           . "`nggpictures`.`pid` IN ({$sub})";
			}
		}

		if ( ! empty( $ds['enable_tag_filter'] ) && ! empty( $term_ids ) ) {
			$ph_f       = rtrim( str_repeat( '%d,', count( $term_ids ) ), ',' );
			$having_f   = $wpdb->prepare( 'HAVING COUNT(*) >= %d', count( $term_ids ) ); // phpcs:ignore
			$filter_sub = $wpdb->prepare( // phpcs:ignore
				"SELECT tr.`object_id`
				 FROM `{$wpdb->term_relationships}` AS tr
				 INNER JOIN `{$wpdb->term_taxonomy}` AS tt
				        ON tr.`term_taxonomy_id` = tt.`term_taxonomy_id`
				 WHERE tt.`taxonomy` IN ('ngg_tag')
				 AND   tt.`term_id`  IN ({$ph_f})
				 GROUP BY tr.`object_id`
				 {$having_f}",
				$term_ids
			);
			$where .= " AND `nggpictures`.`pid` IN ({$filter_sub})";
		}

		$ob    = $ds['order_by'] ?? 'pid';
		$od    = strtoupper( $ds['order_direction'] ?? 'ASC' );
		$order = 'ORDER BY ';
		if ( ! empty( $ds['order_by_relevance'] ) && ! empty( $ft_fields ) ) {
			$order .= '`relevance` DESC, ';
		}
		$order .= ( 'rand' === $ob ) ? 'RAND()' : "`nggpictures`.`{$ob}` {$od}";

		$limit = '';
		if ( intval( $ds['limit'] ?? 0 ) > 0 ) {
			$limit = $wpdb->prepare( ' LIMIT %d', intval( $ds['limit'] ) ); // phpcs:ignore
		}

		$sql     = "SELECT `nggpictures`.`pid` {$select_extra}
		            FROM   `{$wpdb->nggpictures}` AS `nggpictures`
		            {$where} {$having} {$order} {$limit}";
		$results = (array) $wpdb->get_col( $sql ); // phpcs:ignore

		$tm->set( $cache_key, $results, NGG_DISPLAYED_GALLERY_CACHE_TTL );
		return $results;
	}

	// =========================================================================
	// get_image_terms  (adds $limit and filterable exclusion list)
	// =========================================================================

	/**
	 * Returns related tags for a set of image PIDs.
	 *
	 * Inner query: top $limit tags by usage count DESC.
	 * Outer query: re-sorts A-Z for display.
	 *
	 * Excluded tags: add_filter('ngg_pro_tagcloud_excluded_tags', fn($t) => [...]);
	 *
	 * @param int[] $image_ids
	 * @param int   $limit      0 = no limit.
	 */
	public function get_image_terms( array $image_ids = [], int $limit = 0 ) : array {
		if ( empty( $image_ids ) ) {
			return [];
		}

		global $wpdb;

		/** @param string[] $excluded */
		$excluded = (array) \apply_filters( 'ngg_pro_tagcloud_excluded_tags', [] );

		$ph_ids  = rtrim( str_repeat( '%d,', count( $image_ids ) ), ',' );
		$exc_sql = '';
		if ( ! empty( $excluded ) ) {
			$ph_exc  = rtrim( str_repeat( '%s,', count( $excluded ) ), ',' );
			$exc_sql = $wpdb->prepare( "AND t.`name` NOT IN ({$ph_exc})", $excluded ); // phpcs:ignore
		}
		$limit_sql = ( $limit > 0 ) ? $wpdb->prepare( 'LIMIT %d', $limit ) : ''; // phpcs:ignore

		$sql     = $wpdb->prepare( // phpcs:ignore
			"SELECT DISTINCT xx.`name`, xx.`slug`, xx.`term_id`, xx.`count`
			 FROM (
			     SELECT DISTINCT t.`name`, t.`slug`, t.`term_id`, tt.`count`
			     FROM   `{$wpdb->term_relationships}` tr
			     INNER JOIN `{$wpdb->term_taxonomy}` AS tt ON tr.`term_taxonomy_id` = tt.`term_taxonomy_id`
			     INNER JOIN `{$wpdb->terms}` AS t ON tt.`term_id` = t.`term_id`
			     WHERE  tr.`object_id` IN ({$ph_ids})
			     AND    tt.`taxonomy` = 'ngg_tag'
			     {$exc_sql}
			     ORDER BY tt.`count` DESC
			     {$limit_sql}
			 ) AS xx
			 ORDER BY xx.`name` ASC",
			$image_ids
		);
		$results = $wpdb->get_results( $sql, ARRAY_A ); // phpcs:ignore
		return is_array( $results ) ? $results : [];
	}

	// =========================================================================
	// get_required_related_terms_links
	// =========================================================================

	public function get_required_related_terms_links(
		string $required_terms,
		array  $search_results = [],
		array  $tagfilter      = [],
		int    $limit          = 20
	) : array {
		$app     = Router::get_instance()->get_routed_app();
		$terms   = $this->get_image_terms( $search_results, $limit );
		$encoded = str_replace( ' ', '%20', $required_terms );
		$buttons = [];

		if ( ! empty( $tagfilter ) ) {
			$clear = $this->set_parameter_value( 'nggsearch', $encoded, null, false, \get_page_link() );
			$clear = $app->remove_parameter( 'tagfilter', null, $clear );
			$buttons['ngg-clear-tag-filter'] = [
				'name' => __( 'Clear filters', 'nextgen-gallery-pro' ),
				'slug' => 'ngg-clear-tag-filter', 'type' => 'clearsearchfilters',
				'url'  => $clear, 'count' => 0,
			];
		}

		foreach ( $terms as $term ) {
			if ( strtoupper( $term['name'] ) === strtoupper( $required_terms ) ) {
				continue;
			}
			$slug      = $term['slug'];
			$is_active = in_array( $slug, $tagfilter, true );

			if ( $is_active ) {
				$new = array_values( array_filter( $tagfilter, function ( $s ) use ( $slug ) { return $s !== $slug; } ) );
				if ( ! empty( $new ) ) {
					$url = $this->set_parameter_value( 'nggsearch', $encoded, null, false, \get_page_link() );
					$url = $this->set_parameter_value( 'tagfilter', implode( ',', $new ), null, false, $url );
				} else {
					$url = $this->set_parameter_value( 'nggsearch', $encoded, null, false, \get_page_link() );
					$url = $app->remove_parameter( 'tagfilter', null, $url );
				}
				$buttons[ $slug ] = [ 'name' => $term['name'], 'slug' => $slug, 'type' => 'del', 'url' => $url, 'count' => $term['count'] ];
			} else {
				$new = array_merge( $tagfilter, [ $slug ] );
				$url = $this->set_parameter_value( 'nggsearch', $encoded, null, false, \get_page_link() );
				$url = $this->set_parameter_value( 'tagfilter', implode( ',', $new ), null, false, $url );
				$buttons[ $slug ] = [ 'name' => $term['name'], 'slug' => $slug, 'type' => 'add', 'url' => $url, 'count' => $term['count'] ];
			}
		}
		return $buttons;
	}

	// =========================================================================
	// get_related_terms_links  (overridden to add $limit)
	// =========================================================================

	public function get_related_terms_links(
		string $search_term,
		array  $search_results  = [],
		array  $tagfilter_param = [],
		int    $limit           = 20
	) : array {
		$app     = Router::get_instance()->get_routed_app();
		$terms   = $this->get_image_terms( $search_results, $limit );
		$encoded = str_replace( ' ', '%20', $search_term );
		$buttons = [];

		if ( ! empty( $tagfilter_param ) ) {
			$clear = $this->set_parameter_value( 'nggsearch', $encoded, null, false, \get_page_link() );
			$clear = $app->remove_parameter( 'tagfilter', null, $clear );
			$buttons['ngg-clear-tag-filter'] = [
				'name' => __( 'Clear filters', 'nextgen-gallery-pro' ),
				'slug' => 'ngg-clear-tag-filter', 'type' => 'clearsearchfilters',
				'url'  => $clear, 'count' => 0,
			];
		}

		foreach ( $terms as $term ) {
			if ( strtoupper( $term['name'] ) === strtoupper( $search_term ) ) {
				continue;
			}
			$slug      = $term['slug'];
			$is_active = in_array( $slug, $tagfilter_param, true );

			if ( $is_active ) {
				$new = array_values( array_filter( $tagfilter_param, function ( $s ) use ( $slug ) { return $s !== $slug; } ) );
				if ( ! empty( $new ) ) {
					$url = $this->set_parameter_value( 'nggsearch', $encoded, null, false, \get_page_link() );
					$url = $this->set_parameter_value( 'tagfilter', implode( ',', $new ), null, false, $url );
				} else {
					$url = $this->set_parameter_value( 'nggsearch', $encoded, null, false, \get_page_link() );
					$url = $app->remove_parameter( 'tagfilter', null, $url );
				}
				$buttons[ $slug ] = [ 'name' => $term['name'], 'slug' => $slug, 'type' => 'del', 'url' => $url, 'count' => $term['count'] ];
			} else {
				$new = array_merge( $tagfilter_param, [ $slug ] );
				$url = $this->set_parameter_value( 'nggsearch', $encoded, null, false, \get_page_link() );
				$url = $this->set_parameter_value( 'tagfilter', implode( ',', $new ), null, false, $url );
				$buttons[ $slug ] = [ 'name' => $term['name'], 'slug' => $slug, 'type' => 'add', 'url' => $url, 'count' => $term['count'] ];
			}
		}
		return $buttons;
	}

	// =========================================================================
	// URL helpers
	// =========================================================================

	/**
	 * On "tagsearch" pages: search and tagfilter URLs include #proTagSearch.
	 * On all other pages:   nggsearch segment is stripped; tagfilter anchor kept.
	 */
	public function set_search_page_parameter( $retval, $key, $value = null, $id = null, $use_prefix = null ) {
		$slug         = preg_quote( Settings::get_instance()->get( 'router_param_slug' ), '#' );
		$is_tagsearch = ( strpos( \get_page_link(), 'tagsearch' ) !== false );

		if ( preg_match( "#(/{$slug}/.*)nggsearch--(.*)#", $retval, $m ) ) {
			if ( $is_tagsearch ) {
				$retval = rtrim( str_replace( $m[0], rtrim( $m[1], '/' ) . '/search/' . ltrim( $m[2], '/' ) . '/#proTagSearch', $retval ), '/' );
			} else {
				$retval = rtrim( str_replace( $m[0], '', $retval ), '/' );
			}
		}

		if ( preg_match( "#(/{$slug}/.*)tagfilter--(.*)#", $retval, $m ) ) {
			$retval = rtrim( str_replace( $m[0], rtrim( $m[1], '/' ) . '/tagfilter/' . ltrim( $m[2], '/' ) . '/#proTagSearch', $retval ), '/' );
		}

		return $retval;
	}

	// =========================================================================
	// Private helpers
	// =========================================================================

	private function get_tagfilter_slugs( array $ds ) : array {
		if ( empty( $ds['enable_tag_filter'] ) ) {
			return [];
		}
		$raw = Router::get_instance()->get_parameter( 'tagfilter' );
		return $raw ? array_values( array_filter( explode( ',', (string) $raw ) ) ) : [];
	}

	// =========================================================================
	// Child display-type resolution
	// =========================================================================

	/**
	 * Returns the display type to use for child gallery rendering.
	 *
	 * Defaults to NGG_PRO_MOSAIC and silently upgrades any shortcode that still
	 * carries the old 3.x default ('photocrati-nextgen_basic_thumbnails') stored
	 * in the database from before this update.
	 *
	 * @param array $ds Display settings.
	 * @return string
	 */
	private function resolve_child_display_type( array $ds ) : string {
		$type = $ds['gallery_display_type'] ?? NGG_PRO_MOSAIC;
		if ( empty( $type ) || 'photocrati-nextgen_basic_thumbnails' === $type ) {
			$type = NGG_PRO_MOSAIC;
		}
		return $type;
	}


	/**
	 * Splits on commas only so multi-word tags like "blue ocean" are preserved.
	 */
	public function _split_text_comma( string $str ) : array { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		return array_values( array_filter( array_map( 'trim', explode( ',', stripslashes( $str ) ) ) ) );
	}
}
