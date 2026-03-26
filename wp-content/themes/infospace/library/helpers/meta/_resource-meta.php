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

    //Hide the attached resources field for news posts as they don't have any resources to attach and it was causing confusion for editors
    $current_user = wp_get_current_user();
    $user_role = !empty($current_user->roles) ? $current_user->roles[0] : '';

    $docQueryArgs =
        array(
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
        );

    $linkQueryArgs = array(
        'posts_per_page' => -1,
        'post_type'      => 'page_link',
        'orderby' => 'title',
        'order'   => 'ASC',
    );

    $queryArgs = array(
        'posts_per_page' => -1,
        'post_type'      => 'resource_page',
        'orderby' => 'title',
        'order'   => 'ASC',
    );

    if ($user_role === 'finance_editor') {
        global $finance_module_id;

        $financeResources = get_module_child_pages_using_module_id($finance_module_id);

        //Resources query args
        $queryArgs = array(
            'posts_per_page' => -1,
            'post_type'      => 'resource_page',
            'orderby' => 'title',
            'order'   => 'ASC',
            'post__in' => $financeResources
        );

        //Document query args
        $financeDocuments = [];
        foreach ($financeResources as $resource_id) {
            $documents = get_post_meta($resource_id, $prefix . 'resource_attached_documents', true);
            if ($documents) {
                $financeDocuments = array_merge($financeDocuments, (array) $documents);
            }
        }
        // Include documents created by the finance editor themselves as well in case they haven't attached them to a resource yet
        $financeDocuments = array_merge($financeDocuments, get_posts([
            'posts_per_page' => -1,
            'post_type'      => 'document',
            'author'         => $current_user->ID,
            'fields'         => 'ids',
        ]));

        $docQueryArgs = array(
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
            ),
            'post__in' => $financeDocuments

        );

        //Link query args
        $financeLinks = [];
        foreach ($financeResources as $resource_id) {
            $links = get_post_meta($resource_id, $prefix . 'resource_attached_links', true);
            if ($links) {
                $financeLinks = array_merge($financeLinks, (array) $links);
            }
        }
        $financeLinks = array_merge($financeLinks, get_posts([
            'posts_per_page' => -1,
            'post_type'      => 'page_link',
            'author'         => $current_user->ID,
            'fields'         => 'ids',
        ]));

        $linkQueryArgs = array(
            'posts_per_page' => 1000,
            'post_type'      => 'page_link',
            'orderby' => 'title',
            'order'   => 'ASC',
            'post__in' => $financeLinks
        );
    }

    if ($user_role === 'hsw_editor') {
        global $hsw_module_id;

        $hswResources = get_module_child_pages_using_module_id($hsw_module_id);

        //Resources query args
        $queryArgs = array(
            'posts_per_page' => -1,
            'post_type'      => 'resource_page',
            'orderby' => 'title',
            'order'   => 'ASC',
            'post__in' => $hswResources
        );

        //Document query args
        $hswDocuments = [];
        foreach ($hswResources as $resource_id) {
            $documents = get_post_meta($resource_id, $prefix . 'resource_attached_documents', true);
            if ($documents) {
                $hswDocuments = array_merge($hswDocuments, (array) $documents);
            }
        }
        // Include documents created by the HSW editor themselves as well in case they haven't attached them to a resource yet
        $hswDocuments = array_merge($hswDocuments, get_posts([
            'posts_per_page' => -1,
            'post_type'      => 'document',
            'author'         => $current_user->ID,
            'fields'         => 'ids',
        ]));

        $docQueryArgs = array(
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
            ),
            'post__in' => $hswDocuments

        );

        //Link query args
        $hswLinks = [];
        foreach ($hswResources as $resource_id) {
            $links = get_post_meta($resource_id, $prefix . 'resource_attached_links', true);
            if ($links) {
                $hswLinks = array_merge($hswLinks, (array) $links);
            }
        }
        $hswLinks = array_merge($hswLinks, get_posts([
            'posts_per_page' => -1,
            'post_type'      => 'page_link',
            'author'         => $current_user->ID,
            'fields'         => 'ids',
        ]));

        $linkQueryArgs = array(
            'posts_per_page' => 1000,
            'post_type'      => 'page_link',
            'orderby' => 'title',
            'order'   => 'ASC',
            'post__in' => $hswLinks
        );
    }

    if ($user_role === 'hr_editor') {
        global $hr_module_id;

        $hrResources = get_module_child_pages_using_module_id($hr_module_id);

        //Resources query args
        $queryArgs = array(
            'posts_per_page' => -1,
            'post_type'      => 'resource_page',
            'orderby' => 'title',
            'order'   => 'ASC',
            'post__in' => $hrResources
        );

        //Document query args
        $hrDocuments = [];
        foreach ($hrResources as $resource_id) {
            $documents = get_post_meta($resource_id, $prefix . 'resource_attached_documents', true);
            if ($documents) {
                $hrDocuments = array_merge($hrDocuments, (array) $documents);
            }
        }
        // Include documents created by the HR editor themselves as well in case they haven't attached them to a resource yet
        $hrDocuments = array_merge($hrDocuments, get_posts([
            'posts_per_page' => -1,
            'post_type'      => 'document',
            'author'         => $current_user->ID,
            'fields'         => 'ids',
        ]));

        $docQueryArgs = array(
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
            ),
            'post__in' => $hrDocuments

        );

        //Link query args
        $hrLinks = [];
        foreach ($hrResources as $resource_id) {
            $links = get_post_meta($resource_id, $prefix . 'resource_attached_links', true);
            if ($links) {
                $hrLinks = array_merge($hrLinks, (array) $links);
            }
        }
        $hrLinks = array_merge($hrLinks, get_posts([
            'posts_per_page' => -1,
            'post_type'      => 'page_link',
            'author'         => $current_user->ID,
            'fields'         => 'ids',
        ]));

        $linkQueryArgs = array(
            'posts_per_page' => 1000,
            'post_type'      => 'page_link',
            'orderby' => 'title',
            'order'   => 'ASC',
            'post__in' => $hrLinks
        );
    }


    $resource->add_field(array(
        'name'    => __('Documents shown', 'hrinfospace'),
        'desc'    => __('Drag files from the left column to the right column to attach them to this resource.<br />You may rearrange the order of the files in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'resource_attached_documents',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column

        'options' => array(
            'show_thumbnails' => false, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => $docQueryArgs, // override the get_posts args
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
            'query_args'      => $linkQueryArgs, // override the get_posts args
        ),
    ));



    $resource->add_field(array(
        'name'    => __('Landing pages shown', 'hrinfospace'),
        'desc'    => __('Drag landing pages from the left column to the right column to attach them to this resource.<br />You may rearrange the order of the posts in the right column by dragging and dropping.', 'hrinfospace'),
        'id'      => $prefix . 'resource_attached_resources',
        'type'    => 'custom_attached_posts',
        'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
        'options' => array(
            'show_thumbnails' => false, // Show thumbnails on the left
            'filter_boxes'    => true, // Show a text box for filtering the results
            'query_args'      => $queryArgs, // override the get_posts args
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
