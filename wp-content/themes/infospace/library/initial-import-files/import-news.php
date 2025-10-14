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

// Get all existing old_post_system_id values
$existing_post = get_posts(array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'fields'         => 'ids',
));

$existing_old_post_system_ids = array();
foreach ($existing_post as $existing_post_id) {
    $old_post_system_id = get_post_meta($existing_post_id, $prefix . 'old_post_system_id', true);

    if ($old_post_system_id !== '') {
        $existing_old_post_system_ids[$old_post_system_id] = $existing_post_id;
    }
}

// Fetch all rows from the post_page table
$resultFile = $mysqli->query("SELECT * FROM news_news");
$postsFile = [];

if ($resultFile) {
    $postsFile = $resultFile->fetch_all(MYSQLI_ASSOC);
    $resultFile->free();
}

foreach ($postsFile as $postRecord) {
    $importText = "imported";
    if (!should_import_document()) {
        break;
    }
    

    // Map your table fields to post fields. Adjust these as needed.
    $post_data = array(
        'post_title'    => isset($postRecord['title']) ? $postRecord['title'] : '', // Change 'title' if needed
        'post_status'   => 'publish',
        'post_type'     => 'post',
        'post_content'  => isset($postRecord['content']) ? $postRecord['content'] : '',
        'post_name'     => isset($postRecord['slug']) ? $postRecord['slug'] : '',

        //'post_excerpt'  => isset($postRecord['summary']) ? $postRecord['summary'] : '',
    );

    // Insert the post into the database

    // Skip if old_post_system_id already exists
    if (!array_key_exists($postRecord['id'], $existing_old_post_system_ids)) {
        $post_id = wp_insert_post($post_data);
    } else {
        $importText = "updated";
        $post_id = $existing_old_post_system_ids[$postRecord['id']];
    }

    // Add the fields
    if (isset($postRecord['id'])) {
        update_post_meta($post_id, $prefix . 'old_post_system_id', $postRecord['id']);
    }
    if (isset($postRecord['created'])) {
        update_post_meta($post_id, 'created', $postRecord['created']);
        // Set post_date and post_date_gmt
        $created_date = date('Y-m-d H:i:s', strtotime($postRecord['created']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_date'     => $created_date,
                'post_date_gmt' => get_gmt_from_date($created_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($postRecord['modified'])) {
        update_post_meta($post_id, 'modified', $postRecord['modified']);
        // Set post_modified and post_modified_gmt
        $modified_date = date('Y-m-d H:i:s', strtotime($postRecord['modified']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_modified'     => $modified_date,
                'post_modified_gmt' => get_gmt_from_date($modified_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($postRecord['start_date'])) {
        $start_date = strtotime($postRecord['start_date']);
        update_post_meta($post_id, $prefix . 'post_start_date', $start_date !== false ? $start_date : $postRecord['start_date']);
    }
    if (isset($postRecord['end_date'])) {
        $end_date = strtotime($postRecord['end_date']);
        update_post_meta($post_id, $prefix . 'post_end_date', $end_date !== false ? $end_date : $postRecord['end_date']);
    }
    
    if (isset($postRecord['summary'])) {
        update_post_meta($post_id, $prefix . 'post_summary', $postRecord['summary']);
    }

    if (isset($postRecord['slug'])) {
        update_post_meta($post_id, $prefix . 'post_slug', $postRecord['slug']);
    }
    

    if (is_wp_error($post_id)) {
        echo 'Error importing post: ' . $post_data['post_title'] . ' - ' . $post_id->get_error_message();
    } else {
        echo 'Successfully ' . $importText . ' post: ' . $post_data['post_title'] . ' (Post ID: ' . $post_id . ')<br>';
    }

    //Import related resources
    // Fetch all rows from the news_newspage table
    $resultResource = $mysqli->query("SELECT * FROM news_newspage WHERE news_id = " . intval($postRecord['id']));
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
            $existingResources = get_post_meta($post_id, $prefix . 'post_attached_resource_pages', true);
            if (!is_array($existingResources)) {
                $existingResources = array();
            }

            // Add new ID to array
            if (!in_array($post_post_id, $existingResources)) {
                $existingResources[] = $post_post_id;
            }

            // Update with array of IDs
            update_post_meta($post_id, $prefix . 'post_attached_resource_pages', $existingResources);
            echo 'added the related resource: ' . $post_posts[0]->post_title . ' - ' . $post_post_id . ' to ' . $post_data['post_title'] . ' - <br>';
        }
    }
}
