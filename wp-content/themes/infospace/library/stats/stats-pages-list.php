<?php

function add_reporting_admin_page() {
    add_menu_page(
        'Reporting',
        'Reporting',
        'manage_options',
        'reporting',
        'reporting_page_callback',
        'dashicons-chart-bar',
        30
    );
}
add_action('admin_menu', 'add_reporting_admin_page');

function reporting_page_callback() {
    echo '<div class="wrap">';
    echo '<h1>Reporting</h1>';
    echo '<p><a href="admin.php?page=document-report">View document downloads</a></p>';
    echo '<p><a href="admin.php?page=page-views-report">View page views report</a></p>';
    echo '<p><a href="admin.php?page=newsletters-report">View newsletters report</a></p>';
    echo '<p><a href="admin.php?page=logins-report">View logins report</a></p>';
    echo '</div>';
}