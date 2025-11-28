<?php
function services_carousel_block_assets()
{
    register_block_type(
        'theme/services-carousel-list',
        array(
            'render_callback' => 'render_services_carousel',
        )
    );
}
add_action('init', 'services_carousel_block_assets');

function render_services_carousel($attributes, $content)
{
    ob_start();
    set_query_var('attributes', $attributes);
    set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_services-carousel'); ?>

		<?php return ob_get_clean();
    }
