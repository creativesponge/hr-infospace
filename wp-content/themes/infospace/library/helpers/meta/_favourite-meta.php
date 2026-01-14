<?php

// Favourites
add_filter('cmb2_meta_boxes', 'cmb2_favourite_metabox');
function cmb2_favourite_metabox()
{
    global $prefix;

    $resource = new_cmb2_box([
        'id'            => $prefix . 'favourite_details',
        'title'         => 'favourite details',
        'object_types'  => ['favourite'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);

    $resource->add_field(array(
        'name'    => __('Documents favourited', 'hrinfospace'),
        'desc'    => __('Drag files from the left column to the right column to attach them to this resource.<br />You may rearrange the order of the files in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'favourite_attached_documents',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_page' => 10,
                'post_type'      => 'document',
            ), // override the get_posts args
        ),
    ));

    $resource->add_field(array(
        'name'    => __('Links favourited', 'hrinfospace'),
        'desc'    => __('Drag links from the left column to the right column to attach them to this resource.<br />You may rearrange the order of the links in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'favourite_attached_links',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_page' => 10,
                'post_type'      => 'page_link',
            ), // override the get_posts args
        ),
    ));

    $resource->add_field(array(
        'name'    => __('Resources favourited', 'hrinfospace'),
        'desc'    => __('Drag resource from the left column to the right column to attach them to this resource.<br />You may rearrange the order of the posts in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'favourite_attached_resources',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => true, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_page' => 10,
                'post_type'      => 'resource_page',
            ), // override the get_posts args
        ),
    ));
    $favouriteSide = new_cmb2_box([
        'id'            => $prefix . 'favourite_details_side',
        'title'         => 'Favourite info',
        'object_types'  => ['favourite'],
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
    ]);

 

    $favouriteSide->add_field([
        'id'        => $prefix . 'old_favourite_system_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text',
        'attributes' => [
            //'readonly' => 'readonly',
             'style' => 'display: none;'
        ]
    ]);
}
