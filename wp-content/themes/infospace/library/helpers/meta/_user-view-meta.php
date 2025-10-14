<?php

// Page links
add_filter('cmb2_meta_boxes', 'cmb2_page_link_metabox');
function cmb2_page_link_metabox()
{
    global $prefix;

    $page = new_cmb2_box([
        'id'            => $prefix . 'page_link_details',
        'title'         => 'Link details',
        'object_types'  => ['user_view'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);

    $page->add_field([
        'id'        => $prefix . 'page_link_url',
        'name'      => 'Link url',
        'desc'      => 'The link url including https://',
        'type'      => 'text',
       'column'    => true,
        'sortable'  => true,
    ]);

    

    $page->add_field([
        'id'        => $prefix . 'page_link_summary',
        'name'      => 'Summary',
        'desc'      => 'Short summary of the page',
        'type'      => 'textarea'
    ]);
    $page->add_field([
        'id'        => $prefix . 'page_link_keywords',
        'name'      => 'Keywords',
        'desc'      => 'Comma-separated keywords',
        'type'      => 'textarea'
    ]);
    $page->add_field([
        'id'        => $prefix . 'page_link_is_active',
        'name'      => 'Is Active',
        'desc'      => 'Check to activate this link',
        'type'      => 'checkbox',
        
    ]);

    // Add is active column to admin list view
    add_filter('manage_page_link_posts_columns', function($columns) use ($prefix) {
        $columns[$prefix . 'page_link_is_active'] = 'Active';
        return $columns;
    });

    // Make columns sortable
    add_filter('manage_edit-page_link_sortable_columns', function($columns) use ($prefix) {
        $columns[$prefix . 'page_link_url'] = $prefix . 'page_link_url';
        $columns[$prefix . 'page_link_is_active'] = $prefix . 'page_link_is_active';
        return $columns;
    });

    // Handle sorting
    add_action('pre_get_posts', function($query) use ($prefix) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $orderby = $query->get('orderby');
        
        if ($orderby == $prefix . 'page_link_url') {
            $query->set('meta_key', $prefix . 'page_link_url');
            $query->set('orderby', 'meta_value');
        }
        
        if ($orderby == $prefix . 'page_link_is_active') {
            $query->set('meta_key', $prefix . 'page_link_is_active');
            $query->set('orderby', 'meta_value');
        }
    });

    add_action('manage_page_link_posts_custom_column', function($column, $post_id) use ($prefix) {
        if ($column == $prefix . 'page_link_is_active') {
            $is_active = get_post_meta($post_id, $prefix . 'page_link_is_active', true);
            echo $is_active ? '✓' : '✗';
        }
    }, 10, 2);




    $pageSide = new_cmb2_box([
        'id'            => $prefix . 'page_link_details_side',
        'title'         => 'page info',
        'object_types'  => ['page_link'],
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
    ]);


    $pageSide->add_field([
        'id'        => $prefix . 'old_user_view_system_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text',
        'attributes' => [
            'readonly' => 'readonly',
        ]
    ]);

    $pageSide->add_field([
        'id'        => $prefix .'sort_order',
        'name'      => 'Sort Order',
        'desc'      => 'Order for sorting documents',
        'type'      => 'text_small',
        'attributes' => [
            'type' => 'number',
            'min'  => 0,
            'readonly' => 'readonly',
        ],
    ]);
}
