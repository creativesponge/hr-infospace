<?php

// Document files
add_filter('cmb2_meta_boxes', 'cmb2_newsletter_metabox');
function cmb2_newsletter_metabox()
{
    global $prefix;

    $newsletter = new_cmb2_box([
        'id'            => $prefix . 'newsletter_details',
        'title'         => 'Newsletter details',
        'object_types'  => ['newsletter'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);

    $newsletter->add_field([
        'id'        => $prefix . 'newsletter_date',
        'name'      => 'Date',
        'desc'      => 'The date of the newsletter',
        'type'      => 'text_date_timestamp'
    ]);
    $newsletter->add_field([
        'id'        => $prefix . 'newsletter_start_date',
        'name'      => 'Start Date',
        'desc'      => 'The date the document became effective',
        'type'      => 'text_date_timestamp',
        'column'    => true,
        'sortable'  => true,
    ]);

    $newsletter->add_field([
        'id'        => $prefix . 'newsletter_end_date',
        'name'      => 'End Date',
        'desc'      => 'The date the document is no longer valid',
        'type'      => 'text_date_timestamp',
        'column'    => true,
        'sortable'  => true,
    ]);
    $newsletter->add_field([
        'id'        => $prefix . 'newsletter_link',
        'name'      => 'Newsletter Link',
        'desc'      => 'Link to the newsletter',
        'type'      => 'text_url'
    ]);
    $newsletter->add_field([
        'id'        => $prefix . 'newsletter_file',
        'name'      => 'Newsletter File',
        'desc'      => 'Upload the newsletter file',
        'type'      => 'file',
        'options'   => [
            'url' => false, // Hide the text input for the URL
        ],
        'column'    => true,
    ]);

    

    $newsletter->add_field([
        'id'        => $prefix . 'newsletter_summary',
        'name'      => 'Summary',
        'desc'      => 'Short summary of the newsletter',
        'type'      => 'textarea'
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

    $newsletter->add_field(array(
        'name'    => __('Available to users attached to', 'hrinfospace'),
        'desc'    => __('Drag page from the left column to the right column to attach them to this newsletter.<br />You may rearrange the order of the resources in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'newsletter_attached_resource_pages',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => $queryArgs, // override the get_posts args
        ),
    ));

    $newsletterSide = new_cmb2_box([
        'id'            => $prefix . 'newsletter_details_side',
        'title'         => 'Document info',
        'object_types'  => ['newsletter'],
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
    ]);


    $newsletterSide->add_field([
        'id'        => $prefix . 'old_newsletter_system_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text',
        'attributes' => [
            //'readonly' => 'readonly',
             'style' => 'display: none;'
        ]
    ]);
}
