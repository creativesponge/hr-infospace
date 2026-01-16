<?php
// Add checkbox for open access to attachments
add_filter('cmb2_meta_boxes', 'cmb2_attachment_open_access_metabox');
function cmb2_attachment_open_access_metabox()
{
    global $prefix;

    $attachment = new_cmb2_box([
        'id'            => $prefix . 'attachment_open_access',
        'title'         => 'Access Settings',
        'object_types'  => ['attachment'],
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
    ]);

    $attachment->add_field([
        'id'        => $prefix . 'open_access',
        'name'      => 'Open Access',
        'desc'      => 'Allow public access to this file',
        'type'      => 'checkbox',
    ]);
    $attachment->add_field([
        'id'        => $prefix . 'downloadUrl',
        'name'      => 'Link for downloading the document',
      
        //'desc'      => 'Send an alert notification for this document',
        'type'      => 'title',
        'render_row_cb' => function($field_args, $field) {
            $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
            echo '<div class="cmb-row cmb-type-title">';
            echo '<div class="cmb-th"><label for="' . $field->id() . '">' . $field->args('name') . '</label></div>';
            echo '<div class="cmb-td">';
            echo '<a href="/download-document/' . $post_id . '">/download-document/' . $post_id . '</a>';
            echo '</div></div>';
        },
        
    ]);
}
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