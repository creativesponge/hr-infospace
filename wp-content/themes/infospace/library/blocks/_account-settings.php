<?php
function theme_account_settings_block_assets() {
  register_block_type(
    'theme/account-settings', array(
      'render_callback' => 'theme_render_account_settings',
    )
  );
}
add_action( 'init', 'theme_account_settings_block_assets' );

function theme_render_account_settings( $attributes, $content ) {
		ob_start();
		set_query_var( 'attributes', $attributes );
    set_query_var( 'content', $content );?>

		<?php get_template_part( 'template-parts/blocks/_account-settings' ); ?>

		<?php return ob_get_clean();

 }
