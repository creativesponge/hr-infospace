<?php
function theme_posts_list_assets()
{
  register_block_type(
    'theme/posts-list',
    array(
      'render_callback' => 'theme_render_posts_list',
    )
  );
}
add_action('init', 'theme_posts_list_assets');

function theme_render_posts_list($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_posts-list'); ?>
		<?php return ob_get_clean();
  }
