<?php
function theme_posts_list_filters_assets()
{
  register_block_type(
    'theme/posts-list-filters',
    array(
      'render_callback' => 'theme_render_posts_list_filters',
    )
  );
}
add_action('init', 'theme_posts_list_filters_assets');

function theme_render_posts_list_filters($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>
		<?php get_template_part('template-parts/blocks/_posts-list-filters'); ?>
		<?php return ob_get_clean();
  }
