<?php

/**
 * Add custom user roles
 */

/**
 * Add custom capability for users to only edit users they created
 */
function add_edit_own_created_users_capability()
{
    // Add the custom capability to roles that should have it
    $role = get_role('main');
    if ($role) {
        $role->add_cap('edit_own_created_users');
        $role->add_cap('delete_own_created_users');
    }
}

/**
 * Filter user query to only show users created by current user
 */
function filter_users_by_creator($query)
{
    global $prefix;
    $current_user = wp_get_current_user();
    if (is_admin() && in_array('main', $current_user->roles) && current_user_can('edit_users')) {
        global $pagenow;
        if ($pagenow == 'users.php') {
            $query->query_vars['meta_key'] = $prefix . 'user_created_by';
            $query->query_vars['meta_value'] = get_current_user_id();
        }
    }
}

/**
 * Store creator ID when a new user is created
 */
function store_user_creator($user_id)
{
    global $prefix;
    if (is_admin() && in_array('main', wp_get_current_user()->roles)) {

        // Update CMB2 field if it exists
        if (function_exists('cmb2_get_metabox')) {
            $current_user = get_current_user_id();
            $meta_key = $prefix . 'user_created_by';
            update_user_meta($user_id, $meta_key, $current_user);
        }
    }
}

/**
 * Check if current user can edit/delete specific user
 */
function can_edit_created_user($user_id)
{
    global $prefix;
    if (current_user_can('edit_users')) {
        return true; // Admin can edit all users
    }

    if (current_user_can('edit_own_created_users')) {
        $created_by = get_user_meta($user_id, $prefix . 'user_created_by', true);
        return $created_by == get_current_user_id();
    }

    return false;
}

// Hook the functions
add_action('init', 'add_edit_own_created_users_capability');
add_action('pre_get_users', 'filter_users_by_creator');
add_action('user_register', 'store_user_creator', 20);


function add_custom_roles()
{
    remove_role('employee'); // Remove this
    // Check if roles don't already exist before adding them
    if (!get_role('employee')) {
        add_role(
            'employee',
            'Employee',
            array(
                'read' => false,

            )
        );
    }

    if (!get_role('individual')) {
        add_role(
            'individual',
            'Individual',
            array(
                'read' => true,
            )
        );
    }
    remove_role('main'); // Remove this
    if (!get_role('main')) {

        add_role(
            'main',
            'Main',
            array(
                'read' => true,
                'list_users' => true,
                'delete_users' => true,
                'create_users' => true,
                'edit_users' => true,
                'promote_users' => true,
                //'edit_users' => true,
            )
        );
    }
}

/**
 * Hide 'WordPress News and Events' and 'Activity' widgets for users with the role 'main'
 */
function hide_wordpress_news_for_main_role()
{
    $user = wp_get_current_user();

    if (in_array('main', $user->roles)) {
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    }
}
add_action('wp_dashboard_setup', 'hide_wordpress_news_for_main_role');

// Hook into WordPress initialization
add_action('init', 'add_custom_roles');

/**
 * Remove custom roles on theme deactivation (optional)
 */
function remove_custom_roles()
{
    remove_role('employee');
    remove_role('individual');
    remove_role('main');
}

// Uncomment the line below if you want to remove the roles when theme is deactivated
register_deactivation_hook(__FILE__, 'remove_custom_roles');


/**
 * restrict the 'main' role to only be able to promote a user to 'individual' or 'employee'
 */
function wpse_293133_filter_editable_roles($all_roles)
{
    if (in_array('main', wp_get_current_user()->roles)) {
        $all_roles = array(
            //'employee' => $all_roles['employee'],
            'individual' => $all_roles['individual']
        );
    }

    return $all_roles;
}

add_filter('editable_roles', 'wpse_293133_filter_editable_roles', 21);


/**
 * When adding a new user and the user is 'main', only allow the user to add a new user if there are less than 6 users of that role created by them
 */
function restrict_user_creation_before_add($errors, $sanitized_user_data, $user_data)
{
    global $prefix;

    if (in_array('main', wp_get_current_user()->roles)) {
        // Check the role being assigned to the new user
        $role = isset($_POST['role']) ? $_POST['role'] : 'subscriber';

        if ($role === 'individual') {
            // Get users created by the current user with 'individual' role
            $args = array(
                'meta_key' => $prefix . 'user_created_by',
                'meta_value' => get_current_user_id(),
                'role__in' => array('individual')
            );
            $created_users = get_users($args);
            $user_count = count($created_users);

            if ($user_count >= 6) {
                $errors->add('too_many_individual_users', __('You cannot create more than 6 individual users. Please remove an existing user to add a new one.', 'hrinfospace'));
            }
        } /*elseif ($role === 'employee') {
            // Get users created by the current user with 'employee' role
            $args = array(
                'meta_key' => $prefix . 'user_created_by',
                'meta_value' => get_current_user_id(),
                'role__in' => array('employee')
            );
            $created_users = get_users($args);
            $user_count = count($created_users);
            
            if ($user_count >= 1) {
                $errors->add('too_many_employee_users', __('You cannot create more than 1 employee user', 'hrinfospace'));
            }
        }*/
    }

    return $errors;
}

add_filter('user_profile_update_errors', 'restrict_user_creation_before_add', 10, 3);


// Create finance editor role with editor capabilities but restricted to resource_page post type
function create_finance_editor_role()
{

    remove_role('editor');
    // Remove Yoast SEO roles
    remove_role('wpseo_manager');
    remove_role('wpseo_editor');

    // Remove the role if it already exists to avoid conflicts
    remove_role('finance_editor');

    // Get editor role capabilities
    $editor_caps = array(
        'read' => true,
        'delete_others_pages' => true,
        'delete_others_posts' => true,
        'delete_pages' => true,
        'delete_posts' => true,
        'delete_private_pages' => true,
        'delete_private_posts' => true,
        'delete_published_pages' => true,
        'delete_published_posts' => true,
        'edit_others_pages' => true,
        'edit_others_posts' => true,
        'edit_pages' => true,
        'edit_posts' => true,
        'edit_private_pages' => true,
        'edit_private_posts' => true,
        'edit_published_pages' => true,
        'edit_published_posts' => true,
        'manage_categories' => true,
        'manage_links' => true,
        'moderate_comments' => true,
        'publish_pages' => true,
        'publish_posts' => true,
        'read_private_pages' => true,
        'read_private_posts' => true,
        'upload_files' => true,
    );
    $editor_caps = $editor_caps;

    // Add custom capability for accessing reports
    $editor_caps['access_module_admin_page'] = true;

    // Add the finance editor role with editor capabilities
    add_role('finance_editor', 'Finance Editor', $editor_caps);
}
add_action('init', 'create_finance_editor_role');

// Restrict finance editor access to only resource_page post type and specific pages
function restrict_finance_editor_access($query)
{
    if (is_admin() && !defined('DOING_AJAX') && $query->is_main_query()) {
        $user = wp_get_current_user();

        if (in_array('finance_editor', $user->roles)) {

            global $pagenow;
            global $finance_page;
            global $prefix;

            // Restrict to edit.php (post list) for resource_page only
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && !in_array($_GET['post_type'], ['resource_page', 'document', 'page_link', 'newsletter',  'post', 'page'])) {
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }

            // Restrict resource_page access to page ID $finance_page and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'resource_page') {
                $allowed_ids = array($finance_page);
                $this_page = $finance_page;

                while ($this_page) {
                    $children = get_posts(array(
                        'post_type' => 'resource_page',
                        'post_parent' => $this_page,
                        'numberposts' => -1,
                        'fields' => 'ids'
                    ));

                    if (empty($children)) {
                        break;
                    }

                    $allowed_ids = array_merge($allowed_ids, $children);
                    $this_page = reset($children);
                }
                // Also allow posts where current user is the author
                $user_posts = get_posts(array(
                    'post_type' => 'resource_page',
                    'author' => get_current_user_id(),
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_ids = array_merge($allowed_ids, $user_posts);

                $query->set('post__in', $allowed_ids);
            }

            // Restrict document access to documents attached to page ID $finance_page and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'document') {
                $allowed_docs = array();
                $this_page = $finance_page;

                while ($this_page) {
                    $children = get_posts(array(
                        'post_type' => 'resource_page',
                        'post_parent' => $this_page,
                        'numberposts' => -1,
                        'fields' => 'ids'
                    ));

                    if (empty($children)) {
                        break;
                    }

                    // Get attached documents from custom field
                    foreach ($children as $child_id) {
                        $docs = get_post_meta($child_id, $prefix . 'resource_attached_documents', false);
                        echo $child_id;
                        if (is_array($docs) && !empty($docs)) {
                            $docs = array_values($docs[0] ?? []);
                        }

                        $allowed_docs = array_merge($allowed_docs, $docs);
                    }

                    $this_page = reset($children);
                }

                $allowed_docs = array_unique($allowed_docs);


                // Also allow docs where current user is the author
                $user_posts = get_posts(array(
                    'post_type' => 'document',
                    'author' => get_current_user_id(),
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_docs = array_merge($allowed_docs, $user_posts);

                $query->set('post__in', $allowed_docs);
            }
        }
    }
}
add_action('pre_get_posts', 'restrict_finance_editor_access');

// Hide menu items for finance editor
function hide_admin_menus_for_finance_editor()
{
    $user = wp_get_current_user();

    if (in_array('finance_editor', $user->roles)) {
        // Remove all menu items except the ones they should access
        remove_menu_page('index.php'); // Dashboard
        //remove_menu_page('edit.php'); // Posts
        //remove_menu_page('upload.php'); // Media (keep if needed)
        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('themes.php'); // Appearance
        remove_menu_page('plugins.php'); // Plugins
        remove_menu_page('users.php'); // Users
        remove_menu_page('tools.php'); // Tools
        remove_menu_page('options-general.php'); // Settings
        remove_menu_page('edit.php?post_type=module'); // Module
        // remove_menu_page('edit.php?post_type=newsletter'); // Newsletter
        remove_menu_page('edit.php?post_type=enquiry'); // Enquiry
        //remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('edit.php?post_type=survey'); // Surveys
        remove_menu_page('edit.php?post_type=favourite'); // Favourite
        remove_menu_page('admin.php?page=theme_options'); // Theme Options
        remove_menu_page('admin.php?page=wpseo_workouts'); // SEO Workouts

        // Keep only resource_page and specific pages access
        // The pages menu will be filtered by the query restriction above
    }
}
add_action('admin_menu', 'hide_admin_menus_for_finance_editor');

// Restrict post/page editing access
function restrict_finance_editor_post_access()
{
    $user = wp_get_current_user();

    if (in_array('finance_editor', $user->roles)) {
        global $pagenow;
        global $finance_page;
        if (($pagenow == 'post.php' || $pagenow == 'post-new.php') && isset($_GET['resource_page'])) {
            $post_id = intval($_GET['post']);
            $post = get_post($post_id);

            if ($post) {
                // Allow resource_page post type
                if ($post->post_type == 'newsletter') {
                    return;
                }

                // Allow page ID 4611 and its children
                if ($post->post_type == 'resource_page') {
                    if ($post_id == $finance_page || $post->post_parent == $finance_page || is_child_of_page($post_id, $finance_page)) {
                        return;
                    }
                }

                // Redirect if not allowed
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'restrict_finance_editor_post_access');




// Create HR editor role with editor capabilities but restricted to resource_page post type
function create_hr_editor_role()
{
    // Remove the role if it already exists to avoid conflicts
    remove_role('hr_editor');
    $editor_caps = array(
        'read' => true,
        'delete_others_pages' => true,
        'delete_others_posts' => true,
        'delete_pages' => true,
        'delete_posts' => true,
        'delete_private_pages' => true,
        'delete_private_posts' => true,
        'delete_published_pages' => true,
        'delete_published_posts' => true,
        'edit_others_pages' => true,
        'edit_others_posts' => true,
        'edit_pages' => true,
        'edit_posts' => true,
        'edit_private_pages' => true,
        'edit_private_posts' => true,
        'edit_published_pages' => true,
        'edit_published_posts' => true,
        'manage_categories' => true,
        'manage_links' => true,
        'moderate_comments' => true,
        'publish_pages' => true,
        'publish_posts' => true,
        'read_private_pages' => true,
        'read_private_posts' => true,
        'upload_files' => true,
    );
    // Get editor role capabilities

    $editor_caps = $editor_caps;

    // Add custom capability for accessing reports
    $editor_caps['access_module_admin_page'] = true;

    // Add the HR editor role with editor capabilities
    add_role('hr_editor', 'HR Editor', $editor_caps);
}
add_action('init', 'create_hr_editor_role');

// Restrict HR editor access to only resource_page post type and specific pages
function restrict_hr_editor_access($query)
{
    if (is_admin() && !defined('DOING_AJAX') && $query->is_main_query()) {
        $user = wp_get_current_user();

        if (in_array('hr_editor', $user->roles)) {
            global $pagenow;
            global $hr_page;
            global $prefix;

            // Restrict to edit.php (post list) for resource_page only
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && !in_array($_GET['post_type'], ['resource_page', 'document', 'page_link', 'newsletter',  'post', 'page'])) {
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }

            // Restrict resource_page access to page ID $hr_page and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'resource_page') {
                $allowed_ids = array($hr_page);
                $this_page = $hr_page;

                while ($this_page) {
                    $children = get_posts(array(
                        'post_type' => 'resource_page',
                        'post_parent' => $this_page,
                        'numberposts' => -1,
                        'fields' => 'ids'
                    ));

                    if (empty($children)) {
                        break;
                    }

                    $allowed_ids = array_merge($allowed_ids, $children);
                    $this_page = reset($children);
                }

                // Also allow posts where current user is the author
                $user_posts = get_posts(array(
                    'post_type' => 'resource_page',
                    'author' => get_current_user_id(),
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_ids = array_merge($allowed_ids, $user_posts);
                $query->set('post__in', $allowed_ids);
            }

            // Restrict document access to documents attached to page ID $hr_page and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'document') {
                $allowed_docs = array();
                $this_page = $hr_page;

                while ($this_page) {
                    $children = get_posts(array(
                        'post_type' => 'resource_page',
                        'post_parent' => $this_page,
                        'numberposts' => -1,
                        'fields' => 'ids'
                    ));

                    if (empty($children)) {
                        break;
                    }

                    // Get attached documents from custom field
                    foreach ($children as $child_id) {
                        $docs = get_post_meta($child_id, $prefix . 'resource_attached_documents', false);
                        echo $child_id;
                        if (is_array($docs) && !empty($docs)) {
                            $docs = array_values($docs[0] ?? []);
                        }

                        $allowed_docs = array_merge($allowed_docs, $docs);
                    }

                    $this_page = reset($children);
                }

                $allowed_docs = array_unique($allowed_docs);


                // Also allow docs where current user is the author
                $user_posts = get_posts(array(
                    'post_type' => 'document',
                    'author' => get_current_user_id(),
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_docs = array_merge($allowed_docs, $user_posts);

                $query->set('post__in', $allowed_docs);
            }
        }
    }
}
add_action('pre_get_posts', 'restrict_hr_editor_access');

// Hide menu items for HR editor
function hide_admin_menus_for_hr_editor()
{
    $user = wp_get_current_user();

    if (in_array('hr_editor', $user->roles)) {
        // Remove all menu items except the ones they should access
        remove_menu_page('index.php'); // Dashboard
        //remove_menu_page('edit.php'); // Posts
        //remove_menu_page('upload.php'); // Media (keep if needed)
        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('themes.php'); // Appearance
        remove_menu_page('plugins.php'); // Plugins
        remove_menu_page('users.php'); // Users
        remove_menu_page('tools.php'); // Tools
        remove_menu_page('options-general.php'); // Settings
        remove_menu_page('edit.php?post_type=module'); // Module
        //remove_menu_page('edit.php?post_type=newsletter'); // Newsletter
        remove_menu_page('edit.php?post_type=enquiry'); // Enquiry
        // remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('edit.php?post_type=survey'); // Surveys
        remove_menu_page('edit.php?post_type=favourite'); // Favourite
        remove_menu_page('admin.php?page=theme_options'); // Theme Options
        remove_menu_page('admin.php?page=wpseo_workouts'); // SEO Workouts

        // Keep only resource_page and specific pages access
        // The pages menu will be filtered by the query restriction above
    }
}
add_action('admin_menu', 'hide_admin_menus_for_hr_editor');

// Restrict post/page editing access
function restrict_hr_editor_post_access()
{
    $user = wp_get_current_user();

    if (in_array('hr_editor', $user->roles)) {
        global $pagenow;
        global $hr_page;
        if (($pagenow == 'post.php' || $pagenow == 'post-new.php') && isset($_GET['resource_page'])) {
            $post_id = intval($_GET['post']);
            $post = get_post($post_id);

            if ($post) {
                // Allow resource_page post type
                if ($post->post_type == 'newsletter') {
                    return;
                }

                // Allow page ID $hr_page and its children
                if ($post->post_type == 'resource_page') {
                    if ($post_id == $hr_page || $post->post_parent == $hr_page || is_child_of_page($post_id, $hr_page)) {
                        return;
                    }
                }

                // Redirect if not allowed
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'restrict_hr_editor_post_access');



// Create HSW editor role with editor capabilities but restricted to resource_page post type
function create_hsw_editor_role()
{
    // Remove the role if it already exists to avoid conflicts
    remove_role('hsw_editor');
    $editor_caps = array(
        'read' => true,
        'delete_others_pages' => true,
        'delete_others_posts' => true,
        'delete_pages' => true,
        'delete_posts' => true,
        'delete_private_pages' => true,
        'delete_private_posts' => true,
        'delete_published_pages' => true,
        'delete_published_posts' => true,
        'edit_others_pages' => true,
        'edit_others_posts' => true,
        'edit_pages' => true,
        'edit_posts' => true,
        'edit_private_pages' => true,
        'edit_private_posts' => true,
        'edit_published_pages' => true,
        'edit_published_posts' => true,
        'manage_categories' => true,
        'manage_links' => true,
        'moderate_comments' => true,
        'publish_pages' => true,
        'publish_posts' => true,
        'read_private_pages' => true,
        'read_private_posts' => true,
        'upload_files' => true,
    );
    // Get editor role capabilities
    //$editor = get_role('editor');
    $editor_caps = $editor_caps;

    // Add custom capability for accessing reports
    $editor_caps['access_module_admin_page'] = true;

    // Add the HSW editor role with editor capabilities
    add_role('hsw_editor', 'HSW Editor', $editor_caps);
}
add_action('init', 'create_hsw_editor_role');

// Restrict HSW editor access to only resource_page post type and specific pages
function restrict_hsw_editor_access($query)
{
    if (is_admin() && !defined('DOING_AJAX') && $query->is_main_query()) {
        $user = wp_get_current_user();

        if (in_array('hsw_editor', $user->roles)) {
            global $pagenow;
            global $hsafety_page;
            global $prefix;
            
            // Restrict to edit.php (post list) for resource_page only
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && !in_array($_GET['post_type'], ['resource_page', 'document', 'page_link',  'newsletter', 'post', 'page'])) {
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }

            // Restrict resource_page access to page ID $hsafety_page and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'resource_page') {
                $allowed_ids = array($hsafety_page);
                $this_page = $hsafety_page;

                while ($this_page) {
                    $children = get_posts(array(
                        'post_type' => 'resource_page',
                        'post_parent' => $this_page,
                        'numberposts' => -1,
                        'fields' => 'ids'
                    ));

                    if (empty($children)) {
                        break;
                    }

                    $allowed_ids = array_merge($allowed_ids, $children);
                    $this_page = reset($children);
                }

                // Also allow posts where current user is the author
                $user_posts = get_posts(array(
                    'post_type' => 'resource_page',
                    'author' => get_current_user_id(),
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_ids = array_merge($allowed_ids, $user_posts);

                $query->set('post__in', $allowed_ids);
            }

            // Restrict document access to documents attached to page ID $hsafety_page and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'document') {
                $allowed_docs = array();
                $this_page = $hsafety_page;

                while ($this_page) {
                    $children = get_posts(array(
                        'post_type' => 'resource_page',
                        'post_parent' => $this_page,
                        'numberposts' => -1,
                        'fields' => 'ids'
                    ));

                    if (empty($children)) {
                        break;
                    }

                    // Get attached documents from custom field
                    foreach ($children as $child_id) {
                        $docs = get_post_meta($child_id, $prefix . 'resource_attached_documents', false);
                        echo $child_id;
                        if (is_array($docs) && !empty($docs)) {
                            $docs = array_values($docs[0] ?? []);
                        }

                        $allowed_docs = array_merge($allowed_docs, $docs);
                    }

                    $this_page = reset($children);
                }

                $allowed_docs = array_unique($allowed_docs);


                // Also allow docs where current user is the author
                $user_posts = get_posts(array(
                    'post_type' => 'document',
                    'author' => get_current_user_id(),
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_docs = array_merge($allowed_docs, $user_posts);

                $query->set('post__in', $allowed_docs);
            }
        }
    }
}
add_action('pre_get_posts', 'restrict_hsw_editor_access');

// Hide menu items for HSW editor
function hide_admin_menus_for_hsw_editor()
{
    $user = wp_get_current_user();

    if (in_array('hsw_editor', $user->roles)) {
        // Remove all menu items except the ones they should access
        remove_menu_page('index.php'); // Dashboard
        //remove_menu_page('edit.php'); // Posts
        //remove_menu_page('upload.php'); // Media (keep if needed)
        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('themes.php'); // Appearance
        remove_menu_page('plugins.php'); // Plugins
        remove_menu_page('users.php'); // Users
        remove_menu_page('tools.php'); // Tools
        remove_menu_page('options-general.php'); // Settings
        remove_menu_page('edit.php?post_type=module'); // Module
        // remove_menu_page('edit.php?post_type=newsletter'); // Newsletter
        remove_menu_page('edit.php?post_type=enquiry'); // Enquiry
        // remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('edit.php?post_type=survey'); // Surveys
        remove_menu_page('edit.php?post_type=favourite'); // Favourite
        remove_menu_page('admin.php?page=theme_options'); // Theme Options
        remove_menu_page('admin.php?page=wpseo_workouts'); // SEO Workouts

        // Keep only resource_page and specific pages access
        // The pages menu will be filtered by the query restriction above
    }
}
add_action('admin_menu', 'hide_admin_menus_for_hsw_editor');

// Restrict post/page editing access
function restrict_hsw_editor_post_access()
{
    $user = wp_get_current_user();

    if (in_array('hsw_editor', $user->roles)) {
        global $pagenow;
        global $hsafety_page;
        if (($pagenow == 'post.php' || $pagenow == 'post-new.php') && isset($_GET['resource_page'])) {
            $post_id = intval($_GET['post']);
            $post = get_post($post_id);

            if ($post) {
                // Allow resource_page post type
                if ($post->post_type == 'newsletter') {
                    return;
                }

                // Allow page ID $hsafety_page and its children
                if ($post->post_type == 'resource_page') {
                    if ($post_id == $hsafety_page || $post->post_parent == $hsafety_page || is_child_of_page($post_id, $hsafety_page)) {
                        return;
                    }
                }

                // Redirect if not allowed
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'restrict_hsw_editor_post_access');

// Grant report access capability to administrator role
function grant_admin_report_access()
{
    $admin_role = get_role('administrator');
    if ($admin_role) {
        $admin_role->add_cap('access_module_admin_page');
    }
}
add_action('init', 'grant_admin_report_access');
