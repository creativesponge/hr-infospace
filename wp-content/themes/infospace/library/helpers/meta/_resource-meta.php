<?php

// Pages
add_filter('cmb2_meta_boxes', 'cmb2_page_metabox');
function cmb2_page_metabox()
{
    global $prefix;

    $resource = new_cmb2_box([
        'id'            => $prefix . 'resource_page_details',
        'title'         => 'Page details',
        'object_types'  => ['resource_page'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);
    $resource->add_field([
        'id'        => $prefix . 'resource_summary',
        'name'      => 'Summary',
        'desc'      => 'Short summary of the document',
        'type'      => 'textarea'
    ]);
    $resource->add_field([
        'id'        => $prefix . 'resource_keywords',
        'name'      => 'Keywords',
        'desc'      => 'Comma-separated keywords',
        'type'      => 'textarea'
    ]);
    /*$resource->add_field([
        'id'        => $prefix .'resource_related_documents',
        'name'      => 'Documents shown',
        'desc'      => 'Select one or more document to show',
        'type'      => 'post_search_text',
        'post_type' => 'document',
        'multiple'  => true,
        'select_type' => 'select',
        'attributes'  => [
            'style' => 'width:100%'
        ],
    ]);*/

    $resource->add_field(array(
        'name'    => __('Documents shown', 'hrinfospace'),
        'desc'    => __('Drag files from the left column to the right column to attach them to this resource.<br />You may rearrange the order of the files in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'resource_attached_documents',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column

        'options' => array(
            'show_thumbnails' => false, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_page' => 1000,
                'post_type'      => 'document',
                'meta_query'     => array(
                    array(
                        'key'     => $prefix . 'document_is_active',
                        'value'   => 'on',
                        'compare' => 'LIKE',
                        'orderby' => 'title',
                        'order'   => 'ASC',
                    )
                )
            ), // override the get_posts args
        ),
    ));

    $resource->add_field(array(
        'name'    => __('Links shown', 'hrinfospace'),
        'desc'    => __('Drag links from the left column to the right column to attach them to this resource.<br />You may rearrange the order of the links in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'resource_attached_links',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => false, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_page' => -1,
                'post_type'      => 'page_link',
                'orderby' => 'title',
                'order'   => 'ASC',
            ), // override the get_posts args
        ),
    ));

    $resource->add_field(array(
        'name'    => __('Resources shown', 'hrinfospace'),
        'desc'    => __('Drag resource from the left column to the right column to attach them to this resource.<br />You may rearrange the order of the posts in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'resource_attached_resources',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => false, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => array(
                'posts_per_page' => -1,
                'post_type'      => 'resource_page',
                'orderby' => 'title',
                'order'   => 'ASC',
            ), // override the get_posts args
        ),
    ));



    $resourceSide = new_cmb2_box([
        'id'            => $prefix . 'resource_details_side',
        'title'         => 'Resource info',
        'object_types'  => ['resource_page'],
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
    ]);


    $resourceSide->add_field([
        'id'        => $prefix . 'old_resource_system_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text',
        'attributes' => [
            //'readonly' => 'readonly',
             'style' => 'display: none;'
        ]
    ]);

    $resourceSide->add_field([
        'id'        => $prefix . 'resource_open_access',
        'name'      => 'Make open access',
        'desc'      => 'Check to make this resource openly accessible',
        'type'      => 'checkbox',
    ]);



    $resourceSide->add_field([
        'id'        => $prefix . 'resource_start_date',
        'name'      => 'Start Date',
        'desc'      => 'The date the document became effective',
        'type'      => 'text_date_timestamp',
        'column'    => true,
        'sortable'  => true,
    ]);

    $resourceSide->add_field([
        'id'        => $prefix . 'resource_end_date',
        'name'      => 'End Date',
        'desc'      => 'The date the document is no longer valid',
        'type'      => 'text_date_timestamp',
        'column'    => true,
        'sortable'  => true,
    ]);

    $resourceSide->add_field([
        'id'        => $prefix . 'resource_level',
        'name'      => 'Level',
        'desc'      => 'The level of the document',
        'type'      => 'text_small',
        'attributes' => [
            'type' => 'number',
            'min'  => 0,
            'style' => 'display: none;'
            //'readonly' => 'readonly',
        ],
    ]);
    $resourceSide->add_field([
        'id'        => $prefix . 'resource_lft',
        'name'      => 'Left',
        'desc'      => 'The left from old site',
        'type'      => 'text_small',
        'attributes' => [
            'type' => 'number',
            'min'  => 0,
            //'readonly' => 'readonly',
            'style' => 'display: none;'
        ],
    ]);
    $resourceSide->add_field([
        'id'        => $prefix . 'resource_parent_id',
        'name'      => 'Parent ID',
        'desc'      => 'The parent ID from old site',
        'type'      => 'text_small',
        'attributes' => [
            'type' => 'number',
            'min'  => 0,
            //'readonly' => 'readonly',
            'style' => 'display: none;'
        ],
    ]);
    $resourceSide->add_field([
        'id'        => $prefix . 'resource_right',
        'name'      => 'Right',
        'desc'      => 'The right from old site',
        'type'      => 'text_small',
        'attributes' => [
            'type' => 'number',
            'min'  => 0,
            //'readonly' => 'readonly',
            'style' => 'display: none;'
        ],
    ]);
    $resourceSide->add_field([
        'id'        => $prefix . 'resource_tree_id',
        'name'      => 'Tree ID',
        'desc'      => 'The tree ID from old site',
        'type'      => 'text_small',
        'attributes' => [
            'type' => 'number',
            'min'  => 0,
            'style' => 'display: none;'
            //'readonly' => 'readonly',
        ],
    ]);

    $resourceSide->add_field([
        'id'        => $prefix . 'resource_slug',
        'name'      => 'Slug',
        'desc'      => 'The slug from old site',
        'type'      => 'text',
        'attributes' => [
            //'readonly' => 'readonly',
            'style' => 'display: none;'
        ]

    ]);
}
