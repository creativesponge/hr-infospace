<?php
function theme_small_blocks_container_assets()
{
  register_block_type(
    'theme/small-blocks-container',
    array(

      'render_callback' => 'theme_render_small_blocks_container',
    )
  );
}
add_action('init', 'theme_small_blocks_container_assets');

function theme_render_small_blocks_container($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_small-blocks-container'); ?>
		<?php return ob_get_clean();
  }
