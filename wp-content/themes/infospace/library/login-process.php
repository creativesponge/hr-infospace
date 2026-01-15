<?php

//Load login styles to admin

function my_login_stylesheet()
{
    wp_enqueue_style('main-stylesheet', get_stylesheet_directory_uri() . '/dist/assets/css/' . startertheme_asset_path('app.css'), array(), '2.10.7', 'all');
}
add_action('login_enqueue_scripts', 'my_login_stylesheet');

// Hide the language awitcher form login page
add_filter('login_display_language_dropdown', '__return_false');

// Remove 'register' link from wordpress login page
add_filter('register', '__return_false');

// Remove 'need an account? Sign up!' from wordpress login page
add_filter('login_form_bottom', 'remove_signup_text');
function remove_signup_text($content)
{
    return '';
}

// Add custom content to the login footer
add_action('login_footer', 'my_custom_login_content');
function my_custom_login_content()
{
    // Add your HTML here
    echo '<p><a class="wp-login-lost-password" href="/wp-login.php?action=lostpassword">Lost your password?</a></p>';
}

// Change login logo URL to site URL
add_filter('login_headerurl', 'my_login_logo_url');
function my_login_logo_url()
{
    return home_url();
}


/**
 * Login process
 */

// Check if user is logged in and hasn't accepted terms on any page
add_action('template_redirect', 'check_terms_acceptance');

function check_terms_acceptance()
{
    global $prefix;

    if (isset($_POST['form_action']) && $_POST['form_action'] == 'account_settings') {
        return;
    }

    // Only check for logged-in users
    if (!is_user_logged_in()) {
        return;
    }

    // Don't redirect if form was just submitted or if updated parameter is present
    if (isset($_GET['updated']) || isset($_POST['action'])) {
        return;
    }

    // Allow access to the terms acceptance page itself
    if (is_page('terms-acceptance') || is_page('privacy-acceptance') || is_page('change-password') || current_user_can('administrator')) {
        return;
    }

    $user_id = get_current_user_id();
    $terms_accepted = get_user_meta($user_id, $prefix . 'user_accepted_terms', true);
    $privacy_accepted = get_user_meta($user_id, $prefix . 'user_accepted_privacy_policy', true);
    $changed_password = get_user_meta($user_id, $prefix . 'user_changed_password', true);

    if (!$terms_accepted) {
        wp_redirect(home_url('/terms-acceptance/'));
        exit;
    } else if (!$privacy_accepted) {
        wp_redirect(home_url('/privacy-acceptance/'));
        exit;
    } else if (!$changed_password) {
        wp_redirect(home_url('/change-password/'));
        exit;
    }
}

// Handle terms acceptance form submission
add_action('init', 'process_terms_acceptance');

function process_terms_acceptance()
{
    global $prefix;

    if (isset($_POST['form_action']) && $_POST['form_action'] == 'account_settings') {
        return;
    }

    if (isset($_POST['accept_terms']) && is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, $prefix . 'user_accepted_terms', true);
        wp_redirect(home_url());
        exit;
    } elseif (isset($_POST['accept_privacy']) && is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, $prefix . 'user_accepted_privacy_policy', 'on');

        // Process newsletter subscriptions
        $newsletters = [
            'hr_alerts' => 'user_hr_alerts',
            'hsw_alerts' => 'user_hsw_alerts',
            'finance_alerts' => 'user_finance_alerts'
        ];

        foreach ($newsletters as $post_key => $meta_key) {
            if (isset($_POST[$prefix . $post_key])) {
                update_user_meta($user_id, $prefix . $meta_key, 'on');
            } else {
                delete_user_meta($user_id, $prefix . $meta_key);
            }
        }

        wp_redirect(home_url());
        exit;
    } elseif (isset($_POST['change_password_nonce']) && is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, $prefix . 'user_changed_password', true);
        wp_redirect(home_url());
        exit;
    }
}

// Prevent access to admin/dashboard until terms accepted
add_action('admin_init', 'enforce_terms_acceptance');

function enforce_terms_acceptance()
{
    global $prefix;

    if (isset($_POST['form_action']) && $_POST['form_action'] == 'account_settings') {
        return;
    }

    // Don't redirect AJAX requests or if already on terms page
    if ((defined('DOING_AJAX') && DOING_AJAX)) {
        return;
    }

    if (is_user_logged_in() && !current_user_can('administrator')) {
        $user_id = get_current_user_id();
        $terms_accepted = get_user_meta($user_id, $prefix . 'user_accepted_terms', true);
        $privacy_accepted = get_user_meta($user_id, $prefix . 'user_accepted_privacy_policy', true);
        $changed_password = get_user_meta($user_id, $prefix . 'user_changed_password', true);

        if (!$terms_accepted) {
            wp_redirect(home_url('/terms-acceptance/'));
            exit;
        } else if (!$privacy_accepted) {
            wp_redirect(home_url('/privacy-acceptance/'));
            exit;
        } else if (!$changed_password) {
            wp_redirect(home_url('/change-password/'));
            exit;
        }
    }
}

// record stats on user login 
add_action('wp_login', 'record_user_login_stats', 10, 2);
function record_user_login_stats($user_login, $user)
{
    global $prefix;
    $user_id = $user->ID;
    $user_name = $user->display_name;
    $user_login = $user->user_login;

    log_user_interaction($user_login, $user_id, 14, 'Logged in', $user_name);

    // Update last login date for hr_alerts
    update_user_meta($user_id, $prefix . 'user_last_login', time());
}
