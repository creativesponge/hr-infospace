<?php
function theme_page_contact_block_assets() {
  register_block_type(
    'theme/page-contact', array(
      'render_callback' => 'theme_render_page_contact',
    )
  );
}
add_action( 'init', 'theme_page_contact_block_assets' );

function theme_render_page_contact( $attributes, $content ) {
		ob_start();
		set_query_var( 'attributes', $attributes );
    set_query_var( 'content', $content );?>

		<?php get_template_part( 'template-parts/blocks/_page-contact' ); ?>

		<?php return ob_get_clean();

 }
