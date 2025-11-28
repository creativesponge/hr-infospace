<?php
function login_register_block_assets()
{
  register_block_type(
    'theme/login-register',
    array(
      'render_callback' => 'render_login_register',
    )
  );
  
}
add_action('init', 'login_register_block_assets');

function render_login_register($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_login-register'); ?>

		<?php return ob_get_clean();
  }
