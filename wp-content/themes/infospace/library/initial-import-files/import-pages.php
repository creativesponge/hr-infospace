<?php // Import Pages

$post_id = 826;
$meta_fields = get_post_meta($post_id);
/*
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
    if ($key === 'theme_fieldsresource_attached_links') {
        var_dump(unserialize($value[0]));
    }
    echo "<br>";
}
echo "<br><hr><br>";
*/

global $wpdb;
global $prefix;

// Get all existing old_resource_system_id values
$existing_resource = get_posts(array(
    'post_type'      => 'resource_page',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'fields'         => 'ids',
));

$existing_old_resource_system_ids = array();
foreach ($existing_resource as $existing_resource_id) {
    $old_resource_system_id = get_post_meta($existing_resource_id, $prefix . 'old_resource_system_id', true);

    if ($old_resource_system_id !== '') {
        $existing_old_resource_system_ids[$old_resource_system_id] = $existing_resource_id;
    }
}

// Fetch all rows from the resource_page table
$resultFile = $mysqli->query("SELECT * FROM pages_page");
$resourcesFile = [];

if ($resultFile) {
    $resourcesFile = $resultFile->fetch_all(MYSQLI_ASSOC);
    $resultFile->free();
}

foreach ($resourcesFile as $resourceFile) {
    $importText = "imported";
    if (!should_import_document()) {
        break;
    }
    

    // Map your table fields to post fields. Adjust these as needed.
    $post_data = array(
        'post_title'    => isset($resourceFile['title']) ? $resourceFile['title'] : '', // Change 'title' if needed
        'post_status'   => 'publish',
        'post_type'     => 'resource_page',
        'post_content'  => isset($resourceFile['content']) ? $resourceFile['content'] : '',
        'post_name'     => isset($resourceFile['slug']) ? $resourceFile['slug'] : '',

        //'post_excerpt'  => isset($resourceFile['summary']) ? $resourceFile['summary'] : '',
    );

    // Insert the post into the database

    // Skip if old_resource_system_id already exists
    if (!array_key_exists($resourceFile['id'], $existing_old_resource_system_ids)) {
        $post_id = wp_insert_post($post_data);
    } else {
        $importText = "updated";
        $post_id = $existing_old_resource_system_ids[$resourceFile['id']];
    }

    // Get the parent post new ID using the old ID
    $parent_post = get_posts(array(
        'post_type'      => 'resource_page',
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'meta_query'     => array(
            array(
                'key'   => $prefix . 'old_resource_system_id',
                'value' => intval($resourceFile['parent_id']),
                'compare' => '='
            )
        )
    ));
    $parent_post_id = !empty($parent_post) ?  $parent_post[0]->ID : null;


    // Set parent resource
    if ($parent_post_id) {
        wp_update_post(array(
            'ID' => $post_id,
            'post_parent' => $parent_post_id
        ));
    }

    // Add the fields
    if (isset($resourceFile['id'])) {
        update_post_meta($post_id, $prefix . 'old_resource_system_id', $resourceFile['id']);
    }
    if (isset($resourceFile['created'])) {
        update_post_meta($post_id, 'created', $resourceFile['created']);
        // Set post_date and post_date_gmt
        $created_date = date('Y-m-d H:i:s', strtotime($resourceFile['created']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_date'     => $created_date,
                'post_date_gmt' => get_gmt_from_date($created_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($resourceFile['modified'])) {
        update_post_meta($post_id, 'modified', $resourceFile['modified']);
        // Set post_modified and post_modified_gmt
        $modified_date = date('Y-m-d H:i:s', strtotime($resourceFile['modified']));
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_modified'     => $modified_date,
                'post_modified_gmt' => get_gmt_from_date($modified_date),
            ),
            array('ID' => $post_id)
        );
    }
    if (isset($resourceFile['start_date'])) {
        $start_date = strtotime($resourceFile['start_date']);
        update_post_meta($post_id, $prefix . 'resource_start_date', $start_date !== false ? $start_date : $resourceFile['start_date']);
    }
    if (isset($resourceFile['end_date'])) {
        $end_date = strtotime($resourceFile['end_date']);
        update_post_meta($post_id, $prefix . 'resource_end_date', $end_date !== false ? $end_date : $resourceFile['end_date']);
    }
    if (isset($resourceFile['keywords'])) {
        update_post_meta($post_id, $prefix . 'resource_keywords', $resourceFile['keywords']);
    }
    if (isset($resourceFile['summary'])) {
        update_post_meta($post_id, $prefix . 'resource_summary', $resourceFile['summary']);
    }
    if (isset($resourceFile['level'])) {
        update_post_meta($post_id, $prefix . 'resource_level', $resourceFile['level']);
    }
    if (isset($resourceFile['lft'])) {
        update_post_meta($post_id, $prefix . 'resource_lft', $resourceFile['lft']);
    }
    if (isset($resourceFile['parent_id'])) {
        update_post_meta($post_id, $prefix . 'resource_parent_id', $resourceFile['parent_id']);
    }
    if (isset($resourceFile['right'])) {
        update_post_meta($post_id, $prefix . 'resource_right', $resourceFile['right']);
    }
    if (isset($resourceFile['tree_id'])) {
        update_post_meta($post_id, $prefix . 'resource_tree_id', $resourceFile['tree_id']);
    }
    if (isset($resourceFile['slug'])) {
        update_post_meta($post_id, $prefix . 'resource_slug', $resourceFile['slug']);
    }
    if (isset($resourceFile['path'])) {
        update_post_meta($post_id, $prefix . 'resource_path', $resourceFile['path']);
    }

    if (is_wp_error($post_id)) {
        echo 'Error importing resource: ' . $post_data['post_title'] . ' - ' . $post_id->get_error_message();
    } else {
        echo 'Successfully ' . $importText . ' resource: ' . $post_data['post_title'] . ' (Post ID: ' . $post_id . ')<br>';
    }


    //Import related doc files
    // Fetch all rows from the pages_documentpage table
    $resultFile = $mysqli->query("SELECT * FROM pages_documentpage WHERE page_id = " . intval($resourceFile['id']));
    $relatedDocsFile = [];

    if ($resultFile) {
        $relatedDocsFile = $resultFile->fetch_all(MYSQLI_ASSOC);
        $resultFile->free();
    }

    foreach ($relatedDocsFile as $relatedDocFile) {

        $relatedDocFile_id = $relatedDocFile['document_id'];
        // Get the document post by old_system_id
        $doc_posts = get_posts(array(
            'post_type'      => 'document',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'meta_query'     => array(
                array(
                    'key'   => $prefix . 'old_system_id',
                    'value' => $relatedDocFile_id,
                    'compare' => '='
                )
            )
        ));

        $doc_post_id = !empty($doc_posts) ? $doc_posts[0]->ID : null;

        if ($doc_post_id) {

            // Attach the document file to the resource
            // Get existing attached docs array
            $existing_docs = get_post_meta($post_id, $prefix . 'resource_attached_documents', true);
            if (!is_array($existing_docs)) {
                $existing_docs = array();
            }

            // Add new file ID to array
            if (!in_array($doc_post_id, $existing_docs)) {
                $existing_docs[] = $doc_post_id;
            }

            // Update with array of file IDs
            update_post_meta($post_id, $prefix . 'resource_attached_documents', $existing_docs);
            echo 'added the document: ' . $doc_posts[0]->post_title . ' - ' . $doc_post_id . ' to ' . $post_data['post_title'] . ' - <br>';
        }
    }

    //Import related links
    // Fetch all rows from the pages_linkpage table
    $resultLinks = $mysqli->query("SELECT * FROM pages_linkpage WHERE page_id = " . intval($resourceFile['id']));
    $relatedLinks = [];

    if ($resultLinks) {
        $relatedLinks = $resultLinks->fetch_all(MYSQLI_ASSOC);
        $resultLinks->free();
    }

    foreach ($relatedLinks as $relatedLink) {

        $relatedLinksId = $relatedLink['link_id'];
        // Get the document post by old_system_id
        $link_posts = get_posts(array(
            'post_type'      => 'page_link',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'meta_query'     => array(
                array(
                    'key'   => $prefix . 'old_link_system_id',
                    'value' => $relatedLinksId,
                    'compare' => '='
                )
            )
        ));

        $link_post_id = !empty($link_posts) ?  $link_posts[0]->ID : null;

        if ($link_post_id) {

            // Attach the document file to the resource
            // Get existing attached docs array
            $existing_links = get_post_meta($post_id, $prefix . 'resource_attached_links', true);
            if (!is_array($existing_links)) {
                $existing_links = array();
            }

            // Add new file ID to array
            if (!in_array($link_post_id, $existing_links)) {
                $existing_links[] = $link_post_id;
            }

            // Update with array of file IDs
            update_post_meta($post_id, $prefix . 'resource_attached_links', $existing_links);
            echo 'added the link: ' . $link_posts[0]->post_title . ' - ' . $link_post_id . ' to ' . $post_data['post_title'] . ' - <br>';
        }
    }

    //Import related resources
    // Fetch all rows from the pages_linkpage table
    $resultResources = $mysqli->query("SELECT * FROM pages_pagepage WHERE page_id = " . intval($resourceFile['id']));
    $relatedResources = [];

    if ($resultResources) {
        $relatedResources = $resultResources->fetch_all(MYSQLI_ASSOC);
        $resultResources->free();
    }

    foreach ($relatedResources as $relatedResource) {

        $relatedResourcesId = $relatedResource['related_page_id'];
        // Get the document post by old_system_id
        $resource_posts = get_posts(array(
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

        $resource_post_id = !empty($resource_posts) ?  $resource_posts[0]->ID : null;

        if ($resource_post_id) {

            // Attach the document file to the resource
            // Get existing attached docs array
            $existing_Resources = get_post_meta($post_id, $prefix . 'resource_attached_resources', true);
            if (!is_array($existing_Resources)) {
                $existing_Resources = array();
            }

            // Add new file ID to array
            if (!in_array($resource_post_id, $existing_Resources)) {
                $existing_Resources[] = $resource_post_id;
            }

            // Update with array of file IDs
            update_post_meta($post_id, $prefix . 'resource_attached_resources', $existing_Resources);
            echo 'added the related resource: ' . $resource_posts[0]->post_title . ' - ' . $resource_post_id . ' to ' . $post_data['post_title'] . ' - <br>';
        }
    }
}
