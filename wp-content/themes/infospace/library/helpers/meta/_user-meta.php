<?php

// Users
add_filter('cmb2_admin_init', 'cmb2_user_metabox');
function cmb2_user_metabox()
{
    global $prefix;

    $user = new_cmb2_box([
        'id'            => $prefix . 'user_page_details',
        'title'         => 'User details',
        'object_types'  => ['user'],
        //'context'       => 'normal',
        //'priority'      => 'high',
        'show_names'    => true,
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_organisation',
        'name'      => 'School/Academy',
        //'desc'      => 'Short summary of the document',
        'type'      => 'text',
        'show_on_cb' => function () {
            return current_user_can('administrator') || current_user_can('main');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_federation_trust',
        'name'      => 'Federation/Trust',
        //'desc'      => 'Short summary of the document',
        'type'      => 'text',
        'show_on_cb' => function () {
            return current_user_can('administrator') || current_user_can('main');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_dfe_number',
        'name'      => 'DFE Number',
        'desc'      => 'Department for Education number',
        'type'      => 'text',
        'show_on_cb' => function () {
            return current_user_can('administrator') || current_user_can('main');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_end_date',
        'name'      => 'Access End Date',
        'desc'      => 'The date the user no longer has access',
        'type'      => 'text_date_timestamp',
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_is_active',
        'name'      => 'Is Active',
        'desc'      => 'Check to activate this user',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_hr_alerts',
        'name'      => 'Receive HR alerts',
        'desc'      => 'Check to activate HR alerts for this user',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator') || current_user_can('main')|| current_user_can('individual');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_finance_alerts',
        'name'      => 'Receive finance alerts',
        'desc'      => 'Check to activate finance alerts for this user',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator') || current_user_can('main')|| current_user_can('individual');
        }
    ]);
     $user->add_field([
        'id'        => $prefix . 'user_hsw_alerts',
        'name'      => 'Receive H,S&W alerts',
        'desc'      => 'Check to activate H,S&W alerts for this user',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator') || current_user_can('main')|| current_user_can('individual');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_accepted_terms',
        'name'      => 'Accepted terms',
        'desc'      => 'Check to confirm acceptance of terms for this user',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_accepted_privacy_policy',
        'name'      => 'Accepted privacy policy',
        'desc'      => 'Check to confirm acceptance of privacy policy for this user',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_exclude_from_reports',
        'name'      => 'Exclude from reports',
        'desc'      => 'Check to exclude this user from reports',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_is_staff',
        'name'      => 'Is Staff',
        'desc'      => 'Check if this user is a staff member',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_is_super_user',
        'name'      => 'Is Super User',
        'desc'      => 'Check if this user is a super user',
        'type'      => 'checkbox',
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
    $user->add_field([
        'id'      => $prefix . 'user_group',
        'name'    => 'User Group',
        'desc'    => 'Select the group of the user',
        'type'    => 'select',
        'options' => [
            'individual' => 'Individual',
            'employee'   => 'Employee',
            'main'       => 'Main'
        ],
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);


    $user->add_field(array(
        'name'    => __('User permissions profile', 'hrinfospace'),
        'desc'    => __('Drag profile from the left column to the right column to attach them to this user.<br />You may rearrange the order of the profile in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'user_attached_user_profile',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_post' => 10,
                'post_type'      => 'user_profile',
            ), // override the get_posts args
        ),
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ));


    $user->add_field(array(
        'name'    => __('User page permissions', 'hrinfospace'),
        'desc'    => __('Drag a page from the left column to the right column to attach them to this user.<br />You may rearrange the order of the resources in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'user_attached_resource_pages',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_post' => 10,
                'post_type'      => 'resource_page',
            ), // override the get_posts args
        ),
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ));

    $user->add_field([
        'id'   => $prefix . 'user_admin_title',
        'name' => 'Admin',
        'type' => 'title',
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);


    $user->add_field([
        'id'        => $prefix . 'old_user_system_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text',
        'attributes' => [
            'readonly' => 'readonly',
        ],
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
    $user->add_field([
        'id'        => $prefix . 'user_created_by',
        'name'      => 'Created By',
        'desc'      => 'User who created this record',
        'type'      => 'text',
        //'attributes' => [
        //'readonly' => 'readonly',
        // ],
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);

    $user->add_field([
        'id'        => $prefix . 'user_last_login',
        'name'      => 'Last Login',
        'desc'      => 'The last time this user logged in',
        'type'      => 'text_date_timestamp',
        'date_format' => 'd/m/Y',
        'attributes' => [
            'readonly' => 'readonly',
        ],
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ]);
}
