<?php

// Posts
add_filter('cmb2_meta_boxes', 'cmb2_post_metabox');
function cmb2_post_metabox()
{
    global $prefix;

    $post = new_cmb2_box([
        'id'            => $prefix . 'post_details',
        'title'         => 'Post details',
        'object_types'  => ['post'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);
    $post->add_field([
        'id'        => $prefix . 'post_summary',
        'name'      => 'Summary',
        'desc'      => 'Short summary of the document',
        'type'      => 'wysiwyg'
    ]);
    $post->add_field([
        'id'     => $prefix . 'post_featured',
        'name'   => 'Featured',
        'desc'   => 'Mark this post as featured',
        'type'   => 'checkbox',


    ]);

    //Hide the attached resources field for news posts as they don't have any resources to attach and it was causing confusion for editors
    $current_user = wp_get_current_user();
    $user_role = !empty($current_user->roles) ? $current_user->roles[0] : '';
    $queryArgs = array(
        'posts_per_page' => -1,
        'post_type'      => 'resource_page',
        'post_parent' => 0,
    );

    if ($user_role === 'finance_editor') {
        global $finance_module_id;

        $financeResources = get_module_child_pages_using_module_id($finance_module_id);
        $queryArgs = array(
            'posts_per_page' => -1,
            'post_type'      => 'resource_page',
            'post_parent' => 0,
            'post__in' => $financeResources
        );
    }
    
    if ($user_role === 'hsw_editor') {
        global $hsw_module_id;

        $hswResources = get_module_child_pages_using_module_id($hsw_module_id);
        $queryArgs = array(
            'posts_per_page' => -1,
            'post_type'      => 'resource_page',
            'post_parent' => 0,
            'post__in' => $hswResources
        );
    }

    if ($user_role === 'hr_editor') {
        global $hr_module_id;

        $hrResources = get_module_child_pages_using_module_id($hr_module_id);
        $queryArgs = array(
            'posts_per_page' => -1,
            'post_type'      => 'resource_page',
            'post_parent' => 0,
            'post__in' => $hrResources
        );
    }


    $post->add_field(array(
        'name'    => __('Available to users attached to', 'hrinfospace'),
        'desc'    => __('Drag page from the left column to the right column to attach them to this post.<br />You may rearrange the order of the pages in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'post_attached_resource_pages',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => $queryArgs, // override the get_posts args
        ),
    ));



    $postSide = new_cmb2_box([
        'id'            => $prefix . 'post_details_side',
        'title'         => 'Post info',
        'object_types'  => ['post'],
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
    ]);



    $postSide->add_field([
        'id'        => $prefix . 'post_start_date',
        'name'      => 'Start Date',
        'desc'      => 'The date the document became effective',
        'type'      => 'text_date_timestamp',
        'date_format' => 'd/m/Y'
    ]);

    $postSide->add_field([
        'id'        => $prefix . 'post_end_date',
        'name'      => 'End Date',
        'desc'      => 'The date the document is no longer valid',
        'type'      => 'text_date_timestamp',
        'date_format' => 'd/m/Y'
    ]);
    $postSide->add_field([
        'id'        => $prefix . 'old_post_system_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text',
        'attributes' => [
            //'readonly' => 'readonly',
            'style' => 'display: none;'
        ]
    ]);
    $postSide->add_field([
        'id'        => $prefix . 'post_slug',
        'name'      => 'Slug',
        'desc'      => 'The slug from old site',
        'type'      => 'text',
        'attributes' => [
            //'readonly' => 'readonly',
            'style' => 'display: none;'
        ]

    ]);



    add_filter('manage_post_posts_columns', function ($columns) use ($prefix) {
        $columns[$prefix . 'post_featured'] = __('Featured', 'hrinfospace');
        $columns[$prefix . 'post_start_date'] = __('Start Date', 'hrinfospace');
        $columns[$prefix . 'post_end_date'] = __('End Date', 'hrinfospace');
        return $columns;
    });

    add_action('manage_post_posts_custom_column', function ($column, $post_id) use ($prefix) {
        if ($column === $prefix . 'post_featured') {
            $value = get_post_meta($post_id, $prefix . 'post_featured', true);
            echo (!empty($value) && ($value === 'on' || $value === '1' || $value === 1)) ? '✔' : '';
        } elseif ($column === $prefix . 'post_start_date') {
            $value = get_post_meta($post_id, $prefix . 'post_start_date', true);
            echo !empty($value) ? date('d/m/Y', $value) : '';
        } elseif ($column === $prefix . 'post_end_date') {
            $value = get_post_meta($post_id, $prefix . 'post_end_date', true);
            echo !empty($value) ? date('d/m/Y', $value) : '';
        }
    }, 10, 2);
}
