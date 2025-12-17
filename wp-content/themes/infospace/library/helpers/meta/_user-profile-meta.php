<?php

// Users
add_filter('cmb2_admin_init', 'cmb2_user_profile_metabox');
function cmb2_user_profile_metabox()
{
    global $prefix;

    $user = new_cmb2_box([
        'id'            => $prefix . 'user_profile_details',
        'title'         => 'User details',
        'object_types'  => ['user_profile'],
        //'context'       => 'normal',
        //'priority'      => 'high',
        'show_names'    => true,
    ]);
    
    $user->add_field(array(
        'name'    => __('User page permissions', 'hrinfospace'),
        'desc'    => __('Drag page from the left column to the right column to attach them to this newsletter.<br />You may rearrange the order of the resources in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'profile_attached_resource_pages',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_page' => -1, 
                'post_type'      => 'resource_page',
            ), // override the get_posts args
        ),
        'show_on_cb' => function () {
            return current_user_can('administrator');
        }
    ));



}
