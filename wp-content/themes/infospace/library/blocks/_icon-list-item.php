<?php
function theme_icon_list_item_assets()
{
  register_block_type(
    'theme/icon-list-item',
    array(
      'render_callback' => 'theme_render_icon_list_item',
    )
  );
}
add_action('init', 'theme_icon_list_item_assets');

function theme_render_icon_list_item($attributes, $content)
{
  ob_start();
  set_query_var('attributes', $attributes);
  set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_icon-list-item'); ?>
		<?php return ob_get_clean();
  }
