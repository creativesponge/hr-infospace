<?php

function theme_forgot_password_block_assets()
{
    register_block_type(
        'theme/forgot-password',
        array(
            'render_callback' => 'theme_render_forgot_password',
        )
    );
}
add_action('init', 'theme_forgot_password_block_assets');

function theme_render_forgot_password($attributes, $content)
{
    ob_start();
    set_query_var('attributes', $attributes);
    set_query_var('content', $content); ?>

		<?php echo get_template_part('template-parts/blocks/_forgot-password'); ?>

		<?php return ob_get_clean();
    }
