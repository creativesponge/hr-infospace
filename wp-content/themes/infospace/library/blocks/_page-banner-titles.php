<?php
function theme_page_banner_titles_block_assets()
{
  register_block_type(
    'theme/page-banner-titles',
    array(
      'render_callback' => 'theme_render_page_banner_titles',
    )
  );
}
add_action('init', 'theme_page_banner_titles_block_assets');

function theme_render_page_banner_titles($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes); ?>

		<?php get_template_part('template-parts/blocks/_page-banner-titles'); ?>

		<?php return ob_get_clean();
  }
