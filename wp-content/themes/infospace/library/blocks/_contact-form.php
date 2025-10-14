<?php
function theme_contact_form_block_assets() {
  register_block_type(
    'theme/contact-form', array(
      'render_callback' => 'theme_render_contact_form',
    )
  );
}
add_action( 'init', 'theme_contact_form_block_assets' );

function theme_render_contact_form( $attributes, $content ) {
		ob_start();
		set_query_var( 'attributes', $attributes );
    set_query_var( 'content', $content );?>

		<?php get_template_part( 'template-parts/blocks/_contact-form' ); ?>

		<?php return ob_get_clean();

 }
