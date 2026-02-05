<?php // Import News


/*

$post_id = 826;
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
    if ($key === 'theme_fieldspost_attached_links') {
        var_dump(unserialize($value[0]));
    }
    echo "<br>";
}
echo "<br><hr><br>";
*/

global $wpdb;
global $prefix;

// Get all existing old_newsletter_system_id values
$existing_newsletter = get_posts(array(
    'post_type'      => 'newsletter',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'fields'         => 'ids',
));

$existing_old_newsletter_system_ids = array();
foreach ($existing_newsletter as $existing_newsletter_id) {
    $old_newsletter_system_id = get_post_meta($existing_newsletter_id, $prefix . 'old_newsletter_system_id', true);

    if ($old_newsletter_system_id !== '') {
        $existing_old_newsletter_system_ids[$old_newsletter_system_id] = $existing_newsletter_id;
    }
}

// Fetch all rows from the post_page table
$resultFile = $mysqli->query("SELECT * FROM news_newsletter");
$newsletterData = [];

if ($resultFile) {
    $newsletterData = $resultFile->fetch_all(MYSQLI_ASSOC);
    $resultFile->free();
}

foreach ($newsletterData as $newsletterRecord) {
    $importText = "imported";
    if (!should_import_document()) {
        break;
    }


    // Map your table fields to post fields. Adjust these as needed.
    $post_data = array(
        'post_title'    => isset($newsletterRecord['title']) ? $newsletterRecord['title'] : '', // Change 'title' if needed
        'post_status'   => 'publish',
        'post_type'     => 'newsletter',
        //'post_content'  => isset($newsletterRecord['content']) ? $newsletterRecord['content'] : '',
        //'post_name'     => isset($newsletterRecord['slug']) ? $newsletterRecord['slug'] : '',

        //'post_excerpt'  => isset($newsletterRecord['summary']) ? $newsletterRecord['summary'] : '',
    );

    // Insert the post into the database

    // Skip if old_newsletter_system_id already exists
    if (!array_key_exists($newsletterRecord['id'], $existing_old_newsletter_system_ids)) {
        $post_id = wp_insert_post($post_data);
    } else {
        $importText = "updated";
        $post_id = $existing_old_newsletter_system_ids[$newsletterRecord['id']];
    }

    // Add the fields
    if (isset($newsletterRecord['id'])) {
        update_post_meta($post_id, $prefix . 'old_newsletter_system_id', $newsletterRecord['id']);
    }
    if (isset($newsletterRecord['created'])) {
        update_post_meta($post_id, 'created', $newsletterRecord['created']);
        // Set post_date and post_date_gmt
        $created_date = date('Y-m-d H:i:s', strtotime($newsletterRecord['created']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_date'     => $created_date,
                'post_date_gmt' => get_gmt_from_date($created_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($newsletterRecord['modified'])) {
        update_post_meta($post_id, 'modified', $newsletterRecord['modified']);
        // Set post_modified and post_modified_gmt
        $modified_date = date('Y-m-d H:i:s', strtotime($newsletterRecord['modified']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_modified'     => $modified_date,
                'post_modified_gmt' => get_gmt_from_date($modified_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($newsletterRecord['date'])) {
        $start_date = strtotime($newsletterRecord['date']);
        update_post_meta($post_id, $prefix . 'newsletter_date', $start_date !== false ? $start_date : $newsletterRecord['start_date']);
    }
    if (isset($newsletterRecord['start_date'])) {
        $start_date = strtotime($newsletterRecord['start_date']);
        update_post_meta($post_id, $prefix . 'newsletter_start_date', $start_date !== false ? $start_date : $newsletterRecord['start_date']);
    }
    if (isset($newsletterRecord['end_date'])) {
        $end_date = strtotime($newsletterRecord['end_date']);
        update_post_meta($post_id, $prefix . 'newsletter_end_date', $end_date !== false ? $end_date : $newsletterRecord['end_date']);
    }

    if (isset($newsletterRecord['summary'])) {
        update_post_meta($post_id, $prefix . 'post_summary', $newsletterRecord['summary']);
    }


    if (is_wp_error($post_id)) {
        echo 'Error importing newsletter: ' . $post_data['post_title'] . ' - ' . $post_id->get_error_message();
    } else {
        echo 'Successfully ' . $importText . ' newsletter: ' . $post_data['post_title'] . ' (Post ID: ' . $post_id . ')<br>';
    }

    // Import the file
    // get the actual file path
    $file_path = isset($newsletterRecord['newsletter']) ? $newsletterRecord['newsletter'] : '';

    // Download and import the file
    if (!empty($file_path)) {
        // Get the parent directory and create if it doesn't exist
        //$parent_dir = dirname(__DIR__) . '/' . $file_path;


        $file_url =  dirname(__DIR__) . '/' . $file_path; // Replace with your actual base URL
      // var_dump($file_url);
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

                        update_post_meta($post_id, $prefix . 'newsletter_file', $file_url);
                        update_post_meta($post_id, $prefix . 'newsletter_file_id', $attachment_id);
                        echo 'Successfully uploaded file: ' . $file_name . '<br>';
                    }
                } else {
                    // File already exists, use existing attachment
                    // Add to custom field
                    $file_url = wp_get_attachment_url($existing_attachment[0]->ID);
                    update_post_meta($post_id, $prefix . 'newsletter_file', $file_url);
                    update_post_meta($post_id, $prefix . 'newsletter_file_id', $existing_attachment[0]->ID);

                    echo 'File already exists, using existing: ' . $file_name . '<br>';
                }
            }
        }

    }


    
        //Import related resources
    // Fetch all rows from the news_newspage table
    $resultResource = $mysqli->query("SELECT * FROM news_newsletterpage WHERE newsletter_id = " . intval($newsletterRecord['id']));
    $relatedResources = [];

    if ($resultResource) {
        $relatedResources = $resultResource->fetch_all(MYSQLI_ASSOC);
        $resultResource->free();
    }

    foreach ($relatedResources as $relatedResource) {

        $relatedResourcesId = $relatedResource['page_id'];

        // Get the document post by old_system_id
        $post_posts = get_posts(array(
            'post_type'      => 'resource_page',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'meta_query'     => array(
                array(
                    'key'   => $prefix . 'old_resource_system_id',
                    'value' => $relatedResourcesId,
                    'compare' => '='
                )
            )
        ));

        $post_post_id = !empty($post_posts) ?  $post_posts[0]->ID : null;

        if ($post_post_id) {

            // Attach the resource to the post
            // Get existing attached resources array
            $existingResources = get_post_meta($post_id, $prefix . 'newsletter_attached_resource_pages', true);
            if (!is_array($existingResources)) {
                $existingResources = array();
            }

            // Add new ID to array
            if (!in_array($post_post_id, $existingResources)) {
                $existingResources[] = $post_post_id;
            }

            // Update with array of IDs
            update_post_meta($post_id, $prefix . 'newsletter_attached_resource_pages', $existingResources);
            echo 'added the related resource: ' . $post_posts[0]->post_title . ' - ' . $post_post_id . ' to ' . $post_data['post_title'] . ' - <br>';
        }
    }
}
