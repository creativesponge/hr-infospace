<?php
function image_text_carousel_block_assets()
{
    register_block_type(
        'theme/image-text-carousel-list',
        array(
            'render_callback' => 'render_image_text_carousel',
        )
    );
}
add_action('init', 'image_text_carousel_block_assets');

function render_image_text_carousel($attributes, $content)
{
    ob_start();
    set_query_var('attributes', $attributes);
    set_query_var('content', $content); ?>

		<?php get_template_part('template-parts/blocks/_image-text-carousel'); ?>

		<?php return ob_get_clean();
    }
