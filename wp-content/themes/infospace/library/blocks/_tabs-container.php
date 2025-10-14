<?php
function theme_tabs_block_assets()
{
  register_block_type(
    'theme/tabs-blocks-item',
    array(
      'render_callback' => 'theme_render_tabs',
    )
  );
}
add_action('init', 'theme_tabs_block_assets');

function theme_render_tabs($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_tabs-container'); ?>
		<?php return ob_get_clean();
  }
