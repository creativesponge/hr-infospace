<?php

// Get meta data
function theme_get_meta($post_ID = false){
  global $prefix;
    if(!$post_ID){
        $post_ID = get_the_ID();
    }

    $meta = (array) get_metadata('post', $post_ID);
    $meta_object = new stdClass();

    foreach($meta as $key => $value) {
        if($key == '_wp_page_template' || $key == '_edit_lock' || $key == '_edit_last'){
            continue;
        }

        $key = str_replace($prefix,'',$key);

        if(is_serialized($value[0])){
            $array = unserialize($value[0]);
            $meta_object->{$key} = $array;
        } else {
            $meta_object->{$key} = $value[0];
        }
    }

    return $meta_object;
}


/**
 *  Interchange for CMB2 images
 */
function theme_get_interchange($id) {
    $return = array();
    $return['original'] = wp_get_attachment_url($id);

    $return['small'] = wp_get_attachment_image_src($id, 'fpsmall');
    $return['small'] = $return['small'][0];

    $return['medium'] = wp_get_attachment_image_src($id, 'fpmedium');
    $return['medium'] = $return['medium'][0];

    $return['large'] = wp_get_attachment_image_src($id, 'fplarge');
    $return['large'] = $return['large'][0];

    $return['xlarge'] = wp_get_attachment_image_src($id, 'fpxlarge');
    $return['xlarge'] = $return['xlarge'][0];

    echo 'data-interchange="['.$return["small"].', small],
          ['.$return["medium"].', medium],
          ['.$return["large"].', large],
          ['.$return["xlarge"].', xlarge]"';
}




/**
 *  Removing WP meta boxes
 */

add_action( 'admin_head', 'theme_remove_admin_metaboxes' );
function theme_remove_admin_metaboxes() {

    global $post;
    if(empty($post)){

        return false;
    }

    $current_template = get_post_meta($post->ID, '_wp_page_template', true);
    // Always remove these for pages
    remove_meta_box('commentstatusdiv', 'page', 'normal');
    remove_meta_box('commentsdiv', 'page', 'normal');
    remove_meta_box('postcustom', 'page', 'normal');
    remove_meta_box('authordiv', 'page', 'normal');

    // Always remove these for posts
    remove_meta_box('postcustom', 'post', 'normal');
    remove_meta_box('trackbacksdiv', 'post', 'normal');
    remove_meta_box('commentstatusdiv', 'post', 'normal');
    remove_meta_box('commentsdiv', 'post', 'normal');
    remove_meta_box('tagsdiv-post_tag', 'post', 'side');

}

// Localise ajax for form
function theme_assets() {
    if($GLOBALS['pagenow'] !== 'wp-login.php' && !is_admin()){

        wp_localize_script('foundation', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));

    }
}
add_action('wp_enqueue_scripts', 'theme_assets');

// Remove dashicons in frontend for unauthenticated users
add_action( 'wp_enqueue_scripts', 'bs_dequeue_dashicons' );
function bs_dequeue_dashicons() {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}

// Disable auto image resizing
add_filter( 'big_image_size_threshold', '__return_false' );