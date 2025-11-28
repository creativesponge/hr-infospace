<?php
function services_slide_block_assets()
{

  register_block_type(
    'theme/services-slide',
    array(
      'render_callback' => 'render_services_slide',
    )
  );
}
add_action('init', 'services_slide_block_assets');

function render_services_slide($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_services-slide'); ?>

		<?php return ob_get_clean();
  }
