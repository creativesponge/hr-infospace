<?php

function theme_custom_login_block_assets()
{
    register_block_type(
        'theme/custom-login',
        array(
            'render_callback' => 'theme_render_custom_login',
        )
    );
}
add_action('init', 'theme_custom_login_block_assets');

function theme_render_custom_login($attributes, $content)
{
    ob_start();
    set_query_var('attributes', $attributes);
    set_query_var('content', $content); ?>

		<?php echo get_template_part('template-parts/blocks/_custom-login'); ?>

		<?php return ob_get_clean();
    }
