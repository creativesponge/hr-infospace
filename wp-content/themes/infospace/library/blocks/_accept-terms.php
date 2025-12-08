<?php
function theme_accept_terms_block_assets()
{
    register_block_type(
        'theme/accept-terms',
        array(
            'render_callback' => 'theme_render_accept_terms',
        )
    );
}
add_action('init', 'theme_accept_terms_block_assets');

function theme_render_accept_terms($attributes, $content)
{
    ob_start();
    set_query_var('attributes', $attributes);
    set_query_var('content', $content); ?>

		<?php echo get_template_part('template-parts/blocks/_accept-terms'); ?>

		<?php return ob_get_clean();
    }
