<?php

 function log_user_interaction($path, $object_id, $content_type_id, $action, $repr) {
        global $wpdb;
        global $prefix;
        $user_id = get_current_user_id();

        $user_exclude_from_reports = get_user_meta($user_id, $prefix . 'user_exclude_from_reports', true);
        if ($user_exclude_from_reports == 'on') {
            return;
        }
        $created = current_time('mysql');
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $table_name = $wpdb->prefix . 'user_logs';
        
        $wpdb->insert(
            $table_name,
            array(
                'path' => $path,
                'ip_address' => $ip_address,
                'user_id' => $user_id,
                'object_id' => $object_id,
                'content_type_id' => $content_type_id,
                'action' => $action,
                'repr' => $repr,
                'created' => $created,
                'modified' => current_time('mysql')
            ),
            array(
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
        
        return $wpdb->insert_id;
    }