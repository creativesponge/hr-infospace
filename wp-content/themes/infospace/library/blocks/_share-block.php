<?php
function theme_share_block_assets()
{
  register_block_type(
    'theme/share-block',
    array(
      'render_callback' => 'theme_render_share_block',
    )
  );
}
add_action('init', 'theme_share_block_assets');

function theme_render_share_block($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_share-block'); ?>
		<?php return ob_get_clean();
  }
