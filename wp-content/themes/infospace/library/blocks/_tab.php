<?php
function theme_tab_block_assets()
{
  register_block_type(
    'theme/tab-blocks-item',
    array(
      'render_callback' => 'theme_render_tab',
    )
  );
}
add_action('init', 'theme_tab_block_assets');

function theme_render_tab($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_tab'); ?>

		<?php return ob_get_clean();
  }
