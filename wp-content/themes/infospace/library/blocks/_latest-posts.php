<?php
function theme_latest_posts_assets()
{
  register_block_type(
    'theme/latest-posts',
    array(
      'render_callback' => 'theme_render_latest_posts',
    )
  );
}
add_action('init', 'theme_latest_posts_assets');

function theme_render_latest_posts($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_latest-posts'); ?>
		<?php return ob_get_clean();
  }
