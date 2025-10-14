<?php
function theme_testimonials_block_assets()
{
  register_block_type(
    'theme/testimonials-carousel',
    array(
      'render_callback' => 'theme_render_testimonials',
    )
  );
}
add_action('init', 'theme_testimonials_block_assets');

function theme_render_testimonials($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_testimonials'); ?>
		<?php return ob_get_clean();
  }
