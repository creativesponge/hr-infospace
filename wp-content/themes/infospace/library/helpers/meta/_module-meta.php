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
        'id'      => $prefix .'module_attached_pages',
        'type'    => 'select',
        'column'  => true,
        'show_option_none' => __( 'Select a resource...', 'hrinfospace' ),
        'options_cb' => function() {
            $resources = get_posts(array(
                'post_type' => 'resource_page',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            ));
            $options = array();
            foreach ($resources as $resource) {
                $options[$resource->ID] = $resource->post_title;
            }
            return $options;
        },
    ) );

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

    


    

}
