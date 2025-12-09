<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}


function add_alerts_admin_page()
{
    add_menu_page(
        'Alerts',
        'Alerts',
        'manage_options',
        'alerts',
        'alerts_page_callback',
        'dashicons-warning',
        30
    );
}
add_action('admin_menu', 'add_alerts_admin_page');


function alerts_page_callback()
{

    // Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Verify nonce for form submissions
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    // Only verify nonce if filter parameters are present and page parameter exists
    if (isset($_GET['page']) && $_GET['page'] === 'alerts' && (isset($_GET['role']) || isset($_GET['hsw_alerts']) || isset($_GET['finance_alerts']) || isset($_GET['hr_alerts']) || isset($_GET['paged']))) {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'alerts_filter_nonce')) {
            wp_die(__('Security check failed.'));
        }
    }
}
    $alert_content = '';
    $alert_subject = '';
    $module_type = '';

    echo '<div class="wrap">';
    echo '<h1>Alerts</h1>';
    echo "<br>";


    // Handle form submission
    if (isset($_POST['send_alert']) && wp_verify_nonce($_POST['send_alert_nonce'], 'send_alert_action')) {
        $module_type = sanitize_text_field($_POST['module_type']);
        $subject = sanitize_text_field($_POST['alert_subject']);
        $content = wp_kses_post($_POST['alert_content']);
        $module_email = 'EHRpolicy@norfolk.gov.uk'; // Default email address
        $module_number = '01603 307760'; // Default phone number
        // Get module meta data and phone number
        if (!empty($module_type) && is_numeric($module_type)) {
            $module_meta = get_post_meta($module_type);
            $module_number = $module_meta['theme_fieldsmodule_phone_number'][0] ?? $module_number;
            $module_email = $module_meta['theme_fieldsmodule_email_address'][0] ?? $module_email;
        }

        // Check if form was submitted (even if there were errors)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alert_content = isset($_POST['alert_content']) ? wp_kses_post($_POST['alert_content']) : '';
            $alert_subject = isset($_POST['alert_subject']) ? sanitize_text_field($_POST['alert_subject']) : '';
            $module_type = isset($_POST['module_type']) ? sanitize_text_field($_POST['module_type']) : '';
            $email_addresses = isset($_POST['email_addresses']) ? sanitize_text_field($_POST['email_addresses']) : '';
        }
        // Process the alert here
        //echo '<div class="notice notice-success is-dismissible">';
        // echo '<p>Alert sent successfully!</p>';
        // echo '</div>';

        echo '<h2>Alert preview</h2>';
        echo '<h3><strong>Subject:</strong> ' . esc_html($subject) . '</h3>';
        
        echo '<button onclick="window.history.back();">Back</button><br><br>';
?>
        <div style="max-width: 700px;">
            <style type="text/css">
                /* FONTS */
                @media screen {
                    @font-face {
                        font-family: '';
                        font-style: normal;
                        font-weight: 400;
                        src: 'Poppins', 'Poppins-Regular';
                    }

                    @font-face {
                        font-family: 'Poppins';
                        font-style: normal;
                        font-weight: 700;
                        src: 'Poppins Bold', 'Poppins-Bold';
                    }
                }

                @font-face {
                    font-family: 'Poppins Light';
                    font-style: normal;
                    font-weight: 300;
                    src: 'Poppins Light', 'Poppins-Light';
                }


                /* CLIENT-SPECIFIC STYLES */
                body,
                table,
                td,
                a {
                    -webkit-text-size-adjust: 100%;
                    -ms-text-size-adjust: 100%;
                }

                table,
                td {
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                }

                img {
                    -ms-interpolation-mode: bicubic;
                }

                /* RESET STYLES */
                img {
                    border: 0;
                    height: auto;
                    line-height: 100%;
                    outline: none;
                    text-decoration: none;
                }

                table {
                    border-collapse: collapse !important;
                }

                body {
                    height: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    width: 100% !important;
                }

                /* iOS BLUE LINKS */
                a[x-apple-data-detectors] {
                    color: inherit !important;
                    text-decoration: none !important;
                    font-size: inherit !important;
                    font-family: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                }

                /* MOBILE STYLES */
                @media screen and (max-width:600px) {
                    h1 {
                        font-size: 32px !important;
                        line-height: 32px !important;
                    }

                    table[class="wrapper"] {
                        width: 100% !important;
                    }

                    table[class="mobileleft"] {
                        float: left !important;
                    }

                    td[class="removepadding"] {
                        padding-top: 0px !important;
                    }
                }

                /* ANDROID CENTER FIX */
                div[style*="margin: 16px 0;"] {
                    margin: 0 !important;
                }
            </style>


            <div style="background-color: #ffffff; margin: 0 !important; padding: 0 !important;">
                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                    <!-- LOGO -->
                    <tr>
                        <td bgcolor="#FFA73B" align="center">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" class="wrapper">
                                <tr>
                                    <td align="center" valign="top">
                                        <div style="height:20px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- HERO -->
                    <tr>
                        <td align="center" style="padding: 0px 10px 0px 10px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" class="wrapper">
                                <tr>
                                    <td bgcolor="#ffffff" align="center" valign="top" style="border-radius: 4px 4px 0px 0px;">
                                        <table width="303" border="0" cellspacing="0" cellpadding="0" align="left">
                                            <tbody>
                                                <tr>
                                                    <td width="221"><a href="https://{{ site.domain }}" target="_blank"><img src="https://www.infospace.org.uk/static/images/emails/headerlogo-infospace.png" width="270" height="107" alt="Infospace logo" /></a></td>

                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <br>
                                        <table width="240" border="0" cellspacing="0" cellpadding="0" align="right" class="mobileleft" style="border:solid 1px #ea1d76; border-radius: 3px; margin-bottom: 15px; border-collapse: separate !important; margin-bottom: 15px;">
                                            <tbody>
                                                <tr>
                                                    <td width="43" style="padding-left:2px"><a href="https://www.infospace.org.uk/" target="_blank"><img src="https://www.infospace.org.uk/static/images/emails/loginlogo.gif" width="43" height="38" alt="Login" /></a></td>
                                                    <td width="237" style="color: #ea1d76; font-family: 'Poppins', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;"><a href="https://www.infospace.org.uk/" style="color: #ea1d76; text-decoration:none;">LOGIN TO YOUR ACCOUNT</a></td>
                                                </tr>
                                            </tbody>
                                        </table><br>
                                        <br>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-image:url(https://www.infospace.org.uk/static/images/emails/shadowdown.png); background-repeat:repeat-x; border-top:1px solid #f0f0f0;">&nbsp;</td>
                    </tr>

                    <!-- COPY BLOCK -->
                    <tr>
                        <td align="center" style="padding: 0px 10px 0px 10px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" class="wrapper" align="center">
                                <tr>
                                    <td style="color: #666666; font-family: 'Poppins', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 18px;" align="left">
                                        <!-- COPY -->
                                        <?php echo wpautop($content); ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-image:url(https://www.infospace.org.uk/static/images/emails/shadowup.png); background-repeat:repeat-x; background-position:bottom; border-bottom:1px solid #f0f0f0;">&nbsp;</td>
                    </tr>
                    <!-- FOOTER -->
                    <tr>
                        <td bgcolor="#ffffff" align="center" style="padding: 0px 10px 0px 10px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" class="wrapper">
                                <!-- Footnote -->
                                <tr>
                                    <td bgcolor="#ffffff" align="center">
                                        <table width="136" border="0" cellspacing="0" cellpadding="0" align="right" class="mobileleft">
                                            <tbody>
                                                <tr>
                                                    <td><a href="https://www.infospace.org.uk/" target="_blank"><img src="https://www.infospace.org.uk/static/images/emails/footerlogo.png" width="135" height="107" alt="Infospace logo" /></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table width="420" border="0" cellspacing="0" cellpadding="0" align="left" class="wrapper">
                                            <tbody>
                                                <tr>
                                                    <td align="left" style="color: #666666; font-family: 'Poppins', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 300; line-height: 26px; padding-top:30px;" class="removepadding">
                                                        <p>
                                                            Telephone <?php echo $module_number; ?> <br>
                                                            Email <a href="mailto:<?php echo $module_email; ?>" style="color:#14a6e5; text-decoration:none;"><?php echo $module_email; ?></a><br>
                                                            <br>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#FFA73B" align="center">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" class="wrapper">
                                <tr>
                                    <td align="center" valign="top">
                                        <div style="height:20px;"></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <br><br><p>Email will be sent to the following addresses: <br> <?php echo esc_html($email_addresses); ?></p>
        </div>
    <?php

    } else {


        // Get current page and posts per page
        $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $posts_per_page = 10;

        // Get selected role filter
        $selected_role = isset($_GET['role']) ? sanitize_text_field($_GET['role']) : '';

        // Get selected alerts
        $selected_hsw_alerts = isset($_GET['hsw_alerts']) ? sanitize_text_field($_GET['hsw_alerts']) : '';
        $selected_finance_alerts = isset($_GET['finance_alerts']) ? sanitize_text_field($_GET['finance_alerts']) : '';
        $selected_hr_alerts = isset($_GET['hr_alerts']) ? sanitize_text_field($_GET['hr_alerts']) : '';

        // Get staff filter
        $user_is_staff = isset($_GET['user_is_staff']) ? sanitize_text_field($_GET['user_is_staff']) : '';

        // Get active filter
        $user_is_active = isset($_GET['user_is_active']) ? sanitize_text_field($_GET['user_is_active']) : '';

        // Get table prefix for meta queries
        $prefix = 'theme_fields';

        // Get selected HSW alerts filter
        echo '<form method="GET">';

        echo '<input type="hidden" name="page" value="alerts">';
        echo '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce('alerts_filter_nonce') . '">';
        echo '<h3>Filter users to send to:</h3>';
        echo '<div style="display: flex; flex-wrap: wrap; gap: 20px;">';
            echo '<div>';
                echo '<div><input type="checkbox" name="hsw_alerts" id="hsw_alerts" value="on"' . ($selected_hsw_alerts === 'on' ? ' checked' : '') . '>';
                echo '<label for="hsw_alerts">Recieve HSW alerts </label></div>';

                echo '<div><input type="checkbox" name="finance_alerts" id="finance_alerts" value="on"' . ($selected_finance_alerts === 'on' ? ' checked' : '') . '>';
                echo '<label for="finance_alerts">Recieve Finance alerts </label></div>';

                echo '<div><input type="checkbox" name="hr_alerts" id="hr_alerts" value="on"' . ($selected_hr_alerts === 'on' ? ' checked' : '') . '>';
                echo '<label for="hr_alerts">Recieve HR alerts </label></div><br>';
            echo '</div>';
            echo '<div>';
                echo '<div><label for="user_is_staff">Staff status: </label>';
                echo '<select name="user_is_staff" id="user_is_staff">';
                echo '<option value="">All</option>';
                echo '<option value="on"' . ($user_is_staff === 'on' ? ' selected' : '') . '>Is Staff</option>';
                echo '<option value="off"' . ($user_is_staff === 'off' ? ' selected' : '') . '>Not Staff</option>';
                echo '</select></div><br>';
            echo '</div>';
            echo '<div>';
                echo '<div><label for="user_is_active">Active status: </label>';
                echo '<select name="user_is_active" id="user_is_active">';
                echo '<option value="">All</option>';
                echo '<option value="on"' . ($user_is_active === 'on' ? ' selected' : '') . '>Is Active</option>';
                echo '<option value="off"' . ($user_is_active === 'off' ? ' selected' : '') . '>Not Active</option>';
                echo '</select></div><br>';
            echo '</div>';
            echo '<div>';
                echo '<div><label for="role">Role: </label>';
                echo '<select name="role" id="role">';
                echo '<option value="">All Roles</option>';
                foreach (wp_roles()->roles as $role_key => $role) {
                    $selected = ($selected_role === $role_key) ? 'selected' : '';
                    echo '<option value="' . esc_attr($role_key) . '" ' . $selected . '>' . esc_html($role['name']) . '</option>';
                }
                echo '</select></div><br>';
            echo '</div>';
            echo '<div>';
                echo '<input type="submit" value="Filter" class="button">';
            echo '</div>';
          
        echo '</div>';
        echo '</form><br>';

        // Get users with pagination and role filter
        $user_args = array(
            'number' => $posts_per_page,
            'offset' => ($paged - 1) * $posts_per_page
        );

        if (!empty($selected_role)) {
            $user_args['role'] = $selected_role;
        }

        // Build meta query array for multiple filters with AND relation
        $meta_queries = array();

        // Add meta query for HSW alerts filter
        if ($selected_hsw_alerts === 'on') {
            $meta_queries[] = array(
            'key' => $prefix . 'user_hsw_alerts',
            'value' => 'on',
            'compare' => '='
            );
        }

        // Add meta query for finance alerts filter
        if ($selected_finance_alerts === 'on') {
            $meta_queries[] = array(
            'key' => $prefix . 'user_finance_alerts',
            'value' => 'on',
            'compare' => '='
            );
        }

        // Add meta query for HR alerts filter
        if ($selected_hr_alerts === 'on') {
            $meta_queries[] = array(
            'key' => $prefix . 'user_hr_alerts',
            'value' => 'on',
            'compare' => '='
            );
        }

        // Add meta query for is Staff filter
        if ($user_is_staff === 'on') {
            $meta_queries[] = array(
            'key' => $prefix . 'user_is_staff',
            'value' => 'on',
            'compare' => '='
            );
        } else if ($user_is_staff === 'off') {
            $meta_queries[] = array(
            'relation' => 'OR',
            array(
                'key' => $prefix . 'user_is_staff',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => $prefix . 'user_is_staff',
                'value' => 'off',
                'compare' => '='
            )
            );
        }

        // Add meta query for is Active filter
        if ($user_is_active === 'on') {
            $meta_queries[] = array(
            'key' => $prefix . 'user_is_active',
            'value' => 'on',
            'compare' => '='
            );
        } else if ($user_is_active === 'off') {
            $meta_queries[] = array(
            'relation' => 'OR',
            array(
                'key' => $prefix . 'user_is_active',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => $prefix . 'user_is_active',
                'value' => 'off',
                'compare' => '='
            )
            );
        }

        // Apply meta query if any filters are active
        if (!empty($meta_queries)) {
            if (count($meta_queries) > 1) {
            $meta_queries['relation'] = 'AND';
            }
            $user_args['meta_query'] = $meta_queries;
        }




        $users = get_users($user_args);

        // Get total user count for pagination
        $count_args = $user_args;
        unset($count_args['number'], $count_args['offset']);
        // if (!empty($selected_role)) {
        //  $count_args['role'] = $selected_role;
        // }

        $total_users = count($count_args ? get_users($count_args) : get_users());
        $total_pages = ceil($total_users / $posts_per_page);
        // Get comma separated list of all users email addresses
        $all_filtered_users = get_users($count_args);
        $email_addresses = array();
        foreach ($all_filtered_users as $user) {
            $email_addresses[] = $user->user_email;
        }
        $comma_separated_emails = implode(', ', $email_addresses);
      
        // Display users table
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Active</th><th>Staff</th><th>Role</th><th>Registration Date</th></tr></thead>';
        echo '<tbody>';

        foreach ($users as $user) {
            $user_meta = get_user_meta($user->ID);
            $is_active = isset($user_meta[$prefix . 'user_is_active'][0]) && $user_meta[$prefix . 'user_is_active'][0] == 'on' ? 'Yes' : 'No'; 
             $is_staff = isset($user_meta[$prefix . 'user_is_staff'][0]) && $user_meta[$prefix . 'user_is_staff'][0] == 'on' ? 'Yes' : 'No'; 
            //echo "<pre>";
            //var_dump($user_meta);
            // echo "</pre>";
            $user_roles = implode(', ', $user->roles);
            echo '<tr>';
            echo '<td>' . esc_html($user->ID) . '</td>';
            echo '<td>' . esc_html($user->user_login) . '</td>';
            echo '<td>' . esc_html($user->user_email ) . '</td>';
            echo '<td>' . esc_html($is_active) . '</td>';
            echo '<td>' . esc_html($is_staff) . '</td>';
            echo '<td>' . esc_html($user_roles) . '</td>';
            echo '<td>' . esc_html(date('Y-m-d', strtotime($user->user_registered))) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';

        // Pagination

        if ($total_pages > 1) {
            echo '<div class="tablenav bottom">';
            echo '<div class="tablenav-pages">';
            echo '<span class="displaying-num">' . sprintf(_n('%s user', '%s users', $total_users), number_format_i18n($total_users)) . '</span>';

            $pagination_args = array(
                'base' => add_query_arg('paged', '%#%'),
                'format' => '?paged=%#%',
                'prev_text' => __('<span class="button"><span class="screen-reader-text">Previous page</span><span aria-hidden="true">&laquo;</span></span>'),
                'next_text' => __('<span class="button"><span class="screen-reader-text">Next page</span><span aria-hidden="true">&raquo;</span></span>'),
                'total' => $total_pages,
                'current' => $paged,
                //'add_args' => array('role' => $selected_role),
                'type' => 'plain',
                'show_all' => false,
                'prev_next' => true,
                'end_size' => 0,
                'mid_size' => 0,
                'add_args' => array_filter(array(
                    'role' => $selected_role,
                    'hsw_alerts' => $selected_hsw_alerts,
                    'finance_alerts' => $selected_finance_alerts,
                    'hr_alerts' => $selected_hr_alerts,
                    'user_is_staff' => $user_is_staff,
                    'user_is_active' => $user_is_active,
                    '_wpnonce' => wp_create_nonce('alerts_filter_nonce')
                ))

            );

            echo '<span class="pagination-links">';
            echo paginate_links($pagination_args);
            echo '</span>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

        // Add form for creating alerts
        echo '<div class="wrap">';
        echo '<h2>Send Alert</h2>';
        echo '<form method="POST" action="">';
        wp_nonce_field('send_alert_action', 'send_alert_nonce');
        echo '<input type="hidden" name="email_addresses" value="' . esc_attr($comma_separated_emails) . '">';
        // Module content types select
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th scope="row"><label for="module_type">Module </label></th>';
        echo '<td>';
        echo '<select name="module_type" id="module_type" required>';
        echo '<option value="">Select Module</option>';
        $modules = get_posts(array(
            'post_type' => 'module',
            'post_status' => 'publish',
            'numberposts' => -1
        ));

        foreach ($modules as $module) {
            echo '<option value="' . esc_attr($module->ID) . '"' . selected($module_type, $module->post_name, false) . '>' . esc_html($module->post_title) . '</option>';
        }
        echo '<option value="general"' . selected($module_type, 'general', false) . '>General</option>';
        echo '</select>';
        echo '</td>';
        echo '</tr>';

        // Subject field
        echo '<tr>';
        echo '<th scope="row"><label for="alert_subject">Subject</label></th>';
        echo '<td>';
        echo '<input type="text" name="alert_subject" id="alert_subject" class="regular-text" value="' . esc_attr($alert_subject) . '" required />';
        echo '</td>';
        echo '</tr>';

        // WYSIWYG content field
        echo '<tr>';
        echo '<th scope="row"><label for="alert_content">Message</label></th>';
        echo '<td>';
        wp_editor($alert_content, 'alert_content', array(
            'textarea_name' => 'alert_content',
            'media_buttons' => true,
            'textarea_rows' => 10,
            'teeny' => false,
            'quicktags' => true
        )); ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Auto-save to localStorage
                function saveEditorContent() {
                    if (typeof tinymce !== "undefined" && tinymce.get("alert_content")) {
                        var content = tinymce.get("alert_content").getContent();
                        localStorage.setItem("alert_content_backup", content);
                    }
                }

                // Restore from localStorage
                function restoreEditorContent() {
                    var savedContent = localStorage.getItem("alert_content_backup");
                    if (savedContent && typeof tinymce !== "undefined" && tinymce.get("alert_content")) {
                        tinymce.get("alert_content").setContent(savedContent);
                    }
                }

                // Wait for TinyMCE to be ready
                function waitForTinyMCE() {
                    if (typeof tinymce !== "undefined" && tinymce.get("alert_content")) {
                        restoreEditorContent();
                        // Save content periodically and on form changes
                        setInterval(saveEditorContent, 5000); // Every 5 seconds
                    } else {
                        setTimeout(waitForTinyMCE, 100);
                    }
                }

                // Start waiting for TinyMCE
                waitForTinyMCE();

                // Restore content when editor is ready (backup method)
                document.addEventListener("tinymce-editor-init", function(event) {
                    if (event.target && event.target.id === "alert_content") {
                        restoreEditorContent();
                    }
                });
            });
        </script>
<?php
        echo '</td>';
        echo '</tr>';
        echo '</table>';

        echo '<p class="submit">';
        echo '<input type="submit" name="send_alert" class="button-primary" value="Preview Alert" />';
        echo '</p>';
        echo '</form>';
        echo '</div>';
    }
}
