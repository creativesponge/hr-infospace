<?php // Import logs
global $wpdb;
global $prefix;

// Create custom table if it doesn't exist
$table_name = $wpdb->prefix . 'user_logs';

// Check if table already exists
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        old_log_system_id mediumint(9),
        path varchar(255),
        ip_address varchar(45),
        user_id mediumint(9),
        object_id mediumint(9),
        content_type_id mediumint(9),
        action varchar(255) NOT NULL,
        repr text,
        created datetime DEFAULT CURRENT_TIMESTAMP,
        modified datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY old_log_system_id (old_log_system_id)
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    echo "Table created or updated successfully.";
}





// Get all data from the wp_user_logs table
$logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created DESC");
// Get all existing old_log_system_id values
$existing_old_log_system_ids = array();


if ($logs) {
    echo "<h3>User Logs Data:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Old System ID</th>";
    echo "<th>Path</th>";
    echo "<th>IP Address</th>";
    echo "<th>User ID</th>";
    echo "<th>Object ID</th>";
    echo "<th>Content Type ID</th>";
    echo "<th>Action</th>";

    echo "<th>Created</th>";
    echo "<th>Modified</th>";
    echo "</tr>";

    foreach ($logs as $log) {
        echo "<tr>";
        echo "<td>" . esc_html($log->id) . "</td>";
        echo "<td>" . esc_html($log->old_log_system_id) . "</td>";
        echo "<td>" . esc_html($log->path) . "</td>";
        echo "<td>" . esc_html($log->ip_address) . "</td>";
        echo "<td>" . esc_html($log->user_id) . "</td>";
        echo "<td>" . esc_html($log->object_id) . "</td>";
        echo "<td>" . esc_html($log->content_type_id) . "</td>";
        echo "<td>" . esc_html($log->action) . "</td>";

        echo "<td>" . esc_html($log->created) . "</td>";
        echo "<td>" . esc_html($log->modified) . "</td>";
        echo "</tr>";

        // get all the old ids
        $old_log_system_id = $log->old_log_system_id;
        if ($old_log_system_id !== '') {
            $existing_old_log_system_ids[$old_log_system_id] = $log->id;
        }
    }
    echo "</table>";



    // Fetch all rows from the pages_log table
    $resultFile = $mysqli->query("SELECT * FROM users_userview");
    $logsFile = [];

    if ($resultFile) {
        $logsFile = $resultFile->fetch_all(MYSQLI_ASSOC);
        $resultFile->free();
    }

    foreach ($logsFile as $logFile) {

        $importText = "imported";
        if (!should_import_document()) {
            break;
        }

        // Map your table fields to log data

        // Get the new user id using the old one
        $old_user_id = isset($logFile['user_id']) ? $logFile['user_id'] : null;
        if ($old_user_id) {
            // Check if user exists in WordPress
            $wp_user = get_users(array(
                'meta_key' => $prefix . 'old_user_system_id',
                'meta_value' => $old_user_id,
                'number' => 1
            ));
            if (!empty($wp_user)) {
                $new_user_id = $wp_user[0]->ID;
            } else {
                $new_user_id = null; // User not found in WordPress
            }
        }

        // Get the content ids using the old ones
        $content_type_id = isset($logFile['content_type_id']) ? $logFile['content_type_id'] : null;
        $old_object_id = isset($logFile['object_id']) ? $logFile['object_id'] : null;

        if ($content_type_id) {
            // Define mapping of content types to post types and meta keys
            $content_type_mapping = array(
                '10' => array('post_type' => 'page', 'meta_key' => $prefix . 'old_resource_system_id'),
                '12' => array('post_type' => 'document', 'meta_key' => $prefix . 'old_system_id'),
                '19' => array('post_type' => 'page_link', 'meta_key' => $prefix . 'old_link_system_id'),
                '9' => array('post_type' => 'newsletter', 'meta_key' => $prefix . 'old_newsletter_system_id'),
                '8' => array('post_type' => 'post', 'meta_key' => $prefix . 'old_post_system_id')
            );

            if (isset($content_type_mapping[$content_type_id])) {
                $mapping = $content_type_mapping[$content_type_id];
                $wp_post = get_posts(array(
                    'post_type' => $mapping['post_type'],
                    'meta_key' => $mapping['meta_key'],
                    'meta_value' => $old_object_id,
                    'number' => 1,
                    'fields' => 'ids'
                ));
                $new_object_id = !empty($wp_post) ? $wp_post[0] : null;
            } else {
                $new_object_id = null;
            }
        }

        $log_data = array(
            'old_log_system_id' => isset($logFile['id']) ? $logFile['id'] : null,
            'path' => isset($logFile['path']) ? $logFile['path'] : '',
            'ip_address' => isset($logFile['ip_address']) ? $logFile['ip_address'] : '',
            'user_id' => $new_user_id,
            'object_id' => $new_object_id,
            'content_type_id' => $content_type_id,
            'action' => isset($logFile['action']) ? $logFile['action'] : '',
            'repr' => isset($logFile['repr']) ? $logFile['repr'] : '',
            'created' => isset($logFile['created']) ? $logFile['created'] : current_time('mysql'),
            'modified' => isset($logFile['modified']) ? $logFile['modified'] : current_time('mysql')
        );



        // Insert the post into the database
        //var_dump($existing_old_log_system_ids);
        // Skip if old_log_system_id already exists
        if (!array_key_exists($logFile['id'], $existing_old_log_system_ids)) {
            //add
            $result = $wpdb->insert($table_name, $log_data);
            if ($result === false) {
                echo 'Error importing log: ' . $wpdb->last_error . '<br>';
            } else {
                echo 'Successfully ' . $importText . ' log (ID: ' . $wpdb->insert_id . ')<br>';
            }
        } else {
            $importText = "updated";
            $post_id = $existing_old_log_system_ids[$logFile['id']];
            echo 'Successfully ' . $importText . ' log (ID: ' . $wpdb->insert_id . ')<br>';
        }
    }
} else {
    echo "No logs found in the user_logs table.";
}
/*
$existing_logs = get_posts(array(
    'post_type'      => 'user_view',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'fields'         => 'ids',
));

$existing_old_log_system_ids = array();
foreach ($existing_logs as $existing_log_id) {
    $old_log_system_id = get_post_meta($existing_log_id, $prefix . 'old_user_view_system_id', true);

    if ($old_log_system_id !== '') {
        $existing_old_log_system_ids[$old_log_system_id] = $existing_log_id;
    }
}

// Fetch all rows from the pages_log table
$resultFile = $mysqli->query("SELECT * FROM users_userview");
$logsFile = [];

if ($resultFile) {
    $logsFile = $resultFile->fetch_all(MYSQLI_ASSOC);
    $resultFile->free();
}

foreach ($logsFile as $logFile) {
    $importText = "imported";
    if (!should_import_document()) {
        break;
    }
    // Map your table fields to post fields. Adjust these as needed.
    $post_data = array(
        'post_title'    => isset($logFile['title']) ? $logFile['title'] : '', // Change 'title' if needed
        'post_status'   => 'publish',
        'post_type'     => 'user_view',
        'menu_order'    => isset($logFile['sort_order']) ? (int)$logFile['sort_order'] : 0,
    );

    // Insert the post into the database

    // Skip if old_log_system_id already exists
    if (!array_key_exists($logFile['id'], $existing_old_log_system_ids)) {
        $post_id = wp_insert_post($post_data);
    } else {
        $importText = "updated";
        $post_id = $existing_old_log_system_ids[$logFile['id']];
    }

    // Add the fields
    if (isset($logFile['id'])) {
        update_post_meta($post_id, $prefix . 'old_log_system_id', $logFile['id']);
    }
    if (isset($logFile['created'])) {
        update_post_meta($post_id, 'created', $logFile['created']);
        // Set post_date and post_date_gmt
        $created_date = date('Y-m-d H:i:s', strtotime($logFile['created']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_date'     => $created_date,
                'post_date_gmt' => get_gmt_from_date($created_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($logFile['modified'])) {
        update_post_meta($post_id, 'modified', $logFile['modified']);
        // Set post_modified and post_modified_gmt
        $modified_date = date('Y-m-d H:i:s', strtotime($logFile['modified']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_modified'     => $modified_date,
                'post_modified_gmt' => get_gmt_from_date($modified_date),
            ),
            array('ID' => $post_id)
        );
    }



    if (isset($logFile['url'])) {
        update_post_meta($post_id, $prefix . 'page_log_url', $logFile['url']);
    }
    if (isset($logFile['sort_order'])) {
        update_post_meta($post_id, $prefix . 'sort_order', $logFile['sort_order']);
    }
    if (isset($logFile['summary'])) {
        update_post_meta($post_id, $prefix . 'page_log_summary', $logFile['summary']);
    }
    if (isset($logFile['keywords'])) {
        update_post_meta($post_id, $prefix . 'page_log_keywords', $logFile['keywords']);
    }
    if (isset($logFile['is_active'])) {
        update_post_meta($post_id, $prefix . 'page_log_is_active', $logFile['is_active'] ? 'on' : '');
    }

    if (is_wp_error($post_id)) {
        echo 'Error importing log: ' . $post_data['post_title'] . ' - ' . $post_id->get_error_message();
    } else {
        echo 'Successfully ' . $importText . ' log file: ' . $post_data['post_title'] . ' (Post ID: ' . $post_id . ')<br>';
    }
*/
