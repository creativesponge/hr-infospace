<?php // Import documents

// List all meta fields and their values for post ID 123
/*$post_id = 809;
$meta_fields = get_post_meta($post_id);

echo "<h3>Meta fields for Post ID: {$post_id}</h3>";
foreach ($meta_fields as $key => $value) {
    echo "<strong>{$key}:</strong> ";
    if (is_array($value) && count($value) === 1) {
        echo htmlspecialchars($value[0]);
    } elseif (is_array($value)) {
        echo '<pre>' . htmlspecialchars(print_r($value, true)) . '</pre>';
    } else {
        echo htmlspecialchars($value);
    }
    if ($key === 'theme_fieldsdocument_attached_files') {
        var_dump(unserialize($value[0]));
    }
    echo "<br>";
}
echo "<br><hr><br>";
*/
global $wpdb;
global $prefix;

// Get all existing old_system_id values
$existing_documents = get_posts(array(
    'post_type'      => 'document',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'fields'         => 'ids',
));

$existing_old_system_ids = array();
foreach ($existing_documents as $existing_doc_id) {
    $old_system_id = get_post_meta($existing_doc_id, $prefix . 'old_system_id', true);

    if ($old_system_id !== '') {
        $existing_old_system_ids[$old_system_id] = $existing_doc_id;
    }
}

// Get all existing old_system_id values for files
$existing_documents_files = get_posts(array(
    'post_type'      => 'document_file',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'fields'         => 'ids',
));

$existing_old_file_system_ids = array();
foreach ($existing_documents_files as $existing_doc_file_id) {
    $old_file_system_id = get_post_meta($existing_doc_file_id, $prefix . 'old_file_system_id', true);
    if ($old_file_system_id !== '') {
        $existing_old_file_system_ids[$old_file_system_id] =  $existing_doc_file_id;
    }
}

// Fetch all rows from the pages_document table
$result = $mysqli->query("SELECT * FROM pages_document");
$documents = [];

if ($result) {
    $documents = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

// Loop through the import and add or update
foreach ($documents as $doc) {
    $importText = "imported";
    if (!should_import_document()) {
        break;
    }

    // Map your table fields to post fields. Adjust these as needed.
    $post_data = array(
        'post_title'    => isset($doc['title']) ? $doc['title'] : '', // Change 'title' if needed

        'post_status'   => 'publish',
        'post_type'     => 'document',
        'menu_order'    => isset($doc['sort_order']) ? (int)$doc['sort_order'] : 0,
    );

    // Insert the post into the database

    // Skip if old_system_id already exists
    if (!array_key_exists($doc['id'], $existing_old_system_ids)) {
        $post_id = wp_insert_post($post_data);
    } else {
        $importText = "updated";
        $post_id = $existing_old_system_ids[$doc['id']];
    }

    // Add the fields
    if (isset($doc['id'])) {
        update_post_meta($post_id, $prefix . 'old_system_id', $doc['id']);
    }
    if (isset($doc['created'])) {
        update_post_meta($post_id, 'created', $doc['created']);
        // Set post_date and post_date_gmt
        $created_date = date('Y-m-d H:i:s', strtotime($doc['created']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_date'     => $created_date,
                'post_date_gmt' => get_gmt_from_date($created_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($doc['modified'])) {
        update_post_meta($post_id, 'modified', $doc['modified']);
        // Set post_modified and post_modified_gmt
        $modified_date = date('Y-m-d H:i:s', strtotime($doc['modified']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_modified'     => $modified_date,
                'post_modified_gmt' => get_gmt_from_date($modified_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($doc['type'])) {
        //update_post_meta($post_id, $prefix . 'type', $doc['type']);
        // Assign to custom taxonomy 'document_type'
        wp_set_object_terms($post_id, $doc['type'], 'doc_type', false);
    }
    if (isset($doc['keywords'])) {
        update_post_meta($post_id, $prefix . 'keywords', $doc['keywords']);
    }
    if (isset($doc['sort_order'])) {
        update_post_meta($post_id, $prefix . 'sort_order', $doc['sort_order']);
    }
    if (isset($doc['summary'])) {
        update_post_meta($post_id, $prefix . 'summary', $doc['summary']);
    }
    if (isset($doc['is_new'])) {
        $is_new = strtotime($doc['is_new']);
        update_post_meta($post_id, $prefix . 'is_new', $is_new !== false ? $is_new : $doc['is_new']);
    }
    if (isset($doc['is_updated'])) {
        $is_updated = strtotime($doc['is_updated']);
        update_post_meta($post_id, $prefix . 'is_updated', $is_updated !== false ? $is_updated : $doc['is_updated']);
    }

    if (is_wp_error($post_id)) {
        echo 'Error importing document: ' . $post_data['post_title'] . ' - ' . $post_id->get_error_message();
    } else {
        echo 'Successfully ' . $importText . ' document: ' . $post_data['post_title'] . ' (Post ID: ' . $post_id . ')<br>';
    }


    // Store the ID to be used later to add the document files
    $doc_post_id = $post_id;





    //Import related doc files repeatable field group
    // Fetch all rows from the pages_documentfile table
    $resultFile = $mysqli->query("SELECT * FROM pages_documentfile WHERE document_id = " . intval($doc['id']));
    $documentsFile = [];

    if ($resultFile) {
        $documentsFile = $resultFile->fetch_all(MYSQLI_ASSOC);
        $resultFile->free();
    }

    foreach ($documentsFile as $docFile) {

        $fileName = isset($docFile['file']) ? str_replace('documents/', '', $docFile['file']) : ''; // Change 'title' if needed
        // Add to CMB2 repeatable field group
        $existing_doc_files = get_post_meta($doc_post_id, $prefix . 'document_files', true);
        if (!is_array($existing_doc_files)) {
            $existing_doc_files = array();
        }
        // Skip if old_system_id already exists
        // Check if this doc file ID already exists in the repeatable field
        $file_exists = false;
        foreach ($existing_doc_files as $existing_file) {
            if (
                isset($existing_file[$prefix . 'old_system_doc_file_id']) &&
                $existing_file[$prefix . 'old_system_doc_file_id'] == $docFile['id']
            ) {
                $file_exists = true;
                break;
            }
        }
       
        if ($file_exists) {
            continue;
        }

        // Import the file
        // get the actual file path
        $file_path = isset($docFile['file']) ? $docFile['file'] : '';

        // Download and import the file
        if (!empty($file_path)) {
            // Get the parent directory and create if it doesn't exist
            //$parent_dir = dirname(__DIR__) . '/' . $file_path;


            $file_url =  dirname(__DIR__) . '/' . $file_path; // Replace with your actual base URL
            //var_dump($file_url);
            // Download the file
            if (file_exists($file_url)) {
                $file_name = basename($file_path);

                // Get upload directory
                $upload_dir = wp_upload_dir();

                // Create file path
                $file_path_local = $upload_dir['path'] . '/' . $file_name;

                // Copy file to uploads directory
                if (copy($file_url, $file_path_local)) {

                    // Check if file with same name already exists
                    $existing_attachment = get_posts(array(
                        'post_type' => 'attachment',
                        'meta_query' => array(
                            array(
                                'key' => '_wp_attached_file',
                                'value' => basename($file_path_local),
                                'compare' => 'LIKE'
                            )
                        ),
                        'posts_per_page' => 1
                    ));

                    if (empty($existing_attachment)) {
                        // Prepare attachment data
                        $attachment = array(
                            'post_mime_type' => wp_check_filetype($file_name)['type'],
                            'post_title'     => sanitize_file_name($file_name),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );

                        // Insert attachment
                        $attachment_id = wp_insert_attachment($attachment, $file_path_local);

                        if (!is_wp_error($attachment_id)) {

                            // Generate attachment metadata
                            require_once(ABSPATH . 'wp-admin/includes/image.php');
                            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path_local);
                            wp_update_attachment_metadata($attachment_id, $attachment_data);

                            // Add to custom field
                            // Store attachment ID for CMB2 file field compatibility
                            $file_url = wp_get_attachment_url($attachment_id);
                            echo "<br>File URL: " . $file_url;
                            echo "<br>Post ID: " . $post_id;
                            echo "<br>Attachment ID: " . $attachment_id;

                          $uploadedFile = $file_url;
                          $uploadedFileId = $attachment_id;
                           // update_post_meta($post_id, $prefix . 'doc_uploaded_file_id', $attachment_id);
                            echo 'Successfully uploaded file: ' . $file_name . '<br>';
                        }
                    } else {
                        // File already exists, use existing attachment
                        // Add to custom field
                        $file_url = wp_get_attachment_url($existing_attachment[0]->ID);
                        //update_post_meta($post_id, $prefix . 'doc_uploaded_file', $file_url);
                        //update_post_meta($post_id, $prefix . 'doc_uploaded_file_id', $existing_attachment[0]->ID);
                         $uploadedFile = $file_url;
                          $uploadedFileId = $existing_attachment[0]->ID;

                        echo 'File already exists, using existing: ' . $file_name . '<br>';
                    }


                    // Add new file entry to repeatable field group
                    $existing_doc_files[] = array(
                        $prefix . 'filename' => $fileName,
                        $prefix . 'old_system_doc_file_id' => isset($docFile['id']) ? $docFile['id'] : '',
                        $prefix . 'start_date' => isset($docFile['start_date']) ? strtotime($docFile['start_date']) : '',
                        $prefix . 'end_date' => isset($docFile['end_date']) ? strtotime($docFile['end_date']) : '',
                        $prefix . 'doc_uploaded_file' => isset($uploadedFile) ? $uploadedFile : '',
                        $prefix . 'doc_uploaded_file_id' => isset($uploadedFileId) ? $uploadedFileId : '',
                    );
                }
            }

            // Attach the document file to the original document

        }


        // Update the repeatable field group
        update_post_meta($doc_post_id, $prefix . 'document_files', $existing_doc_files);
        /*
        // Map your table fields to post fields. Adjust these as needed.
        $post_data = array(
            'post_title'    => isset($docFile['file']) ? str_replace('documents/', '', $docFile['file']) : '', // Change 'title' if needed
            'post_status'   => 'publish',
            'post_type'     => 'document_file',
        );

        // Insert the post into the database
      
        // Skip if old_system_id already exists
        if (!array_key_exists($docFile['id'], $existing_old_file_system_ids)) {
            $post_id = wp_insert_post($post_data);
        } else {
            $importText = "updated";
            $post_id = $existing_old_file_system_ids[$docFile['id']];
        }

        // Add the fields
        if (isset($docFile['id'])) {
            update_post_meta($post_id, $prefix . 'old_system_doc_file_id', $docFile['id']);
        }
        
        if (isset($docFile['start_date'])) {
            $start_date = strtotime($docFile['start_date']);
            update_post_meta($post_id, $prefix . 'start_date', $start_date !== false ? $start_date : $docFile['start_date']);
        }
        if (isset($docFile['end_date'])) {
            $end_date = strtotime($docFile['end_date']);
            update_post_meta($post_id, $prefix . 'end_date', $end_date !== false ? $end_date : $docFile['end_date']);
        }

        // Import the file
        // get the actual file path
        $file_path = isset($docFile['file']) ? $docFile['file'] : '';

        // Download and import the file
        if (!empty($file_path)) {
            // Get the parent directory and create if it doesn't exist
            //$parent_dir = dirname(__DIR__) . '/' . $file_path;


            $file_url =  dirname(__DIR__) . '/' . $file_path; // Replace with your actual base URL
            //var_dump($file_url);
            // Download the file
            if (file_exists($file_url)) {
                $file_name = basename($file_path);

                // Get upload directory
                $upload_dir = wp_upload_dir();

                // Create file path
                $file_path_local = $upload_dir['path'] . '/' . $file_name;

                // Copy file to uploads directory
                if (copy($file_url, $file_path_local)) {

                    // Check if file with same name already exists
                    $existing_attachment = get_posts(array(
                        'post_type' => 'attachment',
                        'meta_query' => array(
                            array(
                                'key' => '_wp_attached_file',
                                'value' => basename($file_path_local),
                                'compare' => 'LIKE'
                            )
                        ),
                        'posts_per_page' => 1
                    ));

                    if (empty($existing_attachment)) {
                        // Prepare attachment data
                        $attachment = array(
                            'post_mime_type' => wp_check_filetype($file_name)['type'],
                            'post_title'     => sanitize_file_name($file_name),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );

                        // Insert attachment
                        $attachment_id = wp_insert_attachment($attachment, $file_path_local);

                        if (!is_wp_error($attachment_id)) {

                            // Generate attachment metadata
                            require_once(ABSPATH . 'wp-admin/includes/image.php');
                            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path_local);
                            wp_update_attachment_metadata($attachment_id, $attachment_data);

                            // Add to custom field
                            // Store attachment ID for CMB2 file field compatibility
                            $file_url = wp_get_attachment_url($attachment_id);
                            echo "<br>File URL: " . $file_url;
                            echo "<br>Post ID: " . $post_id;
                            echo "<br>Attachment ID: " . $attachment_id;

                            update_post_meta($post_id, $prefix . 'uploaded_file', $file_url);
                            update_post_meta($post_id, $prefix . 'uploaded_file_id', $attachment_id);
                            echo 'Successfully uploaded file: ' . $file_name . '<br>';
                        }
                    } else {
                        // File already exists, use existing attachment
                        // Add to custom field
                        $file_url = wp_get_attachment_url($existing_attachment[0]->ID);
                        update_post_meta($post_id, $prefix . 'uploaded_file', $file_url);
                        update_post_meta($post_id, $prefix . 'uploaded_file_id', $existing_attachment[0]->ID);

                        echo 'File already exists, using existing: ' . $file_name . '<br>';
                    }
                }
            }
            
            // Attach the document file to the original document
            // Get existing attached files array
            $existing_files = get_post_meta($doc_post_id, $prefix . 'document_attached_files', true);
            if (!is_array($existing_files)) {
                $existing_files = array();
            }

            // Add new file ID to array
            if (!in_array($post_id, $existing_files)) {
                $existing_files[] = $post_id;
            }
   

            // Update with array of file IDs
            update_post_meta($doc_post_id, $prefix . 'document_attached_files', $existing_files);
        }
*/
        if (is_wp_error($post_id)) {
            echo 'Error importing document: ' . $post_data['post_title'] . ' - ' . $post_id->get_error_message();
        } else {
            echo 'Successfully ' . $importText . ' document file: ' . $post_data['post_title'] . ' (Post ID: ' . $post_id . ')<br>';
        }
    }



    //Import related doc files as separate post type DELETE IF NOT NEEDED
    // Fetch all rows from the pages_documentfile table
    $resultFile = $mysqli->query("SELECT * FROM pages_documentfile WHERE document_id = " . intval($doc['id']));
    $documentsFile = [];

    if ($resultFile) {
        $documentsFile = $resultFile->fetch_all(MYSQLI_ASSOC);
        $resultFile->free();
    }

    foreach ($documentsFile as $docFile) {

        // Map your table fields to post fields. Adjust these as needed.
        $post_data = array(
            'post_title'    => isset($docFile['file']) ? str_replace('documents/', '', $docFile['file']) : '', // Change 'title' if needed
            'post_status'   => 'publish',
            'post_type'     => 'document_file',
        );

        // Insert the post into the database

        // Skip if old_system_id already exists
        if (!array_key_exists($docFile['id'], $existing_old_file_system_ids)) {
            $post_id = wp_insert_post($post_data);
        } else {
            $importText = "updated";
            $post_id = $existing_old_file_system_ids[$docFile['id']];
        }

        // Add the fields
        if (isset($docFile['id'])) {
            update_post_meta($post_id, $prefix . 'old_file_system_id', $docFile['id']);
        }
        if (isset($docFile['created'])) {
            update_post_meta($post_id, 'created', $docFile['created']);
            // Set post_date and post_date_gmt
            $created_date = date('Y-m-d H:i:s', strtotime($docFile['created']));
            $wpdb->update(
                $wpdb->posts,
                array(
                    'post_date'     => $created_date,
                    'post_date_gmt' => get_gmt_from_date($created_date),
                ),
                array('ID' => $post_id)
            );
        }
        if (isset($doc['modified'])) {
            update_post_meta($post_id, 'modified', $docFile['modified']);
            // Set post_modified and post_modified_gmt
            $modified_date = date('Y-m-d H:i:s', strtotime($docFile['modified']));
            $wpdb->update(
                $wpdb->posts,
                array(
                    'post_modified'     => $modified_date,
                    'post_modified_gmt' => get_gmt_from_date($modified_date),
                ),
                array('ID' => $post_id)
            );
        }

        if (isset($docFile['start_date'])) {
            $start_date = strtotime($docFile['start_date']);
            update_post_meta($post_id, $prefix . 'start_date', $start_date !== false ? $start_date : $docFile['start_date']);
        }
        if (isset($docFile['end_date'])) {
            $end_date = strtotime($docFile['end_date']);
            update_post_meta($post_id, $prefix . 'end_date', $end_date !== false ? $end_date : $docFile['end_date']);
        }

        // Import the file
        // get the actual file path
        $file_path = isset($docFile['file']) ? $docFile['file'] : '';

        // Download and import the file
        if (!empty($file_path)) {
            // Get the parent directory and create if it doesn't exist
            //$parent_dir = dirname(__DIR__) . '/' . $file_path;


            $file_url =  dirname(__DIR__) . '/' . $file_path; // Replace with your actual base URL
            //var_dump($file_url);
            // Download the file
            if (file_exists($file_url)) {
                $file_name = basename($file_path);

                // Get upload directory
                $upload_dir = wp_upload_dir();

                // Create file path
                $file_path_local = $upload_dir['path'] . '/' . $file_name;

                // Copy file to uploads directory
                if (copy($file_url, $file_path_local)) {

                    // Check if file with same name already exists
                    $existing_attachment = get_posts(array(
                        'post_type' => 'attachment',
                        'meta_query' => array(
                            array(
                                'key' => '_wp_attached_file',
                                'value' => basename($file_path_local),
                                'compare' => 'LIKE'
                            )
                        ),
                        'posts_per_page' => 1
                    ));

                    if (empty($existing_attachment)) {
                        // Prepare attachment data
                        $attachment = array(
                            'post_mime_type' => wp_check_filetype($file_name)['type'],
                            'post_title'     => sanitize_file_name($file_name),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );

                        // Insert attachment
                        $attachment_id = wp_insert_attachment($attachment, $file_path_local);

                        if (!is_wp_error($attachment_id)) {

                            // Generate attachment metadata
                            require_once(ABSPATH . 'wp-admin/includes/image.php');
                            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path_local);
                            wp_update_attachment_metadata($attachment_id, $attachment_data);

                            // Add to custom field
                            // Store attachment ID for CMB2 file field compatibility
                            $file_url = wp_get_attachment_url($attachment_id);
                            echo "<br>File URL: " . $file_url;
                            echo "<br>Post ID: " . $post_id;
                            echo "<br>Attachment ID: " . $attachment_id;

                            update_post_meta($post_id, $prefix . 'uploaded_file', $file_url);
                            update_post_meta($post_id, $prefix . 'uploaded_file_id', $attachment_id);
                            echo 'Successfully uploaded file: ' . $file_name . '<br>';
                        }
                    } else {
                        // File already exists, use existing attachment
                        // Add to custom field
                        $file_url = wp_get_attachment_url($existing_attachment[0]->ID);
                        update_post_meta($post_id, $prefix . 'uploaded_file', $file_url);
                        update_post_meta($post_id, $prefix . 'uploaded_file_id', $existing_attachment[0]->ID);

                        echo 'File already exists, using existing: ' . $file_name . '<br>';
                    }
                }
            }

            // Attach the document file to the original document
            // Get existing attached files array
            $existing_files = get_post_meta($doc_post_id, $prefix . 'document_attached_files', true);
            if (!is_array($existing_files)) {
                $existing_files = array();
            }

            // Add new file ID to array
            if (!in_array($post_id, $existing_files)) {
                $existing_files[] = $post_id;
            }


            // Update with array of file IDs
            update_post_meta($doc_post_id, $prefix . 'document_attached_files', $existing_files);
        }

        if (is_wp_error($post_id)) {
            echo 'Error importing document: ' . $post_data['post_title'] . ' - ' . $post_id->get_error_message();
        } else {
            echo 'Successfully ' . $importText . ' document file: ' . $post_data['post_title'] . ' (Post ID: ' . $post_id . ')<br>';
        }
    }
}
