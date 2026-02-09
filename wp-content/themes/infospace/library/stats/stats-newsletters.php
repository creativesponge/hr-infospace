<?php
// Add admin menu for Newsletters report
function add_newsletters_report_submenu()
{
    add_submenu_page(
        'reporting',
        'Newsletters Report',
        'Newsletters Report',
        'access_module_admin_page',
        'newsletters-report',
        'newsletters_report_page_callback'
    );
}
add_action('admin_menu', 'add_newsletters_report_submenu');

function newsletters_report_page_callback()
{
    // Handle CSV export first to avoid unnecessary processing
    if (isset($_GET['export_csv'])) {
        infospace_export_newsletters_report_csv_ftn();
        return;
    }

    // Cache documents and modules queries
    static $newsletters = null;
    static $modules = null;

    if ($newsletters === null) {
        $newsletters = get_posts(array(
            'post_type' => 'newsletter',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
            'fields' => 'ids' // Only get IDs first
        ));
    }

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

    // Sanitize inputs once
    $start_date = sanitize_text_field($_GET['start_date'] ?? '');
    $end_date = sanitize_text_field($_GET['end_date'] ?? '');
    $page_id = intval($_GET['newsletter_id'] ?? 0);
    $module_id = intval($_GET['module_id'] ?? 0);
    global $finance_module_id, $hr_module_id, $hsw_module_id;
    $current_user = wp_get_current_user();
    $roles = (array) $current_user->roles;
    $restricted_module_id = 0;
    if (in_array('finance_editor', $roles, true)) {
        $restricted_module_id = intval($finance_module_id);
    } elseif (in_array('hr_editor', $roles, true)) {
        $restricted_module_id = intval($hr_module_id);
    } elseif (in_array('hsw_editor', $roles, true)) {
        $restricted_module_id = intval($hsw_module_id);
    }
    if ($restricted_module_id) {
        $module_id = $restricted_module_id;
    }
    $sort_order = ($_GET['sort_order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

    // Buffer output for better performance
    ob_start();
?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="wrap">
        <h1>Newsletters Report</h1>
        <br><br>

        <form method="get" action="">
            <input type="hidden" name="page" value="newsletters-report">

            <label for="start_date">Start Date:&nbsp;</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo esc_attr($start_date); ?>">

            &nbsp;&nbsp;<label for="end_date">End Date:&nbsp;</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo esc_attr($end_date); ?>">

            <br><br>

            <label for="page_id">Newsletter:&nbsp;</label>
            <select name="newsletter_id" id="page_id">
                <option value="">All Newsletters</option>
                <?php
                foreach ($newsletters as $newsletterid) {
                    $doc_title = get_the_title($newsletterid);
                    $selected = ($newsletterid == $page_id) ? ' selected' : '';
                    echo '<option value="' . esc_attr($newsletterid) . '"' . $selected . '>' . esc_html($doc_title) . '</option>';
                }
                ?>
            </select>

            <br><br><br><br>

            <label for="module_id">Module:&nbsp;</label>
            <?php if ($restricted_module_id): ?>
                <?php $module_title = get_the_title($restricted_module_id); ?>
                <input type="hidden" name="module_id" value="<?php echo esc_attr($restricted_module_id); ?>">
                <span><?php echo $module_title ? esc_html($module_title) : 'Module ' . esc_html($restricted_module_id); ?></span>
            <?php else: ?>
                <select name="module_id" id="module_id">
                    <option value="">All Modules</option>
                    <?php
                    foreach ($modules as $mod_id) {
                        $mod_title = get_the_title($mod_id);
                        $selected = ($module_id == $mod_id) ? ' selected' : '';
                        echo '<option value="' . esc_attr($mod_id) . '"' . $selected . '>' . esc_html($mod_title) . '</option>';
                    }
                    ?>
                </select>
            <?php endif; ?>

            <br><br>

            <label for="sort_order">Sort by Downloads:&nbsp;</label>
            <select name="sort_order" id="sort_order">
                <option value="DESC" <?php echo ($sort_order === 'DESC') ? ' selected' : ''; ?>>Highest to Lowest</option>
                <option value="ASC" <?php echo ($sort_order === 'ASC') ? ' selected' : ''; ?>>Lowest to Highest</option>
            </select>

            <br><br>
            <input type="submit" value="Filter" class="button button-primary">
        </form>

        <form method="get" action="" style="margin-top: 10px;">
            <input type="hidden" name="page" value="newsletters-report">
            <input type="hidden" name="export_csv" value="1">
            <?php if ($start_date): ?><input type="hidden" name="start_date" value="<?php echo esc_attr($start_date); ?>"><?php endif; ?>
            <?php if ($end_date): ?><input type="hidden" name="end_date" value="<?php echo esc_attr($end_date); ?>"><?php endif; ?>
            <?php if ($page_id): ?><input type="hidden" name="newsletter_id" value="<?php echo esc_attr($page_id); ?>"><?php endif; ?>
            <?php if ($module_id): ?><input type="hidden" name="module_id" value="<?php echo esc_attr($module_id); ?>"><?php endif; ?>
            <input type="hidden" name="sort_order" value="<?php echo esc_attr($sort_order); ?>">
            <input type="submit" value="Export as CSV" class="button button-secondary">
        </form>

        <?php
        // Get report data
        $report_data = get_newsletters_report_data($start_date, $end_date, $page_id, $module_id, $sort_order);

        if ($report_data) {
            $report_title = ($sort_order === 'DESC') ? 'Top 40 Most Viewed Pages' : 'Top 40 Least Popular Pages';
        ?>
            <?php
            // Display the newsletters report table and chart
            function display_newsletters_report_table_and_chart($report_data, $report_title)
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
                            <th>Unique Newsletters Count</th>
                            <th>Total Newsletters Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_data as $row): ?>

                            <tr>
                                <td><?php echo esc_html($row->object_id); ?></td>
                                <td><?php echo esc_html($row->repr); ?></td>
                                <td><?php echo esc_html($row->unique_downloads); ?></td>
                                <td><?php echo esc_html($row->access_count); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php
                // Prepare chart data
                $chart_data = array(array('Document Name', 'Total Newsletters', 'Unique Newsletters'));
                foreach ($report_data as $row) {
                    $chart_data[] = array($row->repr, intval($row->access_count), intval($row->unique_downloads));
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
                            title: "Newsletter Statistics",
                            hAxes: {
                                0: {
                                    title: "Downloads"
                                }
                            },
                            vAxes: {
                                0: {
                                    title: "Newsletters"
                                }
                            },
                            series: {
                                0: {
                                    color: "#1f77b4",
                                    name: "Total Downloads"
                                },
                                1: {
                                    color: "#ff7f0e",
                                    name: "Unique Downloads"
                                }
                            }
                        };

                        var chart = new google.visualization.BarChart(document.getElementById("document-chart"));
                        chart.draw(data, options);
                    }
                </script>
        <?php
            }

            // Call the function
            display_newsletters_report_table_and_chart($report_data, $report_title);
        } else {
            echo '<p>No newsletter access data found for the selected criteria.</p>';
        }
        ?>
    </div>
<?php
    echo ob_get_clean();
}

// Separate function for database queries
function get_newsletters_report_data($start_date, $end_date, $page_id, $module_id, $sort_order)
{
    global $wpdb, $prefix;

    $where_conditions = array("content_type_id = 9");
    $where_params = array();

    if ($start_date) {
        $where_conditions[] = "DATE(created) >= %s";
        $where_params[] = $start_date;
    }

    if ($end_date) {
        $where_conditions[] = "DATE(created) <= %s";
        $where_params[] = $end_date;
    }

    if ($page_id) {
        $where_conditions[] = "object_id = %d";
        $where_params[] = $page_id;
    }

    if ($module_id) {
        $attached_page_id = get_post_meta($module_id, $prefix . 'module_attached_resources', true);
        if ($attached_page_id) {
            function get_all_child_pages($parent_id)
            {
                $child_pages = get_posts(array(
                    'post_type' => 'resource_page',
                    'post_parent' => $parent_id,
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'fields' => 'ids'
                ));

                $all_pages = $child_pages;

                foreach ($child_pages as $child_id) {
                    $all_pages = array_merge($all_pages, get_all_child_pages($child_id));
                }

                return $all_pages;
            }

            $child_pages = get_all_child_pages($attached_page_id);
            $page_ids = array_merge(array($attached_page_id), $child_pages);



            if ($page_ids) {
                $page_ids = array_unique(array_filter(array_map('intval', $page_ids)));
                // Get newsletters that have any of the page IDs in their attached resource pages meta field
                $newsletter_ids = array();
                $newsletters = get_posts(array(
                    'post_type' => 'newsletter',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'fields' => 'ids'
                ));

                foreach ($newsletters as $newsletter_id) {
                    $attached_pages = get_post_meta($newsletter_id, $prefix . 'newsletter_attached_resource_pages', true);
                    if (is_array($attached_pages) && array_intersect($attached_pages, $page_ids)) {
                        $newsletter_ids[] = $newsletter_id;
                    }
                }

                if ($newsletter_ids) {
                    $where_conditions[] = "object_id IN (" . implode(',', array_map('intval', $newsletter_ids)) . ")";
                }
            }
        }
    }

    $where_clause = "WHERE " . implode(" AND ", $where_conditions);

    $query_sql = "
        SELECT object_id, repr, 
               COUNT(*) as access_count,
               COUNT(DISTINCT ip_address) as unique_downloads
        FROM {$wpdb->prefix}user_logs
        {$where_clause}
        GROUP BY object_id
        ORDER BY access_count {$sort_order}
        LIMIT 40
    ";
    $query = $where_params ? $wpdb->prepare($query_sql, $where_params) : $query_sql;

    return $wpdb->get_results($query);
}

// Handle CSV export
add_action('admin_init', 'infospace_export_newsletters_report_csv');

function infospace_export_newsletters_report_csv()
{
    if (isset($_GET['export_csv']) && $_GET['export_csv'] == '1' && isset($_GET['page']) && $_GET['page'] == 'newsletters-report') {
        if (!current_user_can('access_module_admin_page')) {
            wp_die('You do not have permission to access this page.');
        }

        infospace_export_newsletters_report_csv_ftn();
        exit;
    }
}

function infospace_export_newsletters_report_csv_ftn()
{
    $start_date = sanitize_text_field($_GET['start_date'] ?? '');
    $end_date = sanitize_text_field($_GET['end_date'] ?? '');
    $page_id = intval($_GET['newsletter_id'] ?? 0);
    $module_id = intval($_GET['module_id'] ?? 0);
    global $finance_module_id, $hr_module_id, $hsw_module_id;
    $current_user = wp_get_current_user();
    $roles = (array) $current_user->roles;
    $restricted_module_id = 0;
    if (in_array('finance_editor', $roles, true)) {
        $restricted_module_id = intval($finance_module_id);
    } elseif (in_array('hr_editor', $roles, true)) {
        $restricted_module_id = intval($hr_module_id);
    } elseif (in_array('hsw_editor', $roles, true)) {
        $restricted_module_id = intval($hsw_module_id);
    }
    if ($restricted_module_id) {
        $module_id = $restricted_module_id;
    }
    $sort_order = ($_GET['sort_order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

    $results = get_newsletters_report_data($start_date, $end_date, $page_id, $module_id, $sort_order);

    $filename = 'newsletter_report_' . date('Y-m-d_H-i-s') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Object ID', 'Document Name', 'Unique Downloads', 'Total Downloads'));

    foreach ($results as $row) {
        fputcsv($output, array(
            $row->object_id,
            $row->repr,
            $row->unique_downloads,
            $row->access_count
        ));
    }

    fclose($output);
}
