<?php
function theme_google_map_block_assets() {
  register_block_type(
    'theme/google-map', array(
      'render_callback' => 'theme_render_google_map',
    )
  );
}
add_action( 'init', 'theme_google_map_block_assets' );

function theme_render_google_map( $attributes, $content ) {
		ob_start();
		set_query_var( 'attributes', $attributes );
    set_query_var( 'content', $content );?>

		<?php get_template_part( 'template-parts/blocks/_google-map' ); ?>

		<?php return ob_get_clean();

 }
