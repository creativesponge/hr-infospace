<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php $current_user = wp_get_current_user(); ?>
<?php
$user_email = isset($_GET['useremail']) ? sanitize_email($_GET['useremail']) : '';
$result_message = '';
$result_class = '';
$after_text = (array_key_exists('afterText', $block_attributes)) ? wpautop(sanitize_text_field($block_attributes['afterText'])) : '';

?>
<?php
if (isset($_POST['reset_password']) && wp_verify_nonce($_POST['forgot_password_nonce_field'], 'forgot_password_nonce')) {
    $user_email = sanitize_email($_POST['user_email']);

    if (email_exists($user_email)) {
        $reset_result = retrieve_password($user_email);
        if (is_wp_error($reset_result)) {
            $result_message = '<div class="error-message">' . $reset_result->get_error_message() . '</div>';
            $result_class = ' welcome-back--error-message';
        } else {
            $result_message = '<div class="success-message">Password reset email sent successfully!</div>';
            $result_class = ' welcome-back--success-message';
        }
    } else {
        $result_message = '<div class="error-message">Email address not found.</div>';
        $result_class = ' welcome-back--error-message';
    }
}
?>

<section class="account-settings full-width">
    <header class="panel-header full-width">
        <div class="panel-header__inner">
            <div class="panel-header__content">
                <h1 class="entry-title"><span>Welcome to the new InfoSpace</span></h1>
            </div>
        </div>
    </header>
    <div class="account-settings__content welcome-back<?php echo $result_class; ?>">
        <?php //global $settings;
        //global  $prefix;
        // $current_user = wp_get_current_user();
        ?>
        <?php $block_attributes = get_query_var('attributes'); ?>
        <div class="welcome-back__before-text">
            <?php echo $block_content; ?>
        </div>
        <?php if ($after_text) : ?>
                <div class="welcome-back__after-text">
                    <?php echo $after_text; ?>
                </div>
            <?php endif; ?>
        <form id="welcome-back-form" method="post" action="">
            <?php wp_nonce_field('forgot_password_nonce', 'forgot_password_nonce_field'); ?>

            <div class="form-group">
                <label for="user_email">Email Address:</label>
                <input type="email" id="user_email" name="user_email" value="<?php echo esc_attr($user_email); ?>" required>
            </div>

            <div class="form-group welcome-back-form__submit">
                <button type="submit" name="reset_password">Reset Password</button>
            </div>

            
        </form>

        <?php
        echo $result_message;
        ?>
        <h4>Need help?</h4>
        <p>If you experience any problems accessing your account or creating a new password, our team will be happy to help.</p>
        <p>Please contact the InfoSpace support team:</p>
        <p>Email: <a href="mailto:EHRpolicy@norfolk.gov.uk">EHRpolicy@norfolk.gov.uk</a></p>
        <p>Telephone: +44 (0)1603 307760</p>
        <p>Alternatively, you can use the Contact Us form and a member of the team will respond as soon as possible.</p>


    </div>

</section>