<?php

function theme_welcome_text_before_block_assets()
{
    register_block_type(
        'theme/welcome-text-before',
        array(
            'render_callback' => 'theme_render_welcome_text_before',
        )
    );
}
add_action('init', 'theme_welcome_text_before_block_assets');

function theme_render_welcome_text_before($attributes, $content)
{
    ob_start();
    set_query_var('attributes', $attributes);
    set_query_var('content', $content); ?>

		<?php echo get_template_part('template-parts/blocks/_welcome-text-before'); ?>

		<?php return ob_get_clean();
    }
