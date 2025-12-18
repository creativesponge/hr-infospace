<?php

// Posts
add_filter('cmb2_meta_boxes', 'cmb2_page_page_metabox');
function cmb2_page_page_metabox()
{
    global $prefix;

    $post = new_cmb2_box([
        'id'            => $prefix . 'page_page_details',
        'title'         => 'Page styling',
        'object_types'  => ['page'],
        'context'       => 'side',
        'priority'      => 'high',
        'show_names'    => true,
    ]);
    
    $post->add_field([
        'id'     => $prefix . 'grey_footer',
        'name'   => 'Grey footer',
        'desc'   => 'Change the page footer to grey',
        'type'   => 'checkbox',
        
        
    ]);
}