<?php

// Custom Theme Settings
add_action('admin_menu', 'add_cron_functions_interface');
function add_cron_functions_interface()
{
    add_management_page('Run cron functions', 'Run cron functions', 'manage_options', 'import_cron_functions_page', 'cron_functions');
    //add_management_page('Run daily cron functions', 'Run daily cron functions', 'manage_options', 'import_daily_cron_functions_page', 'daily_cron_functions');
}

// Cron job
function infospace_cron_schedules_cron_functions($schedules)
{
    if (!isset($schedules["10min"])) {
        $schedules["10min"] = array(
            'interval' => 10 * 60,
            'display' => __('Once every 10 minutes')
        );
    }
    return $schedules;
}
add_filter('cron_schedules', 'infospace_cron_schedules_cron_functions');


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

// Daily cron job
function infospace_cron_schedules_daily($schedules)
{
    if (!isset($schedules["daily"])) {
        $schedules["daily"] = array(
            'interval' => 24 * 60 * 60,
            'display' => __('Once daily')
        );
    }
    return $schedules;
}
add_filter('cron_schedules', 'infospace_cron_schedules_daily');

register_activation_hook(__FILE__, 'do_daily_cron');
do_daily_cron();
function do_daily_cron()
{
    if (!wp_next_scheduled('run_daily_cron')) {
        wp_schedule_event(time(), 'daily', 'run_daily_cron');
    }
}

add_action('run_daily_cron', 'daily_cron_functions');

function daily_cron_functions()
{
    global $prefix;
    echo "<h1>Run daily cron functions</h1>";
    // Set the document as active based on whether it has active docs attached
    // Get all the documents
    $documents = get_posts(array(
        'post_type' => 'document',
        'numberposts' => -1,
    ));

    foreach ($documents as $document) {

        $page_id = get_document_page($document->ID);
        $module_id = get_page_module($page_id);
        $module_email = get_post_meta($module_id, $prefix . 'module_email_address', true) ?: "EHRpolicy@norfolk.gov.uk";

        // Check if modified exactly one year ago and send an email to admin
        $today = new DateTime();
        $modified_date = new DateTime(get_the_modified_date('Y-m-d', $document->ID));
        $one_year_ago = clone $today;
        $one_year_ago->sub(new DateInterval('P1Y'));

        //if ($modified_date->getTimestamp() === $one_year_ago->getTimestamp()) {
        if ($modified_date->getTimestamp() === 1767830400) {
            //$admin_email = get_option('admin_email');
            $module_email = "barry@creativesponge.co.uk"; // For testing purposes
            //$module_email = "ehrpolicy@norfolkgov.uk";
            $subject = 'Document Modified One Year Ago';
            $message = '<p>The document "' . $document->post_title . '" (ID: ' . $document->ID . ') has not been updated for one year.</p>';
            $message .= '<p>Please visit: <a href="https://www.infospace.org.uk/wp-admin/post.php?post=' . $document->ID . '&action=edit">this link</a> to review it.</p>';
            $headers = array(
                'From: barry@creativesponge.co.uk',
                'Content-Type: text/html; charset=UTF-8'
            );
            wp_mail($module_email, $subject, $message, $headers);
            echo "Notification email sent for Document ID: " . $document->ID . "<br>";
        }
    }
}
