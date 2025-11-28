<?php
function theme_all_newsletters_assets()
{
  register_block_type(
    'theme/all-newsletters',
    array(
      'render_callback' => 'theme_render_all_newsletters',
    )
  );
}
add_action('init', 'theme_all_newsletters_assets');

function theme_render_all_newsletters($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_all-newsletters'); ?>
		<?php return ob_get_clean();
  }
