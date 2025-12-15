<?php

/**
 * Restrict page access by user role
 */

function user_has_access($post_id): bool
{
    global $prefix;
    $user = wp_get_current_user();
    $post_type = get_post_type($post_id);
    if (! is_user_logged_in()) {
        return false;
    }
    
    // Check if user is in an allowed role
    $allowed_roles = ['main', 'individual', 'employee', 'administrator', 'editor', 'hsw_editor', 'hr_editor', 'finance_editor'];
    if (!array_intersect($allowed_roles, (array) $user->roles)) {
        return false;
    }

    if (in_array('main', (array) $user->roles)) {
        // The user has the "main" role check they have page access
        if (user_has_page_access($user->ID, $post_id, $post_type)) {
            return true;
        }
        return false;
    }

    $allowed_child_roles = ['individual', 'employee', 'hsw_editor', 'hr_editor', 'finance_editor'];
    if (array_intersect($allowed_child_roles, (array) $user->roles)) {

        $created_by = get_user_meta($user->ID, $prefix . 'user_created_by', true);


        if ($created_by == '') {
            // Check if this user has access
            if (user_has_page_access($user->ID, $post_id, $post_type)) {
                return true;
            }
        } else {
            // Check if the parent user has access
            if ($created_by && $created_by != $user->ID && user_has_page_access($created_by, $post_id, $post_type)) {
                return true;
            }
        }


        
        
        return false;
    }

    // administrator or editor
    return true;
}

function get_user_profile_resources($userid): array
{
    global $prefix;

    $attached_profile = get_user_meta($userid,  $prefix . 'user_attached_user_profile', true);
    //return $attached_profile;
    $all_resources = [];
    if ($attached_profile && is_array($attached_profile)) {
        foreach ($attached_profile as $profile_id) {
            $attached_resources = get_post_meta($profile_id, $prefix . 'profile_attached_resource_pages', true);
            if (is_array($attached_resources)) {
                $all_resources = array_merge($all_resources, $attached_resources);
            }
        }
        return array_unique($all_resources);
    }
    return [];
}


// Check user has correct page permissions
function user_has_page_access($userid, $page_id, $post_type): bool
{
    global $prefix;
    $profile_resources = get_user_profile_resources($userid);
    if (!empty($profile_resources)) {
        $attached_pages = $profile_resources;
    } else {
        $attached_pages = get_user_meta($userid, $prefix . 'user_attached_resource_pages', true);
    }
    // resource pages
    if ($post_type == 'resource_page' && (empty($attached_pages) || !in_array($page_id, $attached_pages))) {
        // Get parent pages recursively

        $parent_pages = [];
        $current_page = $page_id;
        $parent_pages[] = $current_page;
        while ($current_page) {
            $parent_id = wp_get_post_parent_id($current_page);
            if ($parent_id) {
                $parent_pages[] = $parent_id;
                $current_page = $parent_id;
            } else {
                break;
            }
        }


        // Check if user has access to any parent pages
        foreach ($parent_pages as $parent_id) {

            if (!empty($attached_pages) && in_array((string)$parent_id, $attached_pages)) {
                return true;
            }
        }
        return false;
    }

    if ($post_type == 'attachment') {
        //error_log('Relevant attachment: ' . print_r($page_id, true));
        // document files - find documents that contain this attachment
        $attached_documents = get_posts([
            'post_type' => 'document',
            'post_status' => 'publish',
            'meta_query' => [

                [
                    'key' => $prefix . 'document_is_active',
                    'value' => 'on',
                    'compare' => '='
                ]
            ],
            'numberposts' => -1,
            'fields' => 'ids'
        ]);

        // Filter documents that contain this attachment
        $relevant_documents = [];
        foreach ($attached_documents as $document_id) {
            $document_files = get_post_meta($document_id, $prefix . 'document_files', true);
            //error_log('doc files: ' . print_r($document_files[0], true));
            if (!empty($document_files) && in_array($page_id, $document_files[0])) {
                $relevant_documents[] = $document_id;
                error_log('Relevant document: ' . print_r($document_id, true));
            }
        }
        $attached_documents = $relevant_documents;

        // Get the pages
        $attached_document_pages = get_posts([
            'post_type' => 'resource_page',
            'post_status' => 'publish',
            'numberposts' => -1,
            'fields' => 'ids'
        ]);

        $relevant_pages = [];
        foreach ($attached_document_pages as $attached_page_id) {
            $attached_docs = get_post_meta($attached_page_id, $prefix . 'resource_attached_documents', true);

            if (!empty($attached_docs) && in_array($attached_docs[0], $attached_documents)) {
                $relevant_pages[] = $attached_page_id;
                error_log('Relevant page: ' . print_r($attached_page_id, true));
            }
        }

        // Check if user has access to any of the pages this document is attached to
        foreach ($relevant_pages as $document_page_id) {
            if (user_has_page_access($userid, $document_page_id, 'resource_page')) {
                return true;
            }
        }

        return false;
    }

    return true;
}

function user_has_module_access($post_id) : bool
{
    global $prefix;
    $user = wp_get_current_user();
    //$post_type = get_post_type($post_id);
    if (! is_user_logged_in()) {
        return false;
    }
    // Check if user is in an allowed role
    $allowed_roles = ['main', 'individual', 'employee', 'administrator', 'editor'];
    if (!array_intersect($allowed_roles, (array) $user->roles)) {
        return false;
    }

    $profile_resources = get_user_profile_resources($user->ID);
    if (!empty($profile_resources)) {
        $user_attached_pages = $profile_resources;
    } else {
        $user_attached_pages = get_user_meta($user->ID, $prefix . 'user_attached_resource_pages', true);
    }

    //return $user_attached_pages;

    if (in_array('main', (array) $user->roles)) {
        // The user has the "main" role check they have page access
        // Check if post_id is a parent of any pages in user_attached_pages
        if (check_module_is_parent_of_attached_page($user_attached_pages, $post_id) || in_array($post_id, $user_attached_pages)) {
            return true;
        }
        return false;
    }

    $allowed_child_roles = ['individual', 'employee'];
    if (array_intersect($allowed_child_roles, (array) $user->roles)) {

        $created_by = get_user_meta($user->ID, $prefix . 'user_created_by', true);

        if ($created_by == '') {
            //return $user_attached_pages;
            // Check if this user has access
            if (check_module_is_parent_of_attached_page($user_attached_pages, $post_id) || in_array($post_id, $user_attached_pages)) {
                
                return true;
            }
            //return $user_attached_pages;
        } else {
            
            // Check if the parent user has access
            $parent_profile_resources = get_user_profile_resources($created_by);
            if (!empty($parent_profile_resources)) {
            
                $parent_user_attached_pages = $parent_profile_resources;
                //return $parent_user_attached_pages;
            } else {    
                $parent_user_attached_pages = get_user_meta($created_by, $prefix . 'user_attached_resource_pages', true);
            }

            if (check_module_is_parent_of_attached_page($parent_user_attached_pages, $post_id) || in_array($post_id, $user_attached_pages)) {
                return true;
            }  
        }




        return false;
    }

    // administrator or editor
    return true;
}

// check module is parent of attached page
function check_module_is_parent_of_attached_page($user_attached_pages, $post_id): bool
{
    if (!empty($user_attached_pages)) {
        foreach ($user_attached_pages as $attached_page_id) {
            $current_page = $attached_page_id;
            while ($current_page) {
                $parent_id = wp_get_post_parent_id($current_page);
                if ($parent_id == $post_id) {
                    return true;
                }
                $current_page = $parent_id;
            }
        }
    }
    return false;
}

function return_users_pages_with_access(): array
{
    global $prefix;
    $user = wp_get_current_user();
    $created_by = get_user_meta($user->ID, $prefix . 'user_created_by', true);
    
    if ($created_by != '') {
        $user = get_user_by('id', $created_by);
    } else {
        $user = wp_get_current_user();
    }
    $profile_resources = get_user_profile_resources($user->ID);

    if (!empty($profile_resources)) {
        $user_attached_pages = $profile_resources;
    } else {
        $user_attached_pages = get_user_meta($user->ID, $prefix . 'user_attached_resource_pages', true);
    }

    // Ensure $user_attached_pages is an array
    if (!is_array($user_attached_pages)) {
        $user_attached_pages = [];
    }

    $all_pages = $user_attached_pages;

    // Loop through attached pages and get all their children
    foreach ($all_pages as $page_id) {
        $children = get_posts([
            'post_type' => 'resource_page',
            'post_parent' => $page_id,
            'post_status' => 'publish',
            'numberposts' => -1,
            'fields' => 'ids'
        ]);

        // Recursively get all descendant pages
        $all_descendants = [];
        $pages_to_check = $children;

        while (!empty($pages_to_check)) {
            $current_page = array_shift($pages_to_check);
            $all_descendants[] = $current_page;

            $grandchildren = get_posts([
                'post_type' => 'resource_page',
                'post_parent' => $current_page,
                'post_status' => 'publish',
                'numberposts' => -1,
                'fields' => 'ids'
            ]);

            $pages_to_check = array_merge($pages_to_check, $grandchildren);
        }

        $all_pages = array_merge($all_pages, $all_descendants);
    }

    $user_attached_pages = array_unique($all_pages);



    
    return $user_attached_pages;
}

// Restrict file access

// 1. Add rewrite rule for the endpoint
add_action('init', function () {
    add_rewrite_rule('^download-document/([0-9]+)/?', 'index.php?download_document_id=$matches[1]', 'top');
    add_rewrite_tag('%download_document_id%', '([0-9]+)');
});

// 2. Handle the endpoint and serve the file e.g. https://hr-infospace:8890/download-document/1574/
add_action('template_redirect', function () {
    global $prefix;
    $doc_id = get_query_var('download_document_id');
    if ($doc_id) {
        // Check user role/capability
        //if (!current_user_can('main')) {
        //wp_die('You do not have permission to download this document.');
        //}

        if (!user_has_access($doc_id)) {
            wp_die('You do not have permission to download this document');
        };

        // Get file URL or path (adjust as needed)
        //$file_url = get_post_meta($doc_id, $prefix . 'uploaded_file', true);
        // Get the attachment post
        $attachment = get_post($doc_id);


        if (!$attachment || $attachment->post_type !== 'attachment') {
            wp_die('Invalid document ID: ' . $doc_id);
        }

        // Get the file path from the attachment
        $file_path = get_attached_file($doc_id);
        error_log('File path: ' . print_r($file_path, true));
        $filename = basename($file_path);

        if ($file_path && file_exists($file_path)) {
            // Serve the file (force download)
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile($file_path);
            exit;
        } else {
            wp_die('File not found: ' . $doc_id);
        }
    }
});



// Switch off Json for not logged in users
add_filter('rest_authentication_errors', function ($result) {
    // If a previous authentication check was applied,
    // pass that result along without modification.
    if (true === $result || is_wp_error($result)) {
        return $result;
    }

    // No authentication has been performed yet.
    // Return an error if user is not logged in.
    if (! is_user_logged_in()) {
        return new WP_Error(
            'rest_not_logged_in',
            __('You are not currently logged in.'),
            array('status' => 401)
        );
    }

    // Our custom authentication check should have no effect
    // on logged-in requests
    return $result;
});
