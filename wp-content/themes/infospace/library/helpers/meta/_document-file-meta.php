<?php

// Document files
/*
add_filter('cmb2_meta_boxes', 'cmb2_document_file_metabox');
function cmb2_document_file_metabox()
{
    global $prefix;

    $document = new_cmb2_box([
        'id'            => $prefix . 'document_file_details',
        'title'         => 'Document details',
        'object_types'  => ['document_file'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);
   

    $document->add_field([
        'id'        => $prefix . 'start_date',
        'name'      => 'Start Date',
        'desc'      => 'The date the document became effective',
        'type'      => 'text_date_timestamp',
         'column'    => true,
        'sortable'  => true,
    ]);

    $document->add_field([
        'id'        => $prefix . 'end_date',
        'name'      => 'End Date',
        'desc'      => 'The date the document is no longer valid',
        'type'      => 'text_date_timestamp',
         'column'    => true,
        'sortable'  => true,
    ]);

    
    $document->add_field([
        'id'        => $prefix . 'uploaded_file',
        'name'      => 'Upload File',
        'desc'      => 'Upload the document file',
        'type'      => 'file',
        'options'   => [
            'url' => false, // Hide the text input for the URL
        ],
         'column'    => true,
        'sortable'  => true,
    ]);

   

     $documentSide = new_cmb2_box([
        'id'            => $prefix .'document_file_details_side',
        'title'         => 'Document info',
        'object_types'  => ['document_file'],
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
    ]);

    
    $documentSide->add_field([
        'id'        => $prefix .'old_file_system_id',
        'name'      => 'Old System ID',
        'desc'      => 'ID from the old system',
        'type'      => 'text',
        'attributes' => [
            'readonly' => 'readonly',
        ]
    ]);
}
*/