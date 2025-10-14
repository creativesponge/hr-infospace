<?php
function theme_narrow_content_block_assets() {
  register_block_type(
    'theme/narrow-content', array(
      // Render the related posts
      'render_callback' => 'theme_render_narrow_content_',
    )
  );
}
// Hook: Block assets.
add_action( 'init', 'theme_narrow_content_block_assets' );

function theme_render_narrow_content_( $attributes, $content ) {

		ob_start();
		set_query_var( 'attributes', $attributes );
        set_query_var( 'content', $content );?>

		<?php get_template_part( 'template-parts/blocks/_narrow-content' ); ?>

		<?php return ob_get_clean();

 }
