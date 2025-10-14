<?php

/**
 * Add a theme specific block category if it doesn't exist already.
 *
 * @param array $categories Array of block categories.
 *
 * @return array
 */
function theme_block_categories( $categories ) {
    $custom_block = array(
        'slug'  => 'theme-specific',
        'title' => __( 'Theme specific', 'theme-specific' ),
         'icon'  => null,
    );

    $categories_sorted = array();
    $categories_sorted[0] = $custom_block;

    foreach ($categories as $category) {
        $categories_sorted[] = $category;
    }

    return $categories_sorted;
}
add_filter( 'block_categories_all', 'theme_block_categories', 10, 2);
