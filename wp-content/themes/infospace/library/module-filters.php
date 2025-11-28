<?php

/**
 * Check what is in each module
 */

function get_current_module_slug(): string
{
    $current_url = $_SERVER['REQUEST_URI'];
    $url_parts = explode('/resource/', $current_url);
    if (count($url_parts) > 1) {
        $after_resource = explode('/', trim($url_parts[1], '/'));
        $second_part = isset($after_resource[0]) ? $after_resource[0] : '';
    } else {
        $second_part = '';
    }
    return $second_part;
}

function get_current_module_meta($current_module): array
{
    global $prefix;
    if (!empty($current_module)) {
        $module_id = $current_module;
    } else {
        $moduleSlug = (!empty($current_module)) ? $current_module : get_current_module_slug();

        $module = get_page_by_path($moduleSlug, OBJECT, 'module');
        $module_id = $module ? $module->ID : 0;
    }

    $attached_resources = get_post_meta($module_id, $prefix . 'module_attached_resources', true);
    $module_description = get_post_meta($module_id, $prefix . 'module_description', true);
    $module_color = get_post_meta($module_id, $prefix . 'module_color', true);
    $module_banner = get_post_meta($module_id, $prefix . 'banner_image_id', true);

    if ($module_id) {
        //set a global variable
        // Declare as global before use to avoid undefined variable warnings
        global $_SESSION;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['current_module_id'] = $module_id;
    }

    return [
        'attached_resources' => $attached_resources,
        'module_description' => $module_description,
        'module_color' => $module_color,
        'module_id' => $module_id,
        'module_banner' =>  $module_banner
    ];
}

function check_if_is_module_landing($page_id, $attached_resource_id): bool
{
    return $page_id == $attached_resource_id;
}

function get_module_child_pages($parent_id, $visited = array()): array
{
    if (!$parent_id) {
        $currentModuleMeta = get_current_module_meta(null);
        $landingPageId = $currentModuleMeta['attached_resources'] ?? 0;
    } else {
        $landingPageId = $parent_id;
    }

    // Prevent infinite recursion
    if (in_array($landingPageId, $visited)) {
        return [];
    }
    $visited[] = $landingPageId;

    $child_pages = get_posts(array(
        'post_type'   => 'resource_page',
        'post_parent' => $landingPageId,
        'numberposts' => -1,
        'fields'      => 'ids'
    ));

    $all_child_pages = $child_pages;

    foreach ($child_pages as $child) {
        $child_id = is_object($child) ? $child->ID : $child;
        $grandchildren = get_module_child_pages($child_id, $visited);
        if (is_array($grandchildren)) {
            $all_child_pages = array_merge($all_child_pages, $grandchildren);
        } elseif (is_int($grandchildren)) {
            $all_child_pages[] = $grandchildren;
        }
    }
    if ($parent_id && !in_array($parent_id, $all_child_pages)) {
        array_unshift($all_child_pages, $landingPageId);
    }
    return $all_child_pages;
}

function get_module_child_pages_using_module_id($module_id): array
{
    if ($module_id) {
        $currentModuleMeta = get_current_module_meta($module_id);
        $parentId = $currentModuleMeta['attached_resources'] ?? 0;
        $all_child_pages = get_module_child_pages($parentId);
        //return $currentModuleMeta;
        return $all_child_pages;
    } else {
        return [];
    }
}

function get_current_users_favourites($userId): array
{
    global $prefix;


    if (!empty($userId)) {
        $favourites = get_posts(array(
            'post_type' => 'favourite',
            'author' => $userId,
            'numberposts' => -1,
            'post_status' => 'publish'
        ));

        $all_favourite_ids = [];

        foreach ($favourites as $favourite) {
            $attached_documents = get_post_meta($favourite->ID, $prefix . 'favourite_attached_documents', true);
            $attached_links = get_post_meta($favourite->ID, $prefix . 'favourite_attached_links', true);
            $attached_resources = get_post_meta($favourite->ID, $prefix . 'favourite_attached_resources', true);

            if (is_array($attached_documents)) {
                $all_favourite_ids = array_merge($all_favourite_ids, $attached_documents);
            }
            if (is_array($attached_links)) {
                $all_favourite_ids = array_merge($all_favourite_ids, $attached_links);
            }
            if (is_array($attached_resources)) {
                $all_favourite_ids = array_merge($all_favourite_ids, $attached_resources);
            }
        }
        $all_favourite_unique_ids = array_unique($all_favourite_ids);
    
        global $_SESSION;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
       
        $_SESSION['current_user_favourite_ids'] = $all_favourite_unique_ids;

        return $all_favourite_unique_ids;
    } else {
        return [];
    }
}

// Hook into user login to populate favourites
function populate_user_favourites_on_login($user_login, $user) {
    if (isset($user->ID)) {
        get_current_users_favourites($user->ID);
    }
}
add_action('wp_login', 'populate_user_favourites_on_login', 10, 2);
