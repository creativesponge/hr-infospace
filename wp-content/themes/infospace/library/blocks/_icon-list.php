<?php
function theme_icon_list_assets()
{
  register_block_type(
    'theme/icon-list',
    array(

      'render_callback' => 'theme_render_icon_list',
    )
  );
}
add_action('init', 'theme_icon_list_assets');

function theme_render_icon_list($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_icon-list'); ?>
		<?php return ob_get_clean();
  }
