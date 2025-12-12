<?php

function theme_site_map_block_assets()
{
    register_block_type(
        'theme/site-map',
        array(
            'render_callback' => 'theme_render_site_map',
        )
    );
}
add_action('init', 'theme_site_map_block_assets');

function theme_render_site_map($attributes, $content)
{
    ob_start();
    set_query_var('attributes', $attributes);
    set_query_var('content', $content); ?>

		<?php echo get_template_part('template-parts/blocks/_site-map'); ?>

		<?php return ob_get_clean();
    }
