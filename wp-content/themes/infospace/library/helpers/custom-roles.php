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
            'employee' => $all_roles['employee'],
            'individual' => $all_roles['individual']
        );
    }

    return $all_roles;
}

add_filter('editable_roles', 'wpse_293133_filter_editable_roles', 21);


/**
 * When adding a new user and the user is 'main', only allow the user to add a new user if there are less than 2 users of that role created by them
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
        } elseif ($role === 'employee') {
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
        }
    }
    
    return $errors;
}

add_filter('user_profile_update_errors', 'restrict_user_creation_before_add', 10, 3);
