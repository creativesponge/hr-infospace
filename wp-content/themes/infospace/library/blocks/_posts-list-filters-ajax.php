<?php
function theme_posts_list_filters_ajax_assets()
{
  register_block_type(
    'theme/posts-list-filters-ajax',
    array(
      'render_callback' => 'theme_render_posts_list_filters_ajax',
    )
  );
}
add_action('init', 'theme_posts_list_filters_ajax_assets');

function theme_render_posts_list_filters_ajax($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>
		<?php get_template_part('template-parts/blocks/_posts-list-filters-ajax'); ?>
		<?php return ob_get_clean();
  }
