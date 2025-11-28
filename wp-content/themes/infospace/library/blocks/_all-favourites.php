<?php
function theme_all_favourites_assets()
{
  register_block_type(
    'theme/all-favourites',
    array(
      'render_callback' => 'theme_render_all_favourites',
    )
  );
}
add_action('init', 'theme_all_favourites_assets');

function theme_render_all_favourites($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_all-favourites'); ?>
		<?php return ob_get_clean();
  }
