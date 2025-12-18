<?php
function benefit_carousel_block_assets()
{
    register_block_type(
        'theme/benefit-carousel-list',
        array(
            'render_callback' => 'render_benefit_carousel',
        )
    );
}
add_action('init', 'benefit_carousel_block_assets');

function render_benefit_carousel($attributes, $content)
{
    ob_start();
    set_query_var('attributes', $attributes);
    set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_benefit-carousel'); ?>

		<?php return ob_get_clean();
    }
