<?php
function theme_logo_list_block_assets()
{
  register_block_type(
    'theme/logo-list',
    array(
      'render_callback' => 'theme_render_logo_list_',
    )
  );
}

add_action('init', 'theme_logo_list_block_assets');
function theme_render_logo_list_($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_logo-list'); ?>

		<?php return ob_get_clean();
  }
