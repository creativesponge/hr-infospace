<?php
function theme_small_block_assets()
{
  register_block_type(
    'theme/small-block',
    array(
      'render_callback' => 'theme_render_small_block',
    )
  );
}
add_action('init', 'theme_small_block_assets');

function theme_render_small_block($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_small-block'); ?>
		<?php return ob_get_clean();
  }
