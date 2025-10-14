<?php
function theme_accordion_block_assets() {
  register_block_type(
    'theme/accordion-blocks-item', array(
      'render_callback' => 'theme_render_accordion',
    )
  );
}
add_action( 'init', 'theme_accordion_block_assets' );

function theme_render_accordion( $attributes, $content ) {
		ob_start();
		set_query_var( 'attributes', $attributes );
        set_query_var( 'content', $content );?>

		<?php get_template_part( 'template-parts/blocks/_accordion' ); ?>

		<?php return ob_get_clean();

 }
