<?php
/**
 * TagCloud – searchdefault template (search form + tag-filter buttons).
 * Used on pages whose URL contains "tagsearch".
 * id="proTagSearch" lets tag-button URLs scroll the page here.
 *
 * @var array  $i18n               Localised strings.
 * @var string $search_term        Current search term.
 * @var string $form_submit_url    POST action for no-JS browsers.
 * @var string $form_redirect_url  JS redirect URL.
 * @var array|null $related_term_links  Tag-button data.
 * @var string $gallery_display    Rendered child display-type HTML.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ngg-image-search-container ngg-image-AGPsearch-container ngg-pro-tagcloud-container"
     id="proTagSearch">

	<form method="POST"
	      class="ngg-image-search-form"
	      action="<?php echo esc_attr( $form_submit_url ); ?>"
	      data-submission-url="<?php echo esc_attr( $form_redirect_url ); ?>">

		<input type="hidden" name="nggsearch-do-redirect" value="1"/>

		<input type="text"
		       class="ngg-image-search-input"
		       name="nggsearch"
		       value="<?php echo esc_attr( $search_term ); ?>"
		       placeholder="<?php echo esc_attr( $i18n['input_placeholder'] ); ?>"/>

		<input type="submit"
		       class="ngg-image-search-button"
		       value="<?php echo esc_attr( $i18n['button_label'] ); ?>"/>
	</form>

	<?php if ( ! empty( $related_term_links ) ) : ?>
		<div class="ngg-image-search-filter ngg-pro-tagcloud-filter">
			<h4><?php esc_html_e( 'Filter by related tags', 'nextgen-gallery-pro' ); ?></h4>
			<div class="ngg-filter-by-tags">
				<?php foreach ( $related_term_links as $term_link ) : ?>
					<a href="<?php echo esc_url( $term_link['url'] ); ?>"
					   class="button <?php echo esc_attr( $term_link['type'] ); ?>">
						<?php echo esc_html( $term_link['name'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

</div>
<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $gallery_display;
