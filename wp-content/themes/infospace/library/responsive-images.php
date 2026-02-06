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
add_image_size( 'imagetext', 936, 0, false );
add_image_size( 'imagetextlarge', 1136, 1136, true );
add_image_size( 'fpsmall', 640, 0, false );
add_image_size( 'fpmedium', 1024, 0, false );
add_image_size( 'fplarge', 1200, 0, false ); // 0 height = proportional
add_image_size( 'fpxlarge', 1400, 0, false );
add_image_size( 'smallsquare', 300, 300, true );
add_image_size( 'service', 448, 503 , true );
add_image_size( 'servicemob', 280, 160, true );
add_image_size( 'servicetab', 232, 306, true );
add_image_size( 'modulebanner', 923, 362, true );
add_image_size( 'modulemob', 224, 448, true );
add_image_size( 'featurednews', 470, 242, true );
add_image_size( 'benefit', 360, 360, true );

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

/**
 * Alternative approach: Directly modify image attributes to add sizes="auto"
 */
add_filter( 'wp_get_attachment_image_attributes', function( $attr, $attachment, $size ) {
    // Only for our custom sizes
    if ( in_array( $size, [ 'fplarge', 'fpxlarge', 'fpmedium', 'fpsmall' ] ) ) {
        // Add sizes attribute with auto and fallback
        switch( $size ) {
            case 'fplarge':
                $attr['sizes'] = '(max-width: 1200px) 100vw, 1200px';
                break;
            case 'fpxlarge':
                $attr['sizes'] = '(max-width: 1400px) 100vw, 1400px';
                break;
            case 'fpmedium':
                $attr['sizes'] = '(max-width: 1024px) 100vw, 1024px';
                break;
            case 'fpsmall':
                $attr['sizes'] = '(max-width: 640px) 100vw, 640px';
                break;
        }
    }
    return $attr;
}, 10, 3 );
