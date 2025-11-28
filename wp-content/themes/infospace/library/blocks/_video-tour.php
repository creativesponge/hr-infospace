<?php
function theme_video_tour_assets()
{
  register_block_type(
    'theme/video-tour',
    array(
      'render_callback' => 'theme_render_video_tour',
    )
  );
}
add_action('init', 'theme_video_tour_assets');

function theme_render_video_tour($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_video-tour'); ?>
		<?php return ob_get_clean();
  }
