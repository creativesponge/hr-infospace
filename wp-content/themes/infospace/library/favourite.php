<?php
// Favourites functionality
// When a user clicks on a button with the class 'add-to-favourites', we use the post ID from data-id on the button create a new post as a 'favourite' custom post type.
// If it is a resource the new post will have a meta field $prefix . 'favourite_attached_resources' that is the data-id.
// If it is a link the new post will have a meta field $prefix . 'favourite_attached_links' that is the data-id.
// If it is a document the new post will have a meta field $prefix . 'favourite_attached_documents' that is the data-id.
// Use the users id as the author of the favourite post to link it to them.
// Use data-name on the button to set the title of the favourite post.
// If the favourite already exists for that user then remove the favourite post for that user.

// Enqueue the JavaScript for favourites functionality
add_action('wp_ajax_toggle_favourite', 'handle_toggle_favourite');
add_action('wp_ajax_nopriv_toggle_favourite', 'handle_toggle_favourite');


// favourite assets
function enqueue_favourites()
{

    wp_localize_script('foundation', 'favouriteData', array(
        'favourite_nonce' => wp_create_nonce('favouriteData'),
        'favourites_ajax_url' => admin_url('admin-ajax.php')
       
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_favourites', 100);


function handle_toggle_favourite() {

    global $prefix;

    if (!wp_verify_nonce($_POST['favourite_nonce'], 'favouriteData')) {
        wp_die('Security check failed');
    }
    
    if (!isset($_POST['post_id']) || !isset($_POST['post_type']) || !isset($_POST['post_title'])) {
        wp_die('Invalid request');
    }
    
    $post_id = intval($_POST['post_id']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $post_title = sanitize_text_field($_POST['post_title']);
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        wp_die('User not logged in');
    }
    if ($post_type == "resource_page") {
        $post_type = "resource";
    }
   
    if ($post_type == "page_link") {
        $post_type = "link";
    }
    $favouriteField = 'favourite_attached_';
    $meta_key = $prefix. $favouriteField . $post_type . 's';
    
    // Check if favourite already exists
    $existing_favourite = get_posts(array(
        'post_type' => 'favourite',
        'author' => $user_id,
        'meta_query' => array(
            array(
                'key' => $meta_key,
                'value' => $post_id,
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => 1
    ));
    
    if ($existing_favourite) {
        // Remove existing favourite
        wp_delete_post($existing_favourite[0]->ID, true);
        get_current_users_favourites($user_id);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
             $_SESSION['current_user_favourite_ids'] = get_current_users_favourites($user_id);

        wp_send_json_success(array('action' => 'removed'));
    } else {
        // Create new favourite
        $favourite_post = array(
            'post_title' => $post_title,
            'post_type' => 'favourite',
            'post_status' => 'publish',
            'post_author' => $user_id
        );
        
        $favourite_id = wp_insert_post($favourite_post);
        
        if ($favourite_id) {
            // Convert post_id to a string in an array format for meta storage
            $post_id_string = strval($post_id);
            $post_id_array = array($post_id_string);
            update_post_meta($favourite_id, $meta_key, $post_id_array);
            if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
             $_SESSION['current_user_favourite_ids'] = get_current_users_favourites($user_id);

            wp_send_json_success(array('action' => 'added'));
            
        } else {
            wp_send_json_error('Failed to create favourite');
        }
    }
}

