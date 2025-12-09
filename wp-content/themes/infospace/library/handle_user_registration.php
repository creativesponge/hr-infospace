<?php
// Handle AJAX user registration
add_action('wp_ajax_register_user_ajax', 'handle_ajax_user_registration');
add_action('wp_ajax_nopriv_register_user_ajax', 'handle_ajax_user_registration');

function handle_ajax_user_registration()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['register_nonce'], 'register_user')) {
        wp_send_json_error(array('message' => 'Security check failed.'));
    }

    //Recaptcha verification
    $recaptcha_response = sanitize_text_field($_POST['recaptcha_response']);
    $recaptcha_secret = '6Ld4_iQsAAAAAJZCHORH432pyFxffNPyMckL2WJd';
    $recaptcha_verify = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
    $recaptcha_result = json_decode(wp_remote_retrieve_body($recaptcha_verify));

    if (!$recaptcha_result->success || $recaptcha_result->score < 0.5) {
        wp_send_json_error(array('message' => '<p>reCAPTCHA verification failed. Please try again.</p>'));
    }

    global $prefix;
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $organisation_name = sanitize_text_field($_POST['user_organisation']);
    $federation_trust = sanitize_text_field($_POST['user_federation_trust']);
    $dfe_number = sanitize_text_field($_POST['user_dfe_number']);
    $user_email = sanitize_email($_POST['user_email']);
    $user_confirm_email = sanitize_email($_POST['user_confirm_email']);

    $errors = array();

    // Validation
    if (empty($first_name)) $errors[] = 'First name is required.';
    if (empty($last_name)) $errors[] = 'Last name is required.';
    if (empty($organisation_name)) $errors[] = 'School/Academy name is required.';
    if (empty($federation_trust)) $errors[] = 'Federation/Trust is required.';
    if (empty($user_email)) $errors[] = 'Email is required.';
    if (!is_email($user_email)) $errors[] = 'Please enter a valid email address.';
    if ($user_email !== $user_confirm_email) $errors[] = 'Email addresses do not match.';
    if (email_exists($user_email)) $errors[] = 'This email address is already registered.';

    if (!empty($errors)) {
        wp_send_json_error(array('message' => '<p>' . implode('</p><p>', $errors) . '</p>'));
    }

    // Generate username and password
    $username = $user_email;
    $password = wp_generate_password();

    // Create user
    $user_id = wp_create_user($username, $password, $user_email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => '<p>Registration failed: ' . $user_id->get_error_message() . '</p>'));
    }

    // Update user meta
    update_user_meta($user_id, 'first_name', sanitize_text_field($first_name));
    update_user_meta($user_id, 'last_name', sanitize_text_field($last_name));
    update_user_meta($user_id, $prefix . 'user_organisation', sanitize_text_field($organisation_name));
    update_user_meta($user_id, $prefix . 'user_federation_trust', sanitize_text_field($_POST['user_federation_trust']));
    update_user_meta($user_id, $prefix . 'user_dfe_number', sanitize_text_field($dfe_number));

    // Send notification email to infospace admin
    //$admin_email = get_option('admin_email');
    $admin_email = 'barry@creativesponge.co.uk';
    //$admin_email = 'ehrpolicy@norfolkgov.uk';
    $subject = 'New User Registration';
    $message = "New user registration for $first_name $last_name needs approving:.\n\n";
    $message .= "School/Academy: $organisation_name\n";
    $message .= "Federation/Trust: $federation_trust\n";
    $message .= "DFE Number: $dfe_number\n";
    $message .= "Email: $user_email\n"; 
    //wp_new_user_notification($user_id, null, 'both');

    wp_mail($admin_email, $subject, $message);
    wp_send_json_success(array('message' => 'Your account has been created successfully. You will receive an email with your login details shortly.'));
}
