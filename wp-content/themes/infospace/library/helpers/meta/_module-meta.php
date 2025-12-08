<?php

// Modules
add_filter('cmb2_meta_boxes', 'cmb2_module_metabox');
function cmb2_module_metabox() {
    global $prefix;
    
    $module = new_cmb2_box([
        'id'            => $prefix .'module_details',
        'title'         => 'Module details',
        'object_types'  => ['module'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);


    $module->add_field( array(
        'name'    => __( 'Resource to start access from', 'hrinfospace' ),
        'desc'    => __( 'Select a single resource as a starting point for this module.', 'hrinfospace' ),
        'id'      => $prefix .'module_attached_resources',
        'type'    => 'select',
        'column'  => true,
        'show_option_none' => __( 'Select a resource...', 'hrinfospace' ),
        'options_cb' => function() {
            $resources = get_posts(array(
                'post_type' => 'resource_page',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order'   => 'ASC',
            ));
            $options = array();
            foreach ($resources as $resource) {
                $options[$resource->ID] = $resource->post_title;
            }
            return $options;
        },
    ) );
    $module->add_field( array(
        'name' => __( 'Description', 'hrinfospace' ),
        'desc' => __( 'Enter a description for this module.', 'hrinfospace' ),
        'id'   => $prefix . 'module_description',
        'type' => 'textarea_small',
    ) );

    

    $module->add_field( array(
        'name' => __( 'Module Color', 'hrinfospace' ),
        'desc' => __( 'Choose a color for this module.', 'hrinfospace' ),
        'id'   => $prefix . 'module_color',
        'type' => 'colorpicker',
    ) );
    $module->add_field( array(
        'name' => __( 'Phone Number', 'hrinfospace' ),
        'desc' => __( 'Enter a contact phone number for this module.', 'hrinfospace' ),
        'id'   => $prefix . 'module_phone_number',
        'type' => 'text',
    ) );
    $module->add_field( array(
        'name' => __( 'Email address', 'hrinfospace' ),
        'desc' => __( 'Enter a contact email address for this module.', 'hrinfospace' ),
        'id'   => $prefix . 'module_email_address',
        'type' => 'text',
    ) );
    $module->add_field( array(
        'name' => __( 'Banner Image', 'hrinfospace' ),
        'desc' => __( 'Upload a banner image for this module.', 'hrinfospace' ),
        'id'   => $prefix . 'banner_image',
        'type' => 'file',
        'options' => array(
            'url' => false,
        ),
        'text'    => array(
            'add_upload_file_text' => __( 'Add Banner Image', 'hrinfospace' )
        ),
        'query_args' => array(
            'type' => array(
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
            ),
        ),
        'preview_size' => 'medium',
    ) );

    $module->add_field( array(
        'name' => __( 'mobule listing Image', 'hrinfospace' ),
        'desc' => __( 'Upload a banner image for this module.', 'hrinfospace' ),
        'id'   => $prefix . 'listing_image_mobile',
        'type' => 'file',
        'options' => array(
            'url' => false,
        ),
        'text'    => array(
            'add_upload_file_text' => __( 'Add MobileListing Image', 'hrinfospace' )
        ),
        'query_args' => array(
            'type' => array(
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
            ),
        ),
        'preview_size' => 'medium',
    ) );
/*
    $module->add_field( array(
        'name'    => __( 'Select Pages', 'hrinfospace' ),
        'desc'    => __( 'Choose pages from the dropdown list to attach to this module.', 'hrinfospace' ),
        'id'      => $prefix .'module_selected_pages',
        'type'    => 'select',
        'show_option_none' => __( 'Select a page...', 'hrinfospace' ),
        'options_cb' => function() {
            $pages = get_pages();
            $options = array();
            foreach ($pages as $page) {
                $options[$page->ID] = $page->post_title;
            }
            return $options;
        },
        'column'  => true,
    ) );
*/
    


    

}
