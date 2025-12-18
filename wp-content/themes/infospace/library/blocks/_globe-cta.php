<?php
function globe_cta_block_assets()
{
  register_block_type(
    'theme/globe-cta',
    array(
      'render_callback' => 'render_globe_cta',
    )
  );
  
}
add_action('init', 'globe_cta_block_assets');

function render_globe_cta($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_globe-cta'); ?>

		<?php return ob_get_clean();
  }
