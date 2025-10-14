<?php
function theme_stats_list_block_assets()
{
  register_block_type(
    'theme/stats-list',
    array(
      'render_callback' => 'theme_render_stats',
    )
  );
}
add_action('init', 'theme_stats_list_block_assets');

function theme_render_stats($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_stats-list'); ?>
		<?php return ob_get_clean();
  }
