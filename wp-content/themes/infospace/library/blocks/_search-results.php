<?php
function theme_search_results_assets()
{
  register_block_type(
    'theme/search-results',
    array(
      'render_callback' => 'theme_render_search_results',
    )
  );
}
add_action('init', 'theme_search_results_assets');

function theme_render_search_results($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>
		<?php get_template_part('template-parts/blocks/_search-results'); ?>
		<?php return ob_get_clean();
  }
