<?php // Import Users

global $wpdb;
global $prefix;

// Get all existing old_user_system_id values
$existing_user = get_users(array(
    'fields' => 'ID'
));

$existing_old_user_system_ids = array();
foreach ($existing_user as $existing_user_id) {

    $old_user_system_id = get_user_meta($existing_user_id, $prefix . 'old_user_system_id', true);

    if ($old_user_system_id !== '') {
        $existing_old_user_system_ids[$old_user_system_id] = $existing_user_id;
    }
}

// Fetch all rows from the post_page table
$resultFile = $mysqli->query("SELECT * FROM users_user");
$userData = [];

if ($resultFile) {
    $userData = $resultFile->fetch_all(MYSQLI_ASSOC);
    $resultFile->free();
}

foreach ($userData as $userRecord) {
    $importText = "imported";
    if (!should_import_document()) {
        break;
    }

    //Import role Data
    // Fetch all rows from the users_user_groups table
    $resultGroups = $mysqli->query("SELECT * FROM users_user_groups WHERE user_id = " . intval($userRecord['id']));
    $relatedResources = [];

    if ($resultGroups) {
        $attachedGroups = $resultGroups->fetch_all(MYSQLI_ASSOC);
        $resultGroups->free();
    }
    $userRole = 'individual';

    foreach ($attachedGroups as $attachedGroup) {

        $attachedGroupsId = $attachedGroup['group_id'];

        switch ($attachedGroupsId) {
            case 3:
                $userRole = 'employee';
                break;
            case 2:
                $userRole = 'individual';
                break;
            case 1:
                $userRole = 'main';
                break;
        }
    }

    $firstName = isset($userRecord['first_name']) ? $userRecord['first_name'] : '';
    $lastName = isset($userRecord['last_name']) ? $userRecord['last_name'] : '';
    $combinedName = $firstName . ' ' . $lastName;
    $loginName = $firstName . '-' . $lastName;

    // Map your table fields to post fields. Adjust these as needed.
    $post_data = array(
        'user_login' => $loginName,
        'user_pass' => null,
        'user_email' => $userRecord['email'],
        'first_name' => $firstName,
        'last_name' =>  $lastName,
        'display_name' => $combinedName,
        'role' => $userRole
    );

    // Insert the post into the database

    // Skip if old_user_system_id already exists
    if (!array_key_exists($userRecord['id'], $existing_old_user_system_ids)) {
        $post_id = wp_insert_user($post_data);
    } else {
        $importText = "updated";
        $post_id = $existing_old_user_system_ids[$userRecord['id']];
    }

    // Add the fields
    if (isset($userRecord['id'])) {
        update_user_meta($post_id, $prefix . 'old_user_system_id', $userRecord['id']);
    }



    if (isset($userRecord['organisation_name'])) {
        update_user_meta($post_id, $prefix . 'user_organisation', $userRecord['organisation_name']);
    }
    if (isset($userRecord['lea_number'])) {
        update_user_meta($post_id, $prefix . 'user_dfe_number', $userRecord['lea_number']);
    }
    if (isset($userRecord['expiry_date'])) {
        $end_date = strtotime($userRecord['expiry_date']);
        update_user_meta($post_id, $prefix . 'user_end_date', $end_date !== false ? $end_date : $userRecord['expiry_date']);
    }
    if (isset($userRecord['is_active'])) {
        update_user_meta($post_id, $prefix . 'user_is_active', $userRecord['is_active'] ? 'on' : '');
    }
   // var_dump($userRecord['is_active'] ? '<strong>true</strong>' : '<strong>false</strong>');
    if (isset($userRecord['receive_hr_alerts'])) {
        update_user_meta($post_id, $prefix . 'user_hr_alerts', $userRecord['receive_hr_alerts'] ? 'on' : '');
    }
    if (isset($userRecord['receive_finance_alerts'])) {
        update_user_meta($post_id, $prefix . 'user_finance_alerts', $userRecord['receive_finance_alerts'] ? 'on' : '');
    }
    if (isset($userRecord['accepted_terms'])) {
        update_user_meta($post_id, $prefix . 'user_accepted_terms', $userRecord['accepted_terms'] ? 'on' : '');
    }
    if (isset($userRecord['accepted_privacy_policy'])) {
        update_user_meta($post_id, $prefix . 'user_accepted_privacy_policy', $userRecord['accepted_privacy_policy'] ? 'on' : '');
    }
    if (isset($userRecord['exclude_from_reports'])) {
        update_user_meta($post_id, $prefix . 'user_exclude_from_reports', $userRecord['exclude_from_reports'] ? 'on' : '');
    }
    if (isset($userRecord['is_staff'])) {
        update_user_meta($post_id, $prefix . 'user_is_staff', $userRecord['is_staff'] ? 'on' : '');
    }
    if (isset($userRecord['is_super_user'])) {
        update_user_meta($post_id, $prefix . 'user_is_super_user', $userRecord['is_super_user'] ? 'on' : '');
    }
    if (isset($userRole)) {
        update_user_meta($post_id, $prefix . 'user_group', $userRole);
    }
    if (isset($userRecord['created_by_id'])) {
        // Get the user by old_user_system_id
        $created_by_users = get_users(array(
            'meta_query' => array(
                array(
                    'key'   => $prefix . 'old_user_system_id',
                    'value' => $userRecord['created_by_id'],
                    'compare' => '='
                )
            )
        ));

        $created_by_user_id = !empty($created_by_users) ? $created_by_users[0]->ID : null;

        if ($created_by_user_id) {
            update_user_meta($post_id, $prefix . 'user_created_by', $created_by_user_id);
        }
    }

    if (isset($userRecord['last_login'])) {
        $last_login = strtotime($userRecord['last_login']);
        update_user_meta($post_id, $prefix . 'user_last_login', $last_login !== false ? $last_login : $userRecord['last_login']);
    }

    if (is_wp_error($post_id)) {
        echo 'Error importing user: ' . $combinedName . ' - ' . $post_id->get_error_message();
    } else {
        echo 'Successfully ' . $importText . ' user: ' . $combinedName . ' (Post ID: ' . $post_id . ')<br>';
    }




    //Import related resources
    // Fetch all rows from the users_userpage table
    $resultResource = $mysqli->query("SELECT * FROM users_userpage WHERE user_id = " . intval($userRecord['id']));
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
            $existingResources = get_user_meta($post_id, $prefix . 'user_attached_resource_pages', true);
          //  var_dump($post_id);
            if (!is_array($existingResources)) {
                $existingResources = array($existingResources);
                //$existingResources = array();
            }

            // Add new ID to array
            if (!in_array($post_post_id, $existingResources)) {
                $existingResources[] = $post_post_id;
            }

            // Update with array of IDs
            update_user_meta($post_id, $prefix . 'user_attached_resource_pages', $existingResources);
            echo 'added the related resource: ' . $post_posts[0]->post_title . ' - ' . $post_post_id . ' to ' . $combinedName . ' - <br>';
        }
    }

    //Import favourites
    $importText = "imported";
    // Fetch all rows from the users_favourite table
    $resultFavourite = $mysqli->query("SELECT * FROM users_favourite WHERE user_id = " . intval($userRecord['id']));
   
    $favouritesFile = [];

    if ($resultFavourite) {
        $favouritesFile = $resultFavourite->fetch_all(MYSQLI_ASSOC);
        $resultFavourite->free();
    }
    //echo "<pre>";
//var_dump($favouritesFile);
    //echo "</pre>";
    foreach ($favouritesFile as $favouritesFile) {
        // Insert the favourite into the database
        // Map your table fields to post fields. Adjust these as needed.
        $post_data = array(
            //'post_title'    => isset($favouritesFile['file']) ? str_replace('documents/', '', $favouritesFile['file']) : '', // Change 'title' if needed
            'post_status'   => 'publish',
            'post_type'     => 'favourite',
            'post_author'   => $post_id,
        );


        // Get all existing old_system_id values for favourites
        $existing_favourites_files = get_posts(array(
            'post_type'      => 'favourite',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ));

        $existing_old_favourite_system_ids = array();
        foreach ($existing_favourites_files as $existing_favourite_id) {
            $old_favourite_system_id = get_post_meta($existing_favourite_id, $prefix . 'old_favourite_system_id', true);
            if ($old_favourite_system_id !== '') {
                $existing_old_favourite_system_ids[$old_favourite_system_id] =  $existing_favourite_id;
            }
        }

        // Skip if old_system_id already exists
        if (!array_key_exists($favouritesFile['id'], $existing_old_favourite_system_ids)) {
            $favourite_id = wp_insert_post($post_data);
        } else {
            $importText = "updated";
            $favourite_id = $existing_old_favourite_system_ids[$favouritesFile['id']];
        }

        // Add the fields
        if (isset($favouritesFile['id'])) {
            update_post_meta($favourite_id, $prefix . 'old_favourite_system_id', $favouritesFile['id']);
        }
        if (isset($favouritesFile['created'])) {
            update_post_meta($favourite_id, 'created', $favouritesFile['created']);
            // Set post_date and post_date_gmt
            $created_date = date('Y-m-d H:i:s', strtotime($favouritesFile['created']));
            $wpdb->update(
                $wpdb->posts,
                array(
                    'post_date'     => $created_date,
                    'post_date_gmt' => get_gmt_from_date($created_date),
                ),
                array('ID' => $favourite_id)
            );
        }
        if (isset($favouritesFile['modified'])) {
            update_post_meta($favourite_id, 'modified', $favouritesFile['modified']);
            // Set post_modified and post_modified_gmt
            $modified_date = date('Y-m-d H:i:s', strtotime($favouritesFile['modified']));
            $wpdb->update(
                $wpdb->posts,
                array(
                    'post_modified'     => $modified_date,
                    'post_modified_gmt' => get_gmt_from_date($modified_date),
                ),
                array('ID' => $favourite_id)
            );
        }

        $attached_item_title = '';
        if ($favouritesFile['content_type_id'] == '12') { //documents
            //Import related doc files
            // Fetch all rows from the pages_documentpage table
            //$resultFile = $mysqli->query("SELECT * FROM pages_documentpage WHERE page_id = " . intval($favouritesFile['object_id']));
            //$relatedDocsFile = [];

            //if ($resultFile) {
            //$relatedDocsFile = $resultFile->fetch_all(MYSQLI_ASSOC);
            // $resultFile->free();
            //}

            //foreach ($relatedDocsFile as $relatedDocFile) {

            $favouriteDocID = $favouritesFile['object_id'];
            // Get the document post by old_system_id
            $doc_posts = get_posts(array(
                'post_type'      => 'document',
                'posts_per_page' => 1,
                'post_status'    => 'any',
                'meta_query'     => array(
                    array(
                        'key'   => $prefix . 'old_system_id',
                        'value' => $favouriteDocID,
                        'compare' => '='
                    )
                )
            ));

            $doc_post_id = !empty($doc_posts) ? $doc_posts[0]->ID : null;

            if ($doc_post_id) {
               
                // Attach the document file to the resource
                // Get existing attached docs array
                $existing_docs = get_post_meta($favourite_id, $prefix . 'favourite_attached_documents', true);
                if (!is_array($existing_docs)) {
                    $existing_docs = array();
                }

                // Add new file ID to array
                if (!in_array($doc_post_id, $existing_docs)) {
                    $existing_docs[] = $doc_post_id;
                }

                // Update with array of file IDs
                update_post_meta($favourite_id, $prefix . 'favourite_attached_documents', $existing_docs);
                //echo 'added the favourited document: ' . $doc_posts[0]->post_title . ' - ' . $doc_post_id . ' to ' . $post_data['post_title'] . ' - <br>';
            }
            // }
        } elseif ($favouritesFile['content_type_id'] == '19') { //links
            //Import related links
            // Fetch all rows from the pages_linkpage table
            //$resultLinks = $mysqli->query("SELECT * FROM pages_linkpage WHERE page_id = " . intval($resourceFile['object_id']));
            //$relatedLinks = [];

            //if ($resultLinks) {
            // $relatedLinks = $resultLinks->fetch_all(MYSQLI_ASSOC);
            // $resultLinks->free();
            // }

            //foreach ($relatedLinks as $relatedLink) {

            $relatedLinksId = $favouritesFile['object_id'];
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
                $existing_links = get_post_meta($favourite_id, $prefix . 'favourite_attached_links', true);
                if (!is_array($existing_links)) {
                    $existing_links = array();
                }

                // Add new file ID to array
                if (!in_array($link_post_id, $existing_links)) {
                    $existing_links[] = $link_post_id;
                }

                // Update with array of file IDs
                update_post_meta($favourite_id, $prefix . 'favourite_attached_links', $existing_links);
                //echo 'added the favourited link: ' . $link_posts[0]->post_title . ' - ' . $link_post_id . ' to ' . $post_data['post_title'] . ' - <br>';
            }
            // }
        } elseif ($favouritesFile['content_type_id'] == '10') { //resources
            //Import related resources
            // Fetch all rows from the pages_pagepage table
            //$resultResources = $mysqli->query("SELECT * FROM pages_pagepage WHERE page_id = " . intval($resourceFile['object_id']));
            //$relatedResources = [];

            //if ($resultResources) {
            //  $relatedResources = $resultResources->fetch_all(MYSQLI_ASSOC);
            // $resultResources->free();
            //}

            //foreach ($relatedResources as $relatedResource) {

            $favRelatedResourcesId = $favouritesFile['object_id'];
            // Get the document post by old_system_id
            $resource_posts = get_posts(array(
                'post_type'      => 'resource_page',
                'posts_per_page' => 1,
                'post_status'    => 'any',
                'meta_query'     => array(
                    array(
                        'key'   => $prefix . 'old_resource_system_id',
                        'value' => $favRelatedResourcesId,
                        'compare' => '='
                    )
                )
            ));

            $resource_post_id = !empty($resource_posts) ?  $resource_posts[0]->ID : null;

            if ($resource_post_id) {

                // Attach the document file to the resource
                // Get existing attached docs array
                $existing_Resources = get_post_meta($favourite_id, $prefix . 'favourite_attached_resources', true);
                if (!is_array($existing_Resources)) {
                    $existing_Resources = array();
                }

                // Add new file ID to array
                if (!in_array($resource_post_id, $existing_Resources)) {
                    $existing_Resources[] = $resource_post_id;
                }

                // Update with array of file IDs
                update_post_meta($favourite_id, $prefix . 'favourite_attached_resources', $existing_Resources);
               // echo 'added the favoiurited resource: ' . $resource_posts[0]->post_title . ' - ' . $resource_post_id . ' to ' . $post_data['post_title'] . ' - <br>';
            }
        }
        // Update the post title based on user and attached item

        if ($favouritesFile['content_type_id'] == '12' && $doc_post_id) {
            $attached_item_title = $doc_posts[0]->post_title;
        } elseif ($favouritesFile['content_type_id'] == '19' && $link_post_id) {
            $attached_item_title = $link_posts[0]->post_title;
        } elseif ($favouritesFile['content_type_id'] == '10' && $resource_post_id) {
            $attached_item_title = $resource_posts[0]->post_title;
        }
        $new_title = '';
        if (!empty($attached_item_title)) {
            $new_title = $attached_item_title;
            wp_update_post(array(
                'ID' => $favourite_id,
                'post_title' => $new_title
            ));
        }

        if (is_wp_error($favourite_id)) {
            echo 'Error importing favourite: ' . $new_title . ' - ' . $favourite_id->get_error_message();
        } else {
            echo 'Successfully ' . $importText . ' favourite: ' . $new_title . ' (Post ID: ' . $favourite_id . ') to ' . $combinedName . '<br>';
        }
        //}
    }
}
