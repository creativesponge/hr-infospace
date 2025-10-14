<?php
/**
 * Configure responsive images sizes
 *
 * @package WordPress
 */

// Add featured image sizes
//
// Sizes are optimized and cropped for landscape aspect ratio
// and optimized for HiDPI displays on 'small' and 'medium' screen sizes.
add_image_size( 'imagetext', 500, 500, true );
add_image_size( 'fpsmall', 640 );
add_image_size( 'fpmedium', 1024 );
add_image_size( 'fplarge', 1200 );
add_image_size( 'fpxlarge', 1400 );
add_image_size( 'smallsquare', 300, 300, true );

// Register the new image sizes for use in the add media modal in wp-admin
function startertheme_custom_sizes( $sizes ) {
	return array_merge(
		$sizes, array(
			'imagetext' => __( 'Image text' ),
			'fpsmall'  => __( 'FP Small' ),
			'fpmedium' => __( 'FP Medium' ),
			'fplarge'  => __( 'FP Large' ),
			'fpxlarge' => __( 'FP XLarge' ),

		)
	);
}
add_filter( 'image_size_names_choose', 'startertheme_custom_sizes' );