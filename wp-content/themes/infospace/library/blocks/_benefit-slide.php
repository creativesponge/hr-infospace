<?php
function benefit_slide_block_assets()
{

  register_block_type(
    'theme/benefit-slide',
    array(
      'render_callback' => 'render_benefit_slide',
    )
  );
}
add_action('init', 'benefit_slide_block_assets');

function render_benefit_slide($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_benefit-slide'); ?>

		<?php return ob_get_clean();
  }
