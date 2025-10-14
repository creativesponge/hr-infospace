<?php
function theme_breadcrumbs_block_assets() {
  register_block_type(
    'theme/breadcrumbs', array(
      'render_callback' => 'theme_render_breadcrumbs',
    )
  );
}
add_action( 'init', 'theme_breadcrumbs_block_assets' );

function theme_render_breadcrumbs( $attributes, $content ) {
		ob_start();
		set_query_var( 'attributes', $attributes );
    set_query_var( 'content', $content );?>

		<?php get_template_part( 'template-parts/blocks/_breadcrumbs' ); ?>

		<?php return ob_get_clean();

 }
