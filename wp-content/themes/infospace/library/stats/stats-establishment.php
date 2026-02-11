<?php
// Add admin menu for Establishment report

// Register the page but do not add it to the admin menu
function register_establishment_report_page() {
    add_submenu_page(
        null, // No parent slug, so it won't appear in the menu
        'Establishment Report',
        'Establishment Report',
        'manage_options',
        'establishment-report',
        'establishment_report_page_callback'
    );
}
add_action('admin_menu', 'register_establishment_report_page');

function establishment_report_page_callback()
{
    global $prefix;
    // Get user ID from URL parameter
    $user_id = intval($_GET['user_id'] ?? 0);

    $report_type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $created_users = array();
    if ($user_id) {

        if ($report_type === 'establishment') {
            $args = array(
                'meta_key' => $prefix . 'user_created_by',
                'meta_value' => $user_id,
                'role__in' => array('individual'),
                'fields' => 'ID'
            );
            $created_users = get_users($args);
        }
        $created_users[] = $user_id;
    } else {
        // Handle case when no user_id is provided
        echo '<div class="wrap">';
        echo '<h1>Establishment Report</h1>';
        echo '<div class="notice notice-error"><p>Error: User ID is required to view this report. Please provide a valid user_id parameter.</p></div>';
        echo '</div>';
        return;
    };


    // Sanitize inputs once
    $start_date = sanitize_text_field($_GET['start_date'] ?? '');
    $end_date = sanitize_text_field($_GET['end_date'] ?? '');
   // var_dump( $start_date, $end_date);
    //$page_id = intval($_GET['newsletter_id'] ?? 0);
    //$module_id = intval($_GET['module_id'] ?? 0);
    $sort_order = ($_GET['sort_order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

    // Buffer output for better performance
    ob_start();
?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="wrap">
        <h1>Establishment Report</h1>
        <br><br>

        <form method="get" action="">
            <input type="hidden" name="page" value="establishment-report">
            <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
            <input type="hidden" name="type" value="<?php echo esc_attr($report_type); ?>">
        
            <label for="start_date">Start Date:&nbsp;</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo esc_attr($start_date); ?>">

            &nbsp;&nbsp;<label for="end_date">End Date:&nbsp;</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo esc_attr($end_date); ?>">
            <br><br>

            <label for="sort_order">Sort by:&nbsp;</label>
            <select name="sort_order" id="sort_order">
                <option value="DESC" <?php echo ($sort_order === 'DESC') ? ' selected' : ''; ?>>Highest to Lowest</option>
                <option value="ASC" <?php echo ($sort_order === 'ASC') ? ' selected' : ''; ?>>Lowest to Highest</option>
            </select>
            <br><br>
            <input type="submit" value="Filter" class="button button-primary">
        </form>

        <!--<form method="get" action="" style="margin-top: 10px;">
            <input type="hidden" name="page" value="establishment-report">
            <input type="hidden" name="export_csv" value="1">
            <?php //if ($start_date): 
            ?><input type="hidden" name="start_date" value="<?php //echo esc_attr($start_date); 
                                                            ?>"><?php //endif; 
                                                                ?>
            <?php //if ($end_date): 
            ?><input type="hidden" name="end_date" value="<?php //echo esc_attr($end_date); 
                                                            ?>"><?php //endif; 
                                                                ?>
            <input type="hidden" name="sort_order" value="<?php //echo esc_attr($sort_order); 
                                                            ?>">
            <input type="submit" value="Export as CSV" class="button button-secondary">
        </form>-->

        <?php

        //Logins
        $report_data = get_login_report_data($start_date, $end_date, $sort_order, $created_users);
        $report_title = "Logins";

        display_login_report_table_and_chart($report_data, $report_title);

        // Resources
        $report_data = get_page_views_report_data($start_date, $end_date, null, null, $sort_order, $created_users);
        $report_title = "Page views";
        display_page_views_report_table_and_chart($report_data, $report_title);

        //Downloads
        
        $report_data = get_document_report_data($start_date, $end_date, null, null, $sort_order, $created_users);
        $report_title = "Downloads";

        display_document_report_table_and_chart($report_data, $report_title);

        // Activity
        $report_data = get_activity_report_data($start_date, $end_date, $sort_order, $created_users);
        $report_title = "Activity";
        display_activity_report_table_and_chart($report_data, $report_title);


        ?>
    </div>
<?php
    echo ob_get_clean();
}

function get_activity_report_data($start_date, $end_date, $sort_order, $users = null)
{
    global $wpdb;

    //$where_conditions = array("content_type_id = 14");
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
                SELECT *
                FROM {$wpdb->prefix}user_logs
                {$where_clause}
                ORDER BY created {$sort_order}
                LIMIT 40
            ", $where_params);

    return $wpdb->get_results($query);
}

function display_activity_report_table_and_chart($report_data, $report_title)
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
                <th>Date</th>
                <th>User</th>
                <th>Name</th>
                <th>Content type</th>
                <th>Action</th>
                <th>Path</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report_data as $row): ?>


                <?php
                $user_info = get_userdata($row->user_id);
                $user_name = $user_info ? $user_info->display_name : 'Unknown User';
                $contentType = $row->content_type_id;
                $contentType = $row->content_type_id == 12 ? "Document" : $contentType;
                $contentType = $row->content_type_id == 10 ? "Resource page" : $contentType;
                $contentType = $row->content_type_id == 19 ? "Link" : $contentType;
                $contentType = $row->content_type_id == 14 ? "Login" : $contentType;
                $contentType = $row->content_type_id == 8 ? "News" : $contentType;

                ?>

                <tr>
                    <td><strong><?php echo esc_html(date('d/m/Y H:i', strtotime($row->created))); ?></strong></td>
                    <td><strong><?php echo esc_html($user_name); ?></strong></td>
                    <td><?php echo esc_html($row->repr); ?></td>
                    <td><strong><?php echo esc_html($contentType); ?></strong></td>
                    <td><strong><?php echo esc_html($row->action); ?></strong></td>
                    <td><?php echo esc_html($row->path); ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>



<?php } ?>