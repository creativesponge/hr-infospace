<?php
function theme_team_block_assets()
{
  register_block_type(
    'theme/team',
    array(
      'render_callback' => 'theme_render_team',
    )
  );
}
add_action('init', 'theme_team_block_assets');

function theme_render_team($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_teams'); ?>
		<?php return ob_get_clean();
  }
