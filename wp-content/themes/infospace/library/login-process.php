<?php
add_action('login_form', 'my_custom_login_content');
function my_custom_login_content() {
    // Add your HTML here
    echo '<p><a href="yourwebsite.com">Need an account? Sign up!</a></p>';
}
/**
 * Login process
 */

// Check if user is logged in and hasn't accepted terms on any page
add_action('template_redirect', 'check_terms_acceptance');

function check_terms_acceptance()
{
    global $prefix;

    // Only check for logged-in users
    if (!is_user_logged_in()) {
        return;
    }

    // Allow access to the terms acceptance page itself
    if (is_page('terms-acceptance') || is_page('privacy-acceptance')) {
        return;
    }

    $user_id = get_current_user_id();
    $terms_accepted = get_user_meta($user_id, $prefix . 'user_accepted_terms', true);
    $privacy_accepted = get_user_meta($user_id, $prefix . 'user_accepted_privacy_policy', true);

    if (!$terms_accepted) {
        wp_redirect(home_url('/terms-acceptance/'));
        exit;
    } else if (!$privacy_accepted) {
        wp_redirect(home_url('/privacy-acceptance/'));
        exit;
    }
}

// Handle terms acceptance form submission
add_action('init', 'process_terms_acceptance');

function process_terms_acceptance()
{
    global $prefix;
    if (isset($_POST['accept_terms']) && is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, $prefix . 'user_accepted_terms', true);
        wp_redirect(home_url());
        exit;
    } else {
        if (isset($_POST['accept_privacy']) && is_user_logged_in()) {
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
        }
    }
}

// Prevent access to admin/dashboard until terms accepted
add_action('admin_init', 'enforce_terms_acceptance');

function enforce_terms_acceptance()
{
    global $prefix;

    // Don't redirect AJAX requests or if already on terms page
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $terms_accepted = get_user_meta($user_id, $prefix . 'user_accepted_terms', true);
        $privacy_accepted = get_user_meta($user_id, $prefix . 'user_accepted_privacy_policy', true);

        if (!$terms_accepted) {
            wp_redirect(home_url('/terms-acceptance/'));
            exit;
        } else if (!$privacy_accepted) {
            wp_redirect(home_url('/privacy-acceptance/'));
            exit;
        }
    }
}

// record stats on user login 
add_action('wp_login', 'record_user_login_stats', 10, 2);
function record_user_login_stats($user_login, $user) {

    $user_id = $user->ID;
    $user_name = $user->display_name;
    $user_login = $user->user_login;
    
    log_user_interaction($user_login, $user_id, 14, 'Logged in', $user_name);
}



