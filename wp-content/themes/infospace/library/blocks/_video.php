<?php
function theme_video_block_assets()
{
  register_block_type(
    'theme/video',
    array(
      'render_callback' => 'theme_render_video',
    )
  );
}
add_action('init', 'theme_video_block_assets');

function theme_render_video($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_video'); ?>
		<?php return ob_get_clean();
  }
