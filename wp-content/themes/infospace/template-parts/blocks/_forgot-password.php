<?php $block_content = get_query_var('content'); ?>
<?php $current_user = wp_get_current_user(); ?>

<section class="account-settings full-width">
    <header class="panel-header full-width">
        <div class="panel-header__inner">
            <div class="panel-header__content">
                <h1 class="entry-title">Hello <?php echo esc_html($current_user->first_name . ' ' . $current_user->last_name); ?><span>Password reset</span></h1>
            </div>
        </div>
    </header>
    <div class="account-settings__content forgot-password">
        <?php //global $settings;
        //global  $prefix;
        // $current_user = wp_get_current_user();
        ?>
        <?php $block_attributes = get_query_var('attributes'); ?>
        <?php echo $block_content; ?>
        <form id="forgot-password-form" method="post" action="">
            <?php wp_nonce_field('forgot_password_nonce', 'forgot_password_nonce_field'); ?>

            <div class="form-group">
                <label for="user_email">Email Address:</label>
                <input type="email" id="user_email" name="user_email" required>
            </div>

            <div class="form-group forgot-password-form__submit">
                <button type="submit" name="reset_password">Reset Password</button>
            </div>

            <div id="forgot-password-message"></div>
        </form>

        <?php
        if (isset($_POST['reset_password']) && wp_verify_nonce($_POST['forgot_password_nonce_field'], 'forgot_password_nonce')) {
            $user_email = sanitize_email($_POST['user_email']);

            if (email_exists($user_email)) {
                $reset_result = retrieve_password($user_email);
                if (is_wp_error($reset_result)) {
                    echo '<div class="error-message">Error: ' . $reset_result->get_error_message() . '</div>';
                } else {
                    echo '<div class="success-message">Password reset email sent successfully!</div>';
                }
            } else {
                echo '<div class="error-message">Email address not found.</div>';
            }
        }
        ?>


    </div>

</section>