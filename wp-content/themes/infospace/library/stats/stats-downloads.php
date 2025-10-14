<?php
// Add admin menu for document report
function add_document_report_submenu()
{
    add_submenu_page(
        'reporting',
        'Document Report',
        'Document Report',
        'manage_options',
        'document-report',
        'document_report_page_callback'
    );
}
add_action('admin_menu', 'add_document_report_submenu');

function document_report_page_callback()
{
    // Include Google Charts script
    echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
    echo '<div class="wrap">';
    echo '<h1>Document Report</h1>';
    echo "<br><br>";
    // Date filter form
    echo '<form method="get" action="">';
        echo '<input type="hidden" name="page" value="document-report">';
        echo '<label for="start_date">Start Date:&nbsp;</label>';
        echo '<input type="date" name="start_date" id="start_date" value="' . esc_attr($_GET['start_date'] ?? '') . '">';
        echo '&nbsp;&nbsp;<label for="end_date">End Date:&nbsp;</label>';
        echo '<input type="date" name="end_date" id="end_date" value="' . esc_attr($_GET['end_date'] ?? '') . '">';
        
        // Add document filter dropdown
        echo "<br><br>";
        echo '<label for="document_id">Document:&nbsp;</label>';
        echo '<select name="document_id" id="document_id">';
        echo '<option value="">All Documents</option>';

        $documents = get_posts(array(
            'post_type' => 'document',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        
        $selected_document_id = $_GET['document_id'] ?? '';
        
        foreach ($documents as $document) {
            $selected = ($selected_document_id == $document->ID) ? ' selected' : '';
            echo '<option value="' . esc_attr($document->ID) . '"' . $selected . '>' . esc_html($document->post_title) . '</option>';
        }
        
        echo '</select>';
        echo "<br><br>";
        // Add sort order dropdown
        echo '<label for="sort_order">Sort by Downloads:&nbsp;</label>';
        echo '<select name="sort_order" id="sort_order">';
        $selected_sort_order = $_GET['sort_order'] ?? 'DESC';
        echo '<option value="DESC"' . ($selected_sort_order == 'DESC' ? ' selected' : '') . '>Highest to Lowest</option>';
        echo '<option value="ASC"' . ($selected_sort_order == 'ASC' ? ' selected' : '') . '>Lowest to Highest</option>';
        echo '</select>';
        echo "<br><br>";
        echo '<input type="submit" value="Filter" class="button button-primary">';
        echo '</form>';

    // Add Export CSV button
    echo '<form method="get" action="" style="margin-top: 10px;">';
    echo '<input type="hidden" name="page" value="document-report">';
    echo '<input type="hidden" name="export_csv" value="1">';


    if (!empty($_GET['start_date'])) {
        echo '<input type="hidden" name="start_date" value="' . esc_attr($_GET['start_date']) . '">';
    }
    if (!empty($_GET['end_date'])) {
        echo '<input type="hidden" name="end_date" value="' . esc_attr($_GET['end_date']) . '">';
    }
    if (!empty($_GET['document_id'])) {
        echo '<input type="hidden" name="document_id" value="' . esc_attr($_GET['document_id']) . '">';
    }
    if (!empty($_GET['sort_order'])) {
        echo '<input type="hidden" name="sort_order" value="' . esc_attr($_GET['sort_order']) . '">';
    }
    echo '<input type="submit" value="Export as CSV" class="button button-secondary">';
    echo '</form>';

    // Process the filter
    global $wpdb;
    $where_clause = "WHERE content_type_id = 12";

    // Original detailed query below
    if (!empty($_GET['start_date'])) {
        $start_date = sanitize_text_field($_GET['start_date']);
        $where_clause .= $wpdb->prepare(" AND DATE(created) >= %s", $start_date);
    }

    if (!empty($_GET['end_date'])) {
        $end_date = sanitize_text_field($_GET['end_date']);
        $where_clause .= $wpdb->prepare(" AND DATE(created) <= %s", $end_date);
    }
    // Add document filter to where clause if specified
    if (!empty($_GET['document_id'])) {
        $document_id = intval($_GET['document_id']);
        $where_clause .= $wpdb->prepare(" AND object_id = %d", $document_id);
    }
    
    // Get sort order
    $sort_order = (!empty($_GET['sort_order']) && $_GET['sort_order'] == 'ASC') ? 'ASC' : 'DESC';
    $report_title = ($sort_order == 'DESC') ? 'Top 40 Most Popular Documents' : 'Top 40 Least Popular Documents';
    
    // Get top 40 most/least popular documents based on object_id frequency
    $popular_results = $wpdb->get_results($wpdb->prepare("
        SELECT object_id, repr, user_id, action, created, 
               COUNT(*) as access_count,
               COUNT(DISTINCT ip_address) as unique_downloads
        FROM {$wpdb->prefix}user_logs
        {$where_clause}
        GROUP BY object_id
        ORDER BY access_count {$sort_order}
        LIMIT 40
    "));

    if ($popular_results) {
        echo '<h2>' . esc_html($report_title) . '</h2>';
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr><th>Object ID</th><th>Name</th><th>Unique Download Count</th><th>Total Download Count</th><!--<th>User ID</th><th>Action</th><th>Created</th>--></tr></thead>';
        echo '<tbody>';
        foreach ($popular_results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->object_id) . '</td>';
            echo '<td>' . esc_html($row->repr) . '</td>';
            echo '<td>' . esc_html($row->unique_downloads) . '</td>';
            echo '<td>' . esc_html($row->access_count) . '</td>';


            //echo '<td>' . esc_html($row->user_id) . '</td>';
            //echo '<td>' . esc_html($row->action) . '</td>';

            //echo '<td>' . esc_html($row->created) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        // Prepare data for wpDataTables chart
        $chart_data = array();
        $chart_data[] = array('Document Name', 'Total Downloads', 'Unique Downloads');

        foreach ($popular_results as $row) {
            $chart_data[] = array(
                $row->repr,
                intval($row->access_count),
                intval($row->unique_downloads)
            );
        }

        // Convert to JSON for JavaScript
        $chart_json = json_encode($chart_data);

        echo '<div id="document-chart" style="width: 100%; height: 400px; margin-top: 20px;"></div>';
        echo '<script>
            google.charts.load("current", {"packages":["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            
            function drawChart() {
                var data = google.visualization.arrayToDataTable(' . $chart_json . ');
                
                var options = {
                    title: "Document Download Statistics",
                    hAxes: {
                        0: {
                            title: "Downloads"
                        }
                    },
                    vAxes: {
                        0: {
                            title: "Documents"
                        }
                    },
                    series: {
                        0: {color: "#1f77b4", name: "Total Downloads"},
                        1: {color: "#ff7f0e", name: "Unique Downloads"}
                    }
                };
                
                var chart = new google.visualization.BarChart(document.getElementById("document-chart"));
                chart.draw(data, options);
            }
        </script>';
    } else {
        echo '<p>No document access data found for the selected date range.</p>';
    }
}

// Add the CSV export function

// Handle CSV export
add_action('init', 'infospace_export_document_report_csv');

function infospace_export_document_report_csv()
{
    if (isset($_GET['export_csv']) && $_GET['export_csv'] == '1') {
        // Check if user is logged in and has admin capabilities
        if (!is_user_logged_in() || !current_user_can('manage_options')) {
            wp_die('You do not have permission to access this page.');
        }
        
        infospace_export_document_report_csv_ftn();
        return;
    }
}
function infospace_export_document_report_csv_ftn()
{
    global $wpdb;

    $where_clause = "WHERE content_type_id = 12";

    if (!empty($_GET['start_date'])) {
        $start_date = sanitize_text_field($_GET['start_date']);
        $where_clause .= $wpdb->prepare(" AND DATE(created) >= %s", $start_date);
    }

    if (!empty($_GET['end_date'])) {
        $end_date = sanitize_text_field($_GET['end_date']);
        $where_clause .= $wpdb->prepare(" AND DATE(created) <= %s", $end_date);
    }

    if (!empty($_GET['document_id'])) {
        $document_id = intval($_GET['document_id']);
        $where_clause .= $wpdb->prepare(" AND object_id = %d", $document_id);
    }

    // Get sort order for CSV export
    $sort_order = (!empty($_GET['sort_order']) && $_GET['sort_order'] == 'ASC') ? 'ASC' : 'DESC';

    // Get the same data as the report
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT object_id, repr, user_id, action, created, 
               COUNT(*) as access_count,
               COUNT(DISTINCT ip_address) as unique_downloads
        FROM {$wpdb->prefix}user_logs
        {$where_clause}
        GROUP BY object_id
        ORDER BY access_count {$sort_order}
        LIMIT 40
    "));

    // Set headers for CSV download
    $filename = 'document_report_' . date('Y-m-d_H-i-s') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Create file pointer connected to output stream
    $output = fopen('php://output', 'w');

    // Add CSV headers
    fputcsv($output, array('Object ID', 'Document Name', 'Unique Downloads', 'Total Downloads'));

    // Add data rows
    foreach ($results as $row) {
        fputcsv($output, array(
            $row->object_id,
            $row->repr,
            $row->unique_downloads,
            $row->access_count
        ));
    }

    fclose($output);
    exit();
}
