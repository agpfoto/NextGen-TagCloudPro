<?php
/**
 * TagCloud – default template (tag-filter buttons only).
 * Used on pages whose URL does NOT contain "tagsearch".
 *
 * @var array|null $related_term_links  Tag-button data.
 * @var string     $gallery_display     Rendered child display-type HTML.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ngg-image-search-container ngg-pro-tagcloud-container">

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
