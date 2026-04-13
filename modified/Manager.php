<?php

/**
 * Provides overrides for Pro display type controllers over the NextGEN parent.
 *
 * @package NextGEN Gallery Pro
 */
namespace Imagely\NGGPro\DisplayType;

use Imagely\NGG\Util\Transient as TransientManager;
use Imagely\NGG\DataTypes\DisplayedGallery;
use Imagely\NGG\DisplayType\ControllerFactory;
use Imagely\NGG\Settings\Settings;
use Imagely\NGG\Util\Router;
/**
 * Provides overrides for Pro display type controllers over the NextGEN parent.
 */
class Manager
{
    /**
     * Register the display types
     */
    public static function register()
    {
        // thumbnail grid.
        /**
         * Starter plugin does not have ThumbnailGrid display type.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_THUMBNAIL_GRID, '\\Imagely\\NGGPro\\DisplayTypes\\ThumbnailGrid', ['thumbnail', 'thumbnails', 'pro_thumbnail', 'pro_thumbnails', 'pro_thumbnail_grid', 'thumbnail_grid', 'nextgen_pro_thumbnail_grid', 'nextgen_pro_thumbnails', 'nextgen_pro_thumbnail']);
        }
        // blog gallery.
        /**
         * Gallery display type is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_BLOG_GALLERY, '\\Imagely\\NGGPro\\DisplayTypes\\BlogGallery', ['blog_gallery', 'pro_blog', 'pro_blog_gallery', 'nextgen_pro_blog', 'nextgen_pro_blog_gallery', 'nextgen_pro_blog_style']);
        }
        // imagebrowser.
        /**
         * Gallery display type is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_IMAGEBROWSER, '\\Imagely\\NGGPro\\DisplayTypes\\ImageBrowser', ['pro_imagebrowser', 'nextgen_pro_imagebrowser']);
        }
        // sidescroll.
        /**
         * Gallery display type is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_SIDESCROLL, '\\Imagely\\NGGPro\\DisplayTypes\\SideScroll', ['sidescroll', 'pro_sidescroll']);
        }
        // film.
        ControllerFactory::register_controller(NGG_PRO_FILM, '\\Imagely\\NGGPro\\DisplayTypes\\Film', ['film', 'pro_film']);
        // tile.
        /**
         * Starter plugin does not have Tile display type.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_TILE, '\\Imagely\\NGGPro\\DisplayTypes\\Tile', ['tile', 'pro_tile', 'nextgen_pro_tile']);
        }
        // mosaic.
        ControllerFactory::register_controller(NGG_PRO_MOSAIC, '\\Imagely\\NGGPro\\DisplayTypes\\Mosaic', ['mosaic', 'pro_mosaic', 'nextgen_pro_mosaic']);
        // masonry.
        /**
         * Masonry is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_MASONRY, '\\Imagely\\NGGPro\\DisplayTypes\\Masonry', ['masonry', 'pro_masonry', 'nextgen_pro_masonry']);
        }
        // search.
        /**
         * Search display type is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_SEARCH, '\\Imagely\\NGGPro\\DisplayTypes\\Search', []);
        // TagCloud display type.
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_TAGCLOUD, '\\Imagely\\NGGPro\\DisplayTypes\\TagCloud', ['nextgen-pro-tagcloud', 'nextgen_pro_tagcloud']);
        }
        }
        // horizontal filmstrip.
        /**
         * Filmstrip display type is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_HORIZONTAL_FILMSTRIP, '\\Imagely\\NGGPro\\DisplayTypes\\HorizontalFilmstrip', ['horizontal_filmstrip', 'pro_horizontal_filmstrip', 'nextgen_pro_horizontal_filmstrip']);
        }
        // slideshow.
        /**
         * Slideshow display type is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_SLIDESHOW, '\\Imagely\\NGGPro\\DisplayTypes\\Slideshow', ['slideshow', 'pro_slideshow', 'nextgen_pro_slideshow']);
        }
        // grid album.
        /**
         * Pro Grid Album display type is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_GRID_ALBUM, '\\Imagely\\NGGPro\\DisplayTypes\\GridAlbum', ['pro_grid_album', 'grid_album', 'nextgen_pro_grid_album']);
        }
        // list album.
        /**
         * List Album display type is not available in Starter plugin.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
            ControllerFactory::register_controller(NGG_PRO_LIST_ALBUM, '\\Imagely\\NGGPro\\DisplayTypes\\ListAlbum', ['pro_list_album', 'list_album', 'nextgen_pro_list_album']);
        }
        \do_action('ngg_pro_display_types_registered');
    }
    /**
     * The above register() method is called on the init action so that it can use NextGEN's classes and methods. This,
     * register_hooks() is called at plugin initialization time, so that the hooks are already registered when NextGEN,
     * begins using them.
     *
     * @return void
     */
    public static function register_hooks()
    {
        /**
         * Prevent the Search display type from being used in a widget.
         *
         * Due to its use of URL as a key component of how frontend search functions, we currently just do not,
         * support this display type as a widget. If there's no page or post, we simply don't do anything.
         *
         * @remove-for-nextgen-starter
         * @param string $text The text of the widget.
         * @return string
         */
        \add_action('widget_text', function ($text) {
            preg_match_all('/' . \get_shortcode_regex() . '/', $text, $matches, PREG_SET_ORDER);
            foreach ($matches as $shortcode) {
                if ('ngg' !== $shortcode[2]) {
                    continue;
                }
                $found = preg_match("/display=['\"](imagely-search|nextgen-pro-tagcloud)[\"']/i", $shortcode[3]);
                if ($found) {
                    $text = str_replace($shortcode[0], '', $text);
                }
            }
            return $text;
        });
        $self = new self();
        // These are necessary for the Search display type to avoid issues caused by display type caching.
        add_action('ngg_added_new_image', [$self, 'flush_cache']);
        add_action('ngg_album_updated', [$self, 'flush_cache']);
        add_action('ngg_delete_album', [$self, 'flush_cache']);
        add_action('ngg_delete_gallery', [$self, 'flush_cache']);
        add_action('ngg_delete_image', [$self, 'flush_cache']);
        add_action('ngg_delete_picture', [$self, 'flush_cache']);
        add_action('ngg_image_updated', [$self, 'flush_cache']);
        add_action('ngg_manage_tags', [$self, 'flush_cache']);
        add_action('ngg_recovered_image', [$self, 'flush_cache']);
        add_action('ngg_updated_image_meta', [$self, 'flush_cache']);
        // @remove-for-nextgen-starter.
        add_filter('ngg_basic_tagcloud_excluded_display_types', [$self, 'exclude_albums_from_tagcloud']);
        // @remove-for-nextgen-starter.
        add_action('ngg_do_app_rewrites', [$self, 'do_app_rewrites']);
        // @remove-for-nextgen-starter.
        add_action('ngg_routes', function ($router) {
            $slug = '/' . Settings::get_instance()->get('router_param_slug');
            // ImageBrowser.
            $router->rewrite("{*}{$slug}{*}/image/{\\w}", "{1}{$slug}{2}/pid--{3}");
            // Search.
            $router->rewrite("{*}{$slug}{*}/search/{*}/tagfilter/{*}", "{1}{$slug}{2}/nggsearch--{3}/tagfilter--{4}");
            $router->rewrite("{*}{$slug}{*}/search/{*}", "{1}{$slug}{2}/nggsearch--{3}");
            // TagCloud: tagfilter without search.
            $router->rewrite("{*}{$slug}{*}/tagfilter/{*}", "{1}{$slug}{2}/tagfilter--{3}");
        });
    }
    /**
     * Flush the frontend image search cache.
     *
     * @return void
     */
    public function flush_cache()
    {
        $transient_manager = TransientManager::get_instance();
        $transient_manager->clear('frontend_image_search');
        $transient_manager->clear(NGG_PRO_TAGCLOUD);
    }
    /**
     * Exclude albums from the tag cloud.
     *
     * @remove-for-nextgen-starter
     * @param array $types The types to exclude.
     * @return array
     */
    public function exclude_albums_from_tagcloud($types = [])
    {
        $types[] = NGG_PRO_GRID_ALBUM;
        $types[] = NGG_PRO_LIST_ALBUM;
        return $types;
    }
    /**
     * Display type rewrites.
     *
     * @remove-for-nextgen-starter
     * @param DisplayedGallery $displayed_gallery The displayed gallery.
     *
     * @return void
     */
    public function do_app_rewrites($displayed_gallery)
    {
        $album_types = [NGG_PRO_ALBUMS, NGG_PRO_LIST_ALBUM, NGG_PRO_GRID_ALBUM];
        // Get the original display type.
        $original_display_type = isset($displayed_gallery->display_settings['original_display_type']) ? $displayed_gallery->display_settings['original_display_type'] : '';
        $do_rewrites = false;
        if (in_array($displayed_gallery->display_type, $album_types, true)) {
            $do_rewrites = true;
            $app = Router::get_instance()->get_routed_app();
            $settings = Settings::get_instance();
            $slug = '/' . $settings->get('router_param_slug');
            // ensure to pass $stop=TRUE to $app->rewrite() on parameters that may be shared with other display types.
            $app->rewrite('{*}' . $slug . '/page/{\\d}{*}', '{1}' . $slug . '/nggpage--{2}{3}', false, true);
            $app->rewrite('{*}' . $slug . '/page--{*}', '{1}' . $slug . '/nggpage--{2}', false, true);
            $app->rewrite('{*}' . $slug . '/{\\w}', '{1}' . $slug . '/album--{2}');
            $app->rewrite('{*}' . $slug . '/{\\w}/{\\w}', '{1}' . $slug . '/album--{2}/gallery--{3}');
            $app->rewrite('{*}' . $slug . '/{\\w}/{\\w}/{\\w}{*}', '{1}' . $slug . '/album--{2}/gallery--{3}/{4}{5}');
        } elseif (in_array($original_display_type, $album_types, true)) {
            $do_rewrites = true;
            $app = Router::get_instance()->get_routed_app();
            $settings = Settings::get_instance();
            $slug = '/' . $settings->get('router_param_slug');
            $app->rewrite("{*}{$slug}/album--{\\w}", "{1}{$slug}/{2}");
            $app->rewrite("{*}{$slug}/album--{\\w}/gallery--{\\w}", "{1}{$slug}/{2}/{3}");
            $app->rewrite("{*}{$slug}/album--{\\w}/gallery--{\\w}/{*}", "{1}{$slug}/{2}/{3}/{4}");
        }
        if (isset($app) && $do_rewrites) {
            $app->do_rewrites();
        }
    }
}