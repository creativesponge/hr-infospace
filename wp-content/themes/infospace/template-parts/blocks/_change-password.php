<?php if (is_user_logged_in()) : ?>
    <?php $block_attributes = get_query_var('attributes'); ?>
    <?php $block_content = get_query_var('content'); ?>
    <?php $current_user = wp_get_current_user(); ?>
    <section class="account-settings full-width">
        <header class="panel-header full-width">
            <div class="panel-header__inner">
                <div class="panel-header__content">
                    <h1 class="entry-title">Hello <?php echo esc_html($current_user->first_name . ' ' . $current_user->last_name); ?><span>Change password</span></h1>
                </div>
            </div>
        </header>
        <div class="account-settings__content change-password">
            <?php
            echo $block_content; ?>
            <form method="post" action="" class="change-password-form">
                <?php wp_nonce_field('change_password_nonce', 'change_password_nonce'); ?>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="change_password" value="Change Password" class="btn btn-primary">Change Password</button>
                </div>
            </form>

            <?php
            if (isset($_POST['change_password']) && wp_verify_nonce($_POST['change_password_nonce'], 'change_password_nonce')) {
                $current_password = $_POST['current_password'];
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];
                
                if ($new_password !== $confirm_password) {
                    echo '<div class="error-message">New passwords do not match.</div>';
                } elseif (strlen($new_password) < 12) {
                    echo '<div class="error-message">Password must be at least 12 characters long.</div>';
                } elseif (!wp_check_password($current_password, $current_user->user_pass, $current_user->ID)) {
                    echo '<div class="error-message">Current password is incorrect.</div>';
                } else {
                    wp_set_password($new_password, $current_user->ID);
                    wp_set_current_user($current_user->ID);
                    wp_set_auth_cookie($current_user->ID);

                    echo '<div class="success-message">Password changed successfully.</div>';
                    wp_redirect('/module/');
                    exit;
                }
            }
            ?>
        </div>
    </section>
<?php endif; ?>