<?php
// Add admin menu for Login report
function add_login_report_submenu()
{
    add_submenu_page(
        'reporting',
        'Login Report',
        'Login Report',
        'manage_options',
        'login-report',
        'login_report_page_callback'
    );
}
add_action('admin_menu', 'add_login_report_submenu');

function login_report_page_callback()
{
    // Handle CSV export first to avoid unnecessary processing
    if (isset($_GET['export_csv'])) {
        infospace_export_login_report_csv_ftn();
        return;
    }

    // Cache documents and modules queries
    static $Login = null;
    //static $users = null;
    /*static $modules = null;
    
    
    
    if ($modules === null) {
        $modules = get_posts(array(
            'post_type' => 'module',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
            'fields' => 'ids' // Only get IDs first
        ));
    }
*/
    // Sanitize inputs once
    $start_date = sanitize_text_field($_GET['start_date'] ?? '');
    $end_date = sanitize_text_field($_GET['end_date'] ?? '');


    //$module_id = intval($_GET['module_id'] ?? 0);
    $sort_order = ($_GET['sort_order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

    // Buffer output for better performance
    ob_start();
?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="wrap">
        <h1>Login Report</h1>
        <br><br>

        <form method="get" action="">
            <input type="hidden" name="page" value="login-report">

            <label for="start_date">Start Date:&nbsp;</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo esc_attr($start_date); ?>">

            &nbsp;&nbsp;<label for="end_date">End Date:&nbsp;</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo esc_attr($end_date); ?>">

            <br><br>

            <label for="sort_order">Sort by logins:&nbsp;</label>
            <select name="sort_order" id="sort_order">
                <option value="DESC" <?php echo ($sort_order === 'DESC') ? ' selected' : ''; ?>>Highest to Lowest</option>
                <option value="ASC" <?php echo ($sort_order === 'ASC') ? ' selected' : ''; ?>>Lowest to Highest</option>
            </select>

            <br><br>
            <input type="submit" value="Filter" class="button button-primary">
        </form>

        <form method="get" action="" style="margin-top: 10px;">

            <input type="hidden" name="page" value="login-report">
            <input type="hidden" name="export_csv" value="1">
            <?php if ($start_date): ?><input type="hidden" name="start_date" value="<?php echo esc_attr($start_date); ?>"><?php endif; ?>
            <?php if ($end_date): ?><input type="hidden" name="end_date" value="<?php echo esc_attr($end_date); ?>"><?php endif; ?>

            <input type="hidden" name="sort_order" value="<?php echo esc_attr($sort_order); ?>">
            <input type="submit" value="Export as CSV" class="button button-secondary">
        </form>

        <?php
        // Get report data
        $report_data = get_login_report_data($start_date, $end_date, $sort_order, $users = null);

        if ($report_data) {
            $report_title = ($sort_order === 'DESC') ? 'Top 40 Most Logged In Users' : 'Top 40 Least Logged In Users';
        ?>
            <?php
            display_login_report_table_and_chart($report_data, $report_title);

            ?>
        <?php
        } else {
            echo '<p>No login access data found for the selected criteria.</p>';
        }
        ?>
    </div>
<?php
    echo ob_get_clean();
}

// Separate function for database queries
function get_login_report_data($start_date, $end_date, $sort_order, $users = null)
{
    global $wpdb;

    $where_conditions = array("content_type_id = 14");
    $where_params = array();

    if ($start_date) {
        $where_conditions[] = "DATE(created) >= %s";
        $where_params[] = $start_date;
    }

    if ($end_date) {
        $where_conditions[] = "DATE(created) <= %s";
        $where_params[] = $end_date;
    }

    if ($users && is_array($users) && !empty($users)) {
        $user_ids = array_unique(array_filter(array_map('intval', $users)));
        if ($user_ids) {
            $where_conditions[] = "user_id IN (" . implode(',', $user_ids) . ")";
        }
    }

    $where_clause = "WHERE " . implode(" AND ", $where_conditions);

    $query = $wpdb->prepare("
        SELECT object_id, repr, 
               COUNT(*) as access_count,
               COUNT(DISTINCT ip_address) as unique_logins
        FROM {$wpdb->prefix}user_logs
        {$where_clause}
        GROUP BY object_id
        ORDER BY access_count {$sort_order}
        LIMIT 40
    ", $where_params);

    return $wpdb->get_results($query);
}

function display_login_report_table_and_chart($report_data, $report_title)
{
    if (empty($report_data)) {
        echo '<p>No data available for the selected criteria.</p>';
        return;
    }
?>
    <h2><?php echo esc_html($report_title); ?></h2>
    <table class="widefat fixed striped">
        <thead>
            <tr>
                <th>Object ID</th>
                <th>Name</th>
                <th>Unique Login Count</th>
                <th>Total Login Count</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report_data as $row): ?>
                <tr>
                    <td><?php echo esc_html($row->object_id); ?></td>
                    <td><?php echo esc_html($row->repr); ?></td>
                    <td><?php echo esc_html($row->unique_logins); ?></td>
                    <td><?php echo esc_html($row->access_count); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    // Prepare chart data
    $chart_data = array(array('Document Name', 'Total Logins', 'Unique Logins'));
    foreach ($report_data as $row) {
        $chart_data[] = array($row->repr, intval($row->access_count), intval($row->unique_logins));
    }
    ?>

    <div id="document-chart" style="width: 100%; height: 400px; margin-top: 20px;"></div>
    <script>
        google.charts.load("current", {
            "packages": ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo wp_json_encode($chart_data); ?>);

            var options = {
                title: "Login Statistics",
                hAxes: {
                    0: {
                        title: "Logins"
                    }
                },
                vAxes: {
                    0: {
                        title: "Login"
                    }
                },
                series: {
                    0: {
                        color: "#1f77b4",
                        name: "Total Logins"
                    },
                    1: {
                        color: "#ff7f0e",
                        name: "Unique Logins"
                    }
                }
            };

            var chart = new google.visualization.BarChart(document.getElementById("document-chart"));
            chart.draw(data, options);
        }
    </script>
<?php
}


// Handle CSV export
add_action('admin_init', 'infospace_export_login_report_csv');

function infospace_export_login_report_csv()
{
    if (isset($_GET['export_csv']) && $_GET['export_csv'] == '1' && isset($_GET['page']) && $_GET['page'] == 'login-report') {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have permission to access this page.');
        }

        infospace_export_login_report_csv_ftn();
        exit;
    }
}

function infospace_export_login_report_csv_ftn()
{

    // $module_id = intval($_GET['module_id'] ?? 0);
    $sort_order = ($_GET['sort_order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
    $start_date = sanitize_text_field($_GET['start_date'] ?? '');
    $end_date = sanitize_text_field($_GET['end_date'] ?? '');
    $users = null;
    $results = get_login_report_data($start_date, $end_date, $sort_order, $users);

    $filename = 'login_report_' . date('Y-m-d_H-i-s') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Object ID', 'Document Name', 'Unique Logins', 'Total Logins'));

    foreach ($results as $row) {
        fputcsv($output, array(
            $row->object_id,
            $row->repr,
            $row->unique_logins,
            $row->access_count
        ));
    }

    fclose($output);
}
