<?php

/*function add_reporting_admin_page() {
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
*/
function stats_page_callback() {
    echo '<div class="wrap">';
    echo '<h1>Alerts</h1>';
    echo '<p>Alerts content goes here.</p>';
    echo '</div>';
}