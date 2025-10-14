<?php
function theme_stat_block_assets()
{
  register_block_type(
    'theme/stats-item',
    array(
      'render_callback' => 'theme_render_stat',
    )
  );
}
add_action('init', 'theme_stat_block_assets');

function theme_render_stat($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_stat-item'); ?>
		<?php return ob_get_clean();
  }
