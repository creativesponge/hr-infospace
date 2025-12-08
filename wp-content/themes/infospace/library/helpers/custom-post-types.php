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
        'supports'              => array('title','page-attributes','thumbnail'),
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
        'name'                  => 'User Profile',
        'singular_name'         => 'User Profile',
        'menu_name'             => 'User Profiles',
        'name_admin_bar'        => 'User Profile',
        'archives'              => 'User Profile List',
        'parent_item_colon'     => 'Parent User Profile:',
        'all_items'             => 'All User Profiles',
        'add_new_item'          => 'Add User Profile',
        'add_new'               => 'Add User Profile',
        'new_item'              => 'New User Profile',
        'edit_item'             => 'Edit User Profile',
        'update_item'           => 'Update User Profile',
        'view_item'             => 'View User Profile',
        'search_items'          => 'Search User Profile',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',
        'featured_image'        => 'Listing Image',
        'set_featured_image'    => 'Set listing image',
        'remove_featured_image' => 'Remove Profile image',
        'use_featured_image'    => 'Use as Profile image',
        'insert_into_item'      => 'Insert into User Profile',
        'uploaded_to_this_item' => 'Uploaded to this User Profile',
        'items_list'            => 'User Profile list',
        'items_list_navigation' => 'User Profile list navigation',
        'filter_items_list'     => 'Filter User Profile',
        
    );

    $args = array(
        'label'                 => 'User Profile',
        'description'           => 'User Profile item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-networking',
        'show_in_rest'             => true,
        'supports'              => array('title', 'page-attributes'),
        'hierarchical'          => true,
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
        'supports'              => array('title', 'page-attributes', 'editor', 'excerpt'),
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
        'exclude_from_search'   => false,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'rewrite'               => array('slug' => 'document', 'with_front' => false),
        'taxonomies'            => array(),
        'show_in_rest'          => true,
    );
    register_post_type('document', $args);
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
        'name'                  => 'Page Links',
        'singular_name'         => 'Page Link',
        'menu_name'             => 'Page Links',
        'name_admin_bar'        => 'Page Link',
        'archives'              => 'Page Link List',
        'parent_item_colon'     => 'Parent Page Link:',
        'all_items'             => 'All Page Links',
        'add_new_item'          => 'Add Page Link',
        'add_new'               => 'Add Page Link',
        'new_item'              => 'New Page Link',
        'edit_item'             => 'Edit Page Link',
        'singular_name'         => 'Page Link',
        'menu_name'             => 'Page Links',
        'name_admin_bar'        => 'Page Link',
        'archives'              => 'Page Link List',
        'parent_item_colon'     => 'Parent Page Link:',
        'all_items'             => 'All Page Links',
        'add_new_item'          => 'Add Page Link',
        'add_new'               => 'Add Page Link',
        'new_item'              => 'New Page Link',
        'edit_item'             => 'Edit Page Link',
        'update_item'           => 'Update Page Link',
        'view_item'             => 'View Page Link',
        'search_items'          => 'Search Page Links',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in bin',

        'insert_into_item'      => 'Insert into Page Link',
        'uploaded_to_this_item' => 'Uploaded to this Page Link',
        'items_list'            => 'Page Links list',
        'items_list_navigation' => 'Page Links list navigation',
        'filter_items_list'     => 'Filter Page Links',
    );

    $args = array(
        'label'                 => 'Page Link',
        'description'           => 'Page Link item',
        'labels'                => $labels,
        'menu_icon'             => 'dashicons-admin-links',
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
        'supports'              => array('title', 'page-attributes'),
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
        'supports'              => array('title', 'page-attributes', 'author'),
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
    // Add new "Team" taxonomy to Posts
    /*register_taxonomy('team', 'person', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => false,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x('Team', 'taxonomy general name'),
            'singular_name' => _x('Team', 'taxonomy singular name'),
            'search_items' =>  __('Search Team'),
            'all_items' => __('All Teams'),
            'parent_item' => __('Parent Team'),
            'parent_item_colon' => __('Parent Team:'),
            'edit_item' => __('Edit Team'),
            'update_item' => __('Update Team'),
            'add_new_item' => __('Add New Team'),
            'new_item_name' => __('New Team Name'),
            'menu_name' => __('Teams'),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'team', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"

            //'query_var' => true,
            //'rewrite' => array( 'slug' => 'topic' ),
        ),
        'show_in_rest'      => true, // Gutenberg support
        'show_admin_column' => true,
    ));
*/

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
