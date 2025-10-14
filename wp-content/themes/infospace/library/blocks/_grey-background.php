<?php
function theme_grey_background_block_assets() {
  register_block_type(
    'theme/grey-background', array(
      'render_callback' => 'theme_render_grey_background_',
    )
  );
}
add_action( 'init', 'theme_grey_background_block_assets' );

function theme_render_grey_background_( $attributes, $content ) {
		ob_start();
		set_query_var( 'attributes', $attributes );
        set_query_var( 'content', $content );?>

		<?php get_template_part( 'template-parts/blocks/_grey-background' ); ?>

		<?php return ob_get_clean();

 }
