<?php // Import links


global $wpdb;
global $prefix;

// Get all existing old_link_system_id values
$existing_links = get_posts(array(
    'post_type'      => 'page_link',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'fields'         => 'ids',
));

$existing_old_link_system_ids = array();
foreach ($existing_links as $existing_link_id) {
    $old_link_system_id = get_post_meta($existing_link_id, $prefix . 'old_link_system_id', true);

    if ($old_link_system_id !== '') {
        $existing_old_link_system_ids[$old_link_system_id] = $existing_link_id;
    }
}

// Fetch all rows from the pages_link table
$resultFile = $mysqli->query("SELECT * FROM pages_link");
$linksFile = [];

if ($resultFile) {
    $linksFile = $resultFile->fetch_all(MYSQLI_ASSOC);
    $resultFile->free();
}

foreach ($linksFile as $linkFile) {
    $importText = "imported";
    if (!should_import_document()) {
        break;
    }
    // Map your table fields to post fields. Adjust these as needed.
    $post_data = array(
        'post_title'    => isset($linkFile['title']) ? $linkFile['title'] : '', // Change 'title' if needed
        'post_status'   => 'publish',
        'post_type'     => 'page_link',
        'menu_order'    => isset($linkFile['sort_order']) ? (int)$linkFile['sort_order'] : 0,
    );

    // Insert the post into the database

    // Skip if old_link_system_id already exists
    if (!array_key_exists($linkFile['id'], $existing_old_link_system_ids)) {
        $post_id = wp_insert_post($post_data);
    } else {
        $importText = "updated";
        $post_id = $existing_old_link_system_ids[$linkFile['id']];
    }

    // Add the fields
    if (isset($linkFile['id'])) {
        update_post_meta($post_id, $prefix . 'old_link_system_id', $linkFile['id']);
    }
    if (isset($linkFile['created'])) {
        update_post_meta($post_id, 'created', $linkFile['created']);
        // Set post_date and post_date_gmt
        $created_date = date('Y-m-d H:i:s', strtotime($linkFile['created']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_date'     => $created_date,
                'post_date_gmt' => get_gmt_from_date($created_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($linkFile['modified'])) {
        update_post_meta($post_id, 'modified', $linkFile['modified']);
        // Set post_modified and post_modified_gmt
        $modified_date = date('Y-m-d H:i:s', strtotime($linkFile['modified']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_modified'     => $modified_date,
                'post_modified_gmt' => get_gmt_from_date($modified_date),
            ),
            array('ID' => $post_id)
        );
    }



    if (isset($linkFile['url'])) {
        update_post_meta($post_id, $prefix . 'page_link_url', $linkFile['url']);
    }
    if (isset($linkFile['sort_order'])) {
        update_post_meta($post_id, $prefix . 'sort_order', $linkFile['sort_order']);
    }
    if (isset($linkFile['summary'])) {
        update_post_meta($post_id, $prefix . 'page_link_summary', $linkFile['summary']);
    }
    if (isset($linkFile['keywords'])) {
        update_post_meta($post_id, $prefix . 'page_link_keywords', $linkFile['keywords']);
    }
    if (isset($linkFile['is_active'])) {
        update_post_meta($post_id, $prefix . 'page_link_is_active', $linkFile['is_active'] ? 'on' : '');
    }

    if (is_wp_error($post_id)) {
        echo 'Error importing link: ' . $post_data['post_title'] . ' - ' . $post_id->get_error_message();
    } else {
        echo 'Successfully ' . $importText . ' link file: ' . $post_data['post_title'] . ' (Post ID: ' . $post_id . ')<br>';
    }
}
