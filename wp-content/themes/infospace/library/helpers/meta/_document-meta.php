<?php

// Documents
add_filter('cmb2_meta_boxes', 'cmb2_document_metabox');
function cmb2_document_metabox() {
    global $prefix;
    
    $document = new_cmb2_box([
        'id'            => $prefix .'document_details',
        'title'         => 'Document details',
        'object_types'  => ['document'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);
  $document->add_field([
        'id'        => $prefix .'summary',
        'name'      => 'Summary',
        'desc'      => 'Short summary of the document',
        'type'      => 'textarea'
    ]);
    $document->add_field([
        'id'        => $prefix .'keywords',
        'name'      => 'Keywords',
        'desc'      => 'Comma-separated keywords',
        'type'      => 'textarea'
    ]);
    /*$document->add_field([
        'id'        => $prefix .'related_documents',
        'name'      => 'Related Documents',
        'desc'      => 'Select one or more related documents',
        'type'      => 'post_search_text',
        'post_type' => 'document',
        'multiple'  => true,
        'select_type' => 'select',
        'attributes'  => [
            'style' => 'width:100%'
        ],
    ]);*/
// Not used as they are set in the resource page.
    /*$document->add_field( array(
		'name'    => __( 'Resource to show on (Not used as they are set in the resource page)', 'hrinfospace' ),
		'desc'    => __( 'Drag a resource from the left column to the right column to attach them to this document.<br />You may rearrange the order of the posts in the right column by dragging and dropping.', 'hrinfospace' ),
		'id'      => $prefix .'document_attached_pages',
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
	) );*/

    /*$document->add_field( array(
		'name'    => __( 'Attached Files (NO LONGER USED)', 'hrinfospace' ),
		'desc'    => __( 'Drag files from the left column to the right column to attach them to this document.<br />You may rearrange the order of the files in the right column by dragging and dropping.', 'hrinfospace' ),
		'id'      => $prefix .'document_attached_files',
		'type'    => 'custom_attached_posts',
		'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
		'options' => array(
			'show_thumbnails' => true, // Show thumbnails on the left
			'filter_boxes'    => true, // Show a text box for filtering the results
			'query_args'      => array(
				'posts_per_page' => 10,
				'post_type'      => 'document_file',
			), // override the get_posts args
		),
        'attributes' => [
            'disabled' => 'disabled',
            'style' => 'color: #999; background-color: #f5f5f5;'
        ],
	) );*/

    
    $document->add_field([
        'id'          => $prefix . 'document_files',
        'type'        => 'group',
        'name'        => 'Document files',
        'description' => 'Add different versions of this document',
        'options'     => [
            'group_title'   => 'File {#}',
            'add_button'    => 'Add Another File',
            'remove_button' => 'Remove File',
            'sortable'      => true,
        ],
    ]);
    $document->add_group_field($prefix . 'document_files', [
        'id'        => $prefix . 'filename',
        'name'      => 'Filename',
        'desc'      => 'Name for this document file',
        'type'      => 'text',
        'column'    => true,
        'sortable'  => true,
    ]);

    $document->add_group_field($prefix . 'document_files', [
        'id'        => $prefix . 'start_date',
        'name'      => 'Start Date',
        'desc'      => 'The date the document became effective',
        'type'      => 'text_date_timestamp',
         'column'    => true,
        'sortable'  => true,
    ]);

    $document->add_group_field($prefix . 'document_files', [
        'id'        => $prefix . 'end_date',
        'name'      => 'End Date',
        'desc'      => 'The date the document is no longer valid',
        'type'      => 'text_date_timestamp',
         'column'    => true,
        'sortable'  => true,
    ]);

    $document->add_field([
        'id'        => $prefix . 'document_is_active',
        'name'      => 'Has active files',
        'desc'      => 'Activated by attached document files',
        'type'      => 'checkbox',
        'attributes' => [
            'style' => 'display: none;'
            //Uncomment once live 'readonly' => 'readonly',
        ],
    ]);

    // Add is active column to admin list view
    add_filter('manage_document_posts_columns', function($columns) use ($prefix) {
        $columns[$prefix . 'document_is_active'] = 'Active';
         $columns[$prefix . 'document_url'] = 'Embed url';
        return $columns;
    });

    // Make columns sortable
    add_filter('manage_edit-document_sortable_columns', function($columns) use ($prefix) {
        $columns[$prefix . 'document_url'] = $prefix . 'document_url';
        $columns[$prefix . 'document_is_active'] = $prefix . 'document_is_active';
        return $columns;
    });

    // Handle sorting
    add_action('pre_get_posts', function($query) use ($prefix) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $orderby = $query->get('orderby');
        
        if ($orderby == $prefix . 'document_is_active') {
            $query->set('meta_key', $prefix . 'document_is_active');
            $query->set('orderby', 'meta_value');
        }
    });

    add_action('manage_document_posts_custom_column', function($column, $post_id) use ($prefix) {
        if ($column == $prefix . 'document_is_active') {
            $is_active = get_post_meta($post_id, $prefix . 'document_is_active', true);
            echo $is_active == 'on' ? '✓' : '✗';
        }
        if ($column == $prefix . 'document_url') {
            $attachedFilesArray = get_post_meta($post_id, $prefix . 'document_files', true);
            $document_url  = isset($attachedFilesArray[0][$prefix . 'doc_uploaded_file_id']) ? get_site_url() . '/download-document/'. $attachedFilesArray[0][$prefix . 'doc_uploaded_file_id'] : '';
            echo esc_url($document_url);
        }
    }, 10, 2);

    $document->add_group_field($prefix . 'document_files', [
        'id'        => $prefix . 'doc_uploaded_file',
        'name'      => 'Upload File',
        'desc'      => 'Upload the document file' ,
        'type'      => 'file',
       
        'options'   => [
            'url' => false, // Hide the text input for the URL
        ],
         'column'    => true,
        'sortable'  => true,
        
    ]);

    $document->add_group_field($prefix . 'document_files', [
        'id'        => $prefix .'old_system_doc_file_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text_small',
        'attributes' => [
            //Uncomment once live 'readonly' => 'readonly',
            'style' => 'display: none;'
        ],
    ]);

    $documentSide = new_cmb2_box([
        'id'            => $prefix .'document_details_side',
        'title'         => 'Document info',
        'object_types'  => ['document'],
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
    ]);

    $documentSide->add_field([
        'id'        => $prefix .'is_new',
        'name'      => 'Is New?',
        'desc'      => 'Mark as new document',
        'type'      => 'text_date_timestamp',
         'column'    => true,
        'sortable'  => true,
    ]);
    $documentSide->add_field([
        'id'        => $prefix .'is_updated',
        'name'      => 'Is Updated?',
        'desc'      => 'Mark as updated document',
        'type'      => 'text_date_timestamp',
         'column'    => true,
        'sortable'  => true,
    ]);
    $documentSide->add_field([ //DELETE WHEN NOT NEEDED
        'id'        => $prefix .'old_system_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text_small',
        'attributes' => [
            //Uncomment once live 'readonly' => 'readonly',
             'style' => 'display: none;'
        ]
    ]);


    $documentSide->add_field([
        'id'        => $prefix .'sort_order',
        'name'      => 'Sort Order (From old system)',
        'desc'      => 'Order for sorting documents',
        'type'      => 'text_small',
        'attributes' => [
            'type' => 'number',
            'min'  => 0,
            'style' => 'display: none;'
            //Uncomment once live 'readonly' => 'readonly',
            
        ],
    ]);

}
