<?php

// Enquiries
add_action('init', 'post_type_enquiries', 0);
function post_type_enquiries()
{
    $labels = array(
        'name'                  => 'Enquiries',
        'singular_name'         => 'Enquiry',
        'menu_name'             => 'Enquiries',
        'name_admin_bar'        => 'Enquiries',
        'archives'              => 'Enquiry List',
        'parent_item_colon'     => 'Parent Enquiries:',
        'all_items'             => 'All Enquiries',
        'add_new_item'          => 'Add New Enquiry',
        'add_new'               => 'Add New Enquiry',
        'new_item'              => 'New Enquiry',
        'edit_item'             => 'Edit Enquiry',
        'update_item'           => 'Update Enquiry',
        'view_item'             => 'View Enquiries',
        'search_items'          => 'Search Enquiries',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'featured_image'        => 'Listing Image',
        'set_featured_image'    => 'Set listing image',
        'remove_featured_image' => 'Remove Enquiry image',
        'use_featured_image'    => 'Use as Enquiry image',
        'insert_into_item'      => 'Insert into Enquiries',
        'uploaded_to_this_item' => 'Uploaded to this Enquiry',
        'items_list'            => 'Enquiries list',
        'items_list_navigation' => 'Enquiries list navigation',
        'filter_items_list'     => 'Filter Enquiries list',
    );

    $args = array(
        'label'                 => 'Enquiries',
        'description'           => 'Enquiries',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-email-alt',
        'supports'              => array(),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 20,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap' => true,
    );
    register_post_type('enquiry', $args);
    remove_post_type_support('enquiry', 'editor');


    // Add columns
    add_filter('manage_enquiry_posts_columns', 'replace_enquiry_column_title');
    function replace_enquiry_column_title($posts_columns)
    {
        $posts_columns['title'] = 'From';
        $posts_columns['enquiry_type'] = __('Type', 'theme_enquiry');
        return $posts_columns;
    }


    // Add the custom columns to the en post type:// Add the data to the custom columns for the book post type:
    add_action('manage_enquiry_posts_custom_column', 'custom_enquiry_column', 10, 2);
    function custom_enquiry_column($posts_columns, $post_id)
    {
        switch ($posts_columns) {

            case 'enquiry_type':
                $type = get_post_meta($post_id, 'enquiry_type', true);
                //$terms = get_the_term_list( $post_id , 'enquiry_type' , '' , ',' , '' );
                if (is_string($type))
                    echo $type;
                else
                    _e('Unable to get enquiry type(s)', 'theme_enquiry');
                break;
        }
    }
}

// Module
add_action('init', 'post_type_module_page', 0);
function post_type_module_page()
{
    $labels = array(
        'name'                  => 'Module',
        'singular_name'         => 'Module',
        'menu_name'             => 'Modules',
        'name_admin_bar'        => 'Module',
        'archives'              => 'Module List',
        'parent_item_colon'     => 'Parent Module:',
        'all_items'             => 'All Modules',
        'add_new_item'          => 'Add Module',
        'add_new'               => 'Add Module',
        'new_item'              => 'New Module',
        'edit_item'             => 'Edit Module',
        'update_item'           => 'Update Module',
        'view_item'             => 'View Module',
        'search_items'          => 'Search Module',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'featured_image'        => 'Listing Image',
        'set_featured_image'    => 'Set listing image',
        'remove_featured_image' => 'Remove Profile image',
        'use_featured_image'    => 'Use as Profile image',
        'insert_into_item'      => 'Insert into Module',
        'uploaded_to_this_item' => 'Uploaded to this Module',
        'items_list'            => 'Module list',
        'items_list_navigation' => 'Module list navigation',
        'filter_items_list'     => 'Filter Module',
    );

    $args = array(
        'label'                 => 'Module',
        'description'           => 'Module item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-networking',
        'show_in_rest'             => true,
        'supports'              => array('title', 'page-attributes', 'thumbnail'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'module', 'with_front' => false),
        'taxonomies'            => array(),

    );
    register_post_type('module', $args);
}

// User profile
add_action('init', 'post_type_user_profile', 0);
function post_type_user_profile()
{
    $labels = array(
        'name'                  => 'Access Profile',
        'singular_name'         => 'Access Profile',
        'menu_name'             => 'Access Profiles',
        'name_admin_bar'        => 'Access Profile',
        'archives'              => 'Access Profile List',
        'parent_item_colon'     => 'Parent Access Profile:',
        'all_items'             => 'All Access Profiles',
        'add_new_item'          => 'Add Access Profile',
        'add_new'               => 'Add Access Profile',
        'new_item'              => 'New Access Profile',
        'edit_item'             => 'Edit Access Profile',
        'update_item'           => 'Update Access Profile',
        'view_item'             => 'View Access Profile',
        'search_items'          => 'Search Access Profile',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'featured_image'        => 'Listing Image',
        'set_featured_image'    => 'Set listing image',
        'remove_featured_image' => 'Remove AccessProfile image',
        'use_featured_image'    => 'Use as Access Profile image',
        'insert_into_item'      => 'Insert into Access Profile',
        'uploaded_to_this_item' => 'Uploaded to this Access Profile',
        'items_list'            => 'Access Profile list',
        'items_list_navigation' => 'Access Profile list navigation',
        'filter_items_list'     => 'Filter Access Profile',

    );

    $args = array(
        'label'                 => 'User Access Profile',
        'description'           => 'Front end access Profile item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-networking',
        'show_in_rest'             => true,
        'supports'              => array('title', 'page-attributes'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => 'users.php', // This places it under Users 
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'user-profile', 'with_front' => false),
        'taxonomies'            => array(),

    );
    register_post_type('user_profile', $args);
}

// Resource pages
add_action('init', 'post_type_resource_page', 0);
function post_type_resource_page()
{
    $labels = array(
        'name'                  => 'Resource',
        'singular_name'         => 'Resource',
        'menu_name'             => 'Resources',
        'name_admin_bar'        => 'Resource',
        'archives'              => 'Resource List',
        'parent_item_colon'     => 'Parent Resource:',
        'all_items'             => 'All Resources',
        'add_new_item'          => 'Add Resource',
        'add_new'               => 'Add Resource',
        'new_item'              => 'New Resource',
        'edit_item'             => 'Edit Resource',
        'update_item'           => 'Update Resource',
        'view_item'             => 'View Resource',
        'search_items'          => 'Search Resource',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'featured_image'        => 'Listing Image',
        'set_featured_image'    => 'Set listing image',
        'remove_featured_image' => 'Remove Profile image',
        'use_featured_image'    => 'Use as Profile image',
        'insert_into_item'      => 'Insert into Resource',
        'uploaded_to_this_item' => 'Uploaded to this Resource',
        'items_list'            => 'Resource list',
        'items_list_navigation' => 'Resource list navigation',
        'filter_items_list'     => 'Filter Resource',
    );

    $args = array(
        'label'                 => 'Resource',
        'description'           => 'Resource item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-media-text',
        'show_in_rest'             => true,
        'supports'              => array('revisions', 'title', 'editor', 'excerpt', 'page-attributes'),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'resource', 'with_front' => true),
        'taxonomies'            => array(),

    );
    register_post_type('resource_page', $args);
}

// Documents
add_action('init', 'post_type_document', 0);
function post_type_document()
{
    $labels = array(
        'name'                  => 'Document',
        'singular_name'         => 'Document',
        'menu_name'             => 'Documents',
        'name_admin_bar'        => 'Document',
        'archives'              => 'Document List',
        'parent_item_colon'     => 'Parent Document:',
        'all_items'             => 'All Documents',
        'add_new_item'          => 'Add Document',
        'add_new'               => 'Add Document',
        'new_item'              => 'New Document',
        'edit_item'             => 'Edit Document',
        'update_item'           => 'Update Document',
        'view_item'             => 'View Document',
        'search_items'          => 'Search Document',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'featured_image'        => 'Listing Image',
        'set_featured_image'    => 'Set listing image',
        'remove_featured_image' => 'Remove Profile image',
        'use_featured_image'    => 'Use as Profile image',
        'insert_into_item'      => 'Insert into Document',
        'uploaded_to_this_item' => 'Uploaded to this Document',
        'items_list'            => 'Document list',
        'items_list_navigation' => 'Document list navigation',
        'filter_items_list'     => 'Filter Document',
    );

    $args = array(
        'label'                 => 'Document',
        'description'           => 'Document item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-media-document',

        'supports'              => array('revisions', 'title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'document', 'with_front' => false),
        'taxonomies'            => array(),
        'show_in_rest'          => true,
    );
    register_post_type('document', $args);
}

//Add an admin column for the document post type which shows the resource_page post types where thie lost id is in the $prefix . 'resource_attached_documents' field
add_filter('manage_document_posts_columns', 'add_document_resource_page_column');
function add_document_resource_page_column($columns)
{
    $columns['related_resources'] = 'Attached to';
    return $columns;
}
add_action('manage_document_posts_custom_column', 'show_document_resource_page_column', 10, 2);
function show_document_resource_page_column($column, $post_id)
{
    global $prefix;
    if ($column == 'related_resources') {
        $related_resources = get_posts(array(
            'post_type' => 'resource_page',
            'meta_query' => array(
                array(
                    'key'     => $prefix . 'resource_attached_documents',
                    'value'   => $post_id,
                    'compare' => 'LIKE',
                ),
            ),
        ));
        if (!empty($related_resources)) {
            $links = array();
            foreach ($related_resources as $resource) {
                $links[] = '<a href="' . get_edit_post_link($resource->ID) . '">' . esc_html(get_the_title($resource->ID)) . '</a>';
            }
            echo implode(',<br> ', $links);
        } else {
            echo '—';
        }
    }
}



// Document files // NOT USED?
/*add_action('init', 'post_type_document_file', 0);
function post_type_document_file()
{
    $labels = array(
        'name'                  => 'Document file',
        'singular_name'         => 'Document file',
        'menu_name'             => 'Document files',
        'name_admin_bar'        => 'Document file',
        'archives'              => 'Document file List',
        'parent_item_colon'     => 'Parent Document file:',
        'all_items'             => 'All Document files',
        'add_new_item'          => 'Add Document file',
        'add_new'               => 'Add Document file',
        'new_item'              => 'New Document file',
        'edit_item'             => 'Edit Document file',
        'update_item'           => 'Update Document file',
        'view_item'             => 'View Document file',
        'search_items'          => 'Search Document files',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'insert_into_item'      => 'Insert into Document file',
        'uploaded_to_this_item' => 'Uploaded to this Document file',
        'items_list'            => 'Document files list',
        'items_list_navigation' => 'Document files list navigation',
        'filter_items_list'     => 'Filter Document files',
    );

    $args = array(
        'label'                 => 'Document file',
        'description'           => 'Document file item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-media-default',
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'document-file', 'with_front' => false),
        'taxonomies'            => array(),
        'show_in_rest'          => true,
    );
    register_post_type('document_file', $args);
}
*/

// Page links
add_action('init', 'post_type_page_links', 0);
function post_type_page_links()
{
    $labels = array(
        'name'                  => 'Web Links',
        'singular_name'         => 'Web Link',
        'menu_name'             => 'Web Links',
        'name_admin_bar'        => 'Web Link',
        'archives'              => 'Web Link List',
        'parent_item_colon'     => 'Parent Web Link:',
        'all_items'             => 'All Web Links',
        'add_new_item'          => 'Add Web Link',
        'add_new'               => 'Add Web Link',
        'new_item'              => 'New Web Link',
        'edit_item'             => 'Edit Web Link',
        'singular_name'         => 'Web Link',
        'menu_name'             => 'Web Links',
        'name_admin_bar'        => 'Web Link',
        'archives'              => 'Web Link List',
        'parent_item_colon'     => 'Parent Web Link:',
        'all_items'             => 'All Web Links',
        'add_new_item'          => 'Add Web Link',
        'add_new'               => 'Add Web Link',
        'new_item'              => 'New Web Link',
        'edit_item'             => 'Edit Web Link',
        'update_item'           => 'Update Web Link',
        'view_item'             => 'View Web Link',
        'search_items'          => 'Search Web Links',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',

        'insert_into_item'      => 'Insert into Web Link',
        'uploaded_to_this_item' => 'Uploaded to this Web Link',
        'items_list'            => 'Web Links list',
        'items_list_navigation' => 'Web Links list navigation',
        'filter_items_list'     => 'Filter Web Links',
    );

    $args = array(
        'label'                 => 'Web Link',
        'description'           => 'Web Link item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-admin-links',
        'supports'              => array('revisions', 'title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'page-link', 'with_front' => false),
        'taxonomies'            => array(),
        'show_in_rest'          => true,
    );
    register_post_type('page_link', $args);
}

// Add columns for page_link
add_filter('manage_page_link_posts_columns', 'add_page_link_columns');
function add_page_link_columns($columns)
{
    $columns['menu_order'] = __('Order');
    return $columns;
}

// Populate the order column
add_action('manage_page_link_posts_custom_column', 'page_link_custom_column', 10, 2);
function page_link_custom_column($column, $post_id)
{
    switch ($column) {
        case 'menu_order':
            echo get_post_field('menu_order', $post_id);
            break;
    }
}

// Make the order column sortable
add_filter('manage_edit-page_link_sortable_columns', 'page_link_sortable_columns');
function page_link_sortable_columns($columns)
{
    $columns['menu_order'] = 'menu_order';
    return $columns;
}

//Add an admin column for the page_link post type which shows the resource_page post types where this post id is in the $prefix . 'resource_attached_links' field
add_filter('manage_page_link_posts_columns', 'add_page_link_resource_column');
function add_page_link_resource_column($columns)
{
    $columns['related_resources'] = 'Attached to';
    return $columns;
}
add_action('manage_page_link_posts_custom_column', 'show_page_link_resource_column', 10, 2);
function show_page_link_resource_column($column, $post_id)
{
    global $prefix;
    if ($column == 'related_resources') {
        $related_resources = get_posts(array(
            'post_type' => 'resource_page',
            'meta_query' => array(
                array(
                    'key'     => $prefix . 'resource_attached_links',
                    'value'   => $post_id,
                    'compare' => 'LIKE',
                ),
            ),
        ));
        if (!empty($related_resources)) {
            $links = array();
            foreach ($related_resources as $resource) {
                $links[] = '<a href="' . get_edit_post_link($resource->ID) . '">' . esc_html(get_the_title($resource->ID)) . '</a>';
            }
            echo implode(',<br> ', $links);
        } else {
            echo '—';
        }
    }
}

// Make the related_resources column sortable
add_filter('manage_edit-page_link_sortable_columns', 'page_link_related_resources_sortable');
function page_link_related_resources_sortable($columns)
{
    $columns['related_resources'] = 'related_resources';
    return $columns;
}

// Handle sorting by related resources
add_action('pre_get_posts', 'page_link_orderby_related_resources');
function page_link_orderby_related_resources($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ('related_resources' === $query->get('orderby')) {
        $query->set('orderby', 'meta_value');
    }
}

// Newsletter
add_action('init', 'post_type_newsletter', 0);
function post_type_newsletter()
{
    $labels = array(
        'name'                  => 'Newsletter',
        'singular_name'         => 'Newsletter',
        'menu_name'             => 'Newsletters',
        'name_admin_bar'        => 'Newsletter',
        'archives'              => 'Newsletter List',
        'parent_item_colon'     => 'Parent Newsletter:',
        'all_items'             => 'All Newsletters',
        'add_new_item'          => 'Add Newsletter',
        'add_new'               => 'Add Newsletter',
        'new_item'              => 'New Newsletter',
        'edit_item'             => 'Edit Newsletter',
        'update_item'           => 'Update Newsletter',
        'view_item'             => 'View Newsletter',
        'search_items'          => 'Search Newsletters',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'featured_image'        => 'Listing Image',
        'set_featured_image'    => 'Set listing image',
        'remove_featured_image' => 'Remove Profile image',
        'use_featured_image'    => 'Use as Profile image',
        'insert_into_item'      => 'Insert into Newsletter',
        'uploaded_to_this_item' => 'Uploaded to this Newsletter',
        'items_list'            => 'Newsletter list',
        'items_list_navigation' => 'Newsletter list navigation',
        'filter_items_list'     => 'Filter Newsletter',
    );

    $args = array(
        'label'                 => 'Newsletter',
        'description'           => 'Newsletter item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-email',
        'supports'              => array('revisions', 'title'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'newsletter', 'with_front' => false),
        'taxonomies'            => array(),
        'show_in_rest'          => true,
    );
    register_post_type('newsletter', $args);
}

// Favourite
add_action('init', 'post_type_favourite', 0);
function post_type_favourite()
{
    $labels = array(
        'name'                  => 'Favourite',
        'singular_name'         => 'Favourite',
        'menu_name'             => 'Favourites',
        'name_admin_bar'        => 'Favourite',
        'archives'              => 'Favourite List',
        'parent_item_colon'     => 'Parent Favourite:',
        'all_items'             => 'All Favourites',
        'add_new_item'          => 'Add Favourite',
        'add_new'               => 'Add Favourite',
        'new_item'              => 'New Favourite',
        'edit_item'             => 'Edit Favourite',
        'update_item'           => 'Update Favourite',
        'view_item'             => 'View Favourite',
        'search_items'          => 'Search Favourites',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'featured_image'        => 'Listing Image',
        'set_featured_image'    => 'Set listing image',
        'remove_featured_image' => 'Remove Profile image',
        'use_featured_image'    => 'Use as Profile image',
        'insert_into_item'      => 'Insert into Favourite',
        'uploaded_to_this_item' => 'Uploaded to this Favourite',
        'items_list'            => 'Favourite list',
        'items_list_navigation' => 'Favourite list navigation',
        'filter_items_list'     => 'Filter Favourite',
    );

    $args = array(
        'label'                 => 'Favourite',
        'description'           => 'Favourite item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-admin-post',
        'supports'              => array('revisions', 'title', 'author'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'favourite', 'with_front' => false),
        'taxonomies'            => array(),
        'show_in_rest'          => true,
    );
    register_post_type('favourite', $args);
}

// Page Views
/*
add_action('init', 'post_type_user_view', 0);
function post_type_user_view()
{
    $labels = array(
        'name'                  => 'User Views',
        'singular_name'         => 'User View',
        'menu_name'             => 'User Views',
        'name_admin_bar'        => 'User View',
        'archives'              => 'User View List',
        'parent_item_colon'     => 'Parent User View:',
        'all_items'             => 'All User Views',
        'add_new_item'          => 'Add User View',
        'add_new'               => 'Add User View',
        'new_item'              => 'New User View',
        'edit_item'             => 'Edit User View',
        'singular_name'         => 'User View',
        'menu_name'             => 'User Views',
        'name_admin_bar'        => 'User View',
        'archives'              => 'User View List',
        'parent_item_colon'     => 'Parent User View:',
        'all_items'             => 'All User Views',
        'add_new_item'          => 'Add User View',
        'add_new'               => 'Add User View',
        'new_item'              => 'New User View',
        'edit_item'             => 'Edit User View',
        'update_item'           => 'Update User View',
        'view_item'             => 'View User View',
        'search_items'          => 'Search User Views',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',

        'insert_into_item'      => 'Insert into User View',
        'uploaded_to_this_item' => 'Uploaded to this User View',
        'items_list'            => 'User Views list',
        'items_list_navigation' => 'User Views list navigation',
        'filter_items_list'     => 'Filter User Views',
    );

    $args = array(
        'label'                 => 'User View',
        'description'           => 'User View item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-chart-bar',
       'supports'              => array('title', 'page-attributes'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 29,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'page-link', 'with_front' => false),
        'taxonomies'            => array(),
        'show_in_rest'          => true,
    );
    register_post_type('user_view', $args);
}
*/

function add_custom_taxonomies()
{

    // Add document taxonomy
    register_taxonomy('doc_type', 'document', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => false,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x('Document type', 'taxonomy general name'),
            'singular_name' => _x('Document type', 'taxonomy singular name'),
            'search_items' =>  __('Search Document types'),
            'all_items' => __('All Document types'),
            'parent_item' => __('Parent Document type'),
            'parent_item_colon' => __('Parent Document type:'),
            'edit_item' => __('Edit Document type'),
            'update_item' => __('Update Document type'),
            'add_new_item' => __('Add New Document type'),
            'new_item_name' => __('New Document type Name'),
            'menu_name' => __('Document type'),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'document-type', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"

            //'query_var' => true,
            //'rewrite' => array( 'slug' => 'topic' ),
        ),
        'show_in_rest'      => true, // Gutenberg support
        'show_admin_column' => true,
    ));
}
add_action('init', 'add_custom_taxonomies', 0);


// Add filter dropdown for doc_type in document admin
add_action('restrict_manage_posts', 'add_doc_type_filter_dropdown');
function add_doc_type_filter_dropdown() {
    global $typenow;
    
    if ($typenow == 'document') {
        $selected = isset($_GET['doc_type_id']) ? $_GET['doc_type_id'] : '';
        $info_taxonomy = get_taxonomy('doc_type');
        wp_dropdown_categories(array(
            'show_option_all' => __("All {$info_taxonomy->label}"),
            'taxonomy' => 'doc_type',
            'name' => 'doc_type_id',
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
        ));
    }
}

// Handle the filter
add_filter('parse_query', 'filter_documents_by_doc_type');
function filter_documents_by_doc_type($query) {
    global $pagenow;
    
    // Only modify the query if we're in admin and it's the main query for the document post type
    if (!is_admin() || !$query->is_main_query() || $pagenow !== 'edit.php') {
        return;
    }
    
    $type = isset($_GET['post_type']) ? $_GET['post_type'] : 'document';
    
    if ($type == 'document' && isset($_GET['doc_type_id']) && $_GET['doc_type_id'] != '' && is_numeric($_GET['doc_type_id'])) {
        $query->query_vars['tax_query'] = array(
            array(
                'taxonomy' => 'doc_type',
                'field'    => 'term_id',
                'terms'    => intval($_GET['doc_type_id']),
            ),
        );
    }
}

