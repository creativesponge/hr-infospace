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
    $newsletter->add_field(array(
        'name'    => __('Available to users attached to', 'hrinfospace'),
        'desc'    => __('Drag page from the left column to the right column to attach them to this newsletter.<br />You may rearrange the order of the resources in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'newsletter_attached_resource_pages',
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
            'readonly' => 'readonly',
             'style' => 'display: none;'
        ]
    ]);
}
