<?php

// Custom Theme Settings
add_action('admin_menu', 'add_cron_functions_interface');
function add_cron_functions_interface()
{
    add_management_page('Run cron functions', 'Run cron functions', 'manage_options', 'import_cron_functions_page', 'cron_functions');
}

// Cron job
function ewj_cron_schedules_cron_functions($schedules)
{
    if (!isset($schedules["10min"])) {
        $schedules["10min"] = array(
            'interval' => 10 * 60,
            'display' => __('Once every 10 minutes')
        );
    }
    return $schedules;
}
add_filter('cron_schedules', 'ewj_cron_schedules_cron_functions');


register_activation_hook(__FILE__, 'do_cron_functions');
do_cron_functions();
function do_cron_functions()
{
    if (!wp_next_scheduled('run_cron_functions')) {
        wp_schedule_event(time(), '10min', 'run_cron_functions');
    }
}

add_action('run_cron_functions', 'cron_functions');

function cron_functions()
{
    global $prefix;

    echo "<h1>Run cron functions</h1>";

    // Set the document as active based on whether it has active docs attached
    // Get all the documents
    $documents = get_posts(array(
        'post_type' => 'document',
        'numberposts' => -1,
    ));

    foreach ($documents as $document) {
        // Process each document
        $hasActiveDocs = false;
        // get the document_files attached to this document
        $document_files = get_post_meta($document->ID, $prefix . 'document_files', true);

        if ($document_files) {
            foreach ($document_files as $file) {
                $start_date = isset($file[$prefix . 'start_date']) ? $file[$prefix . 'start_date'] : '';
                $end_date = isset($file[$prefix . 'end_date']) ? $file[$prefix . 'end_date'] : date('Y-m-d', strtotime('+1 year'));
                $today = strtotime(date('Y-m-d'));

                if ($start_date && $end_date) { // has start and end date
                    if ($start_date <= $today && $end_date >= $today) {
                        $hasActiveDocs = true;
                        break;
                    }
                } else {
                    if ($start_date) { // just start date
                        if ($start_date <= $today) {
                            $hasActiveDocs = true;
                            break;
                        }
                    } elseif ($end_date) { // just end date
                        if ($end_date >= $today) {
                            $hasActiveDocs = true;
                            break;
                        }
                    } elseif (!$start_date && !$end_date) {
                        // No dates set, consider active
                        $hasActiveDocs = true;
                        break;
                    }
                }
                //echo "Start Date: " . $start_date . "<br>";
                //echo "End Date: " . $end_date . "<br><br>";
            }
        }
        if ($hasActiveDocs) {
            update_post_meta($document->ID, $prefix . 'document_is_active', 'on');
            echo "Document ID: " . $document->ID . " (" . $document->post_title . ") is active.<br>";
        } else {
            update_post_meta($document->ID, $prefix . 'document_is_active', '');
            echo "Document ID: " . $document->ID . " (" . $document->post_title . ") is not active.<br>";
        }
    }
}
