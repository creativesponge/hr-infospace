<?php if (is_user_logged_in()) : ?>
    <?php $block_attributes = get_query_var('attributes'); ?>
    <?php $block_content = get_query_var('content'); ?>
    <?php $meta = theme_get_meta(); ?>
    <?php
    global $prefix;
    $imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '';
    $attachmentIdMob = (array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : $imageId;

    // Get current user and their custom fields
    $current_user = wp_get_current_user();
    $user_organisation = get_user_meta($current_user->ID, $prefix . 'user_organisation', true);
    $user_federation_trust = get_user_meta($current_user->ID, $prefix . 'user_federation_trust', true);
    $user_dfe_number = get_user_meta($current_user->ID, $prefix . 'user_dfe_number', true);
    ?>
    <?php
    // Handle form submission
    if (isset($_POST['action']) && $_POST['action'] === 'account_settings' && wp_verify_nonce($_POST['account_settings_nonce'], 'account_settings_nonce')) {
        // Update user basic info
        $user_data = array(
            'ID' => $current_user->ID,
            'first_name' => sanitize_text_field($_POST['contact_name']),
            'last_name' => sanitize_text_field($_POST['last_name']),
            'user_email' => sanitize_email($_POST['contact_email'])
        );
        wp_update_user($user_data);

        // Update user meta fields
        update_user_meta($current_user->ID, $prefix . 'user_organisation', sanitize_text_field($_POST[$prefix . 'user_organisation']));
        update_user_meta($current_user->ID, $prefix . 'user_dfe_number', sanitize_text_field($_POST[$prefix . 'user_dfe_number']));
        update_user_meta($current_user->ID, $prefix . 'user_federation_trust', sanitize_text_field($_POST[$prefix . 'user_federation_trust']));

        // Update alert preferences
        update_user_meta($current_user->ID, $prefix . 'user_hr_alerts', isset($_POST[$prefix . 'user_hr_alerts']) ? 'on' : '');
        update_user_meta($current_user->ID, $prefix . 'user_hsw_alerts', isset($_POST[$prefix . 'user_hsw_alerts']) ? 'on' : '');
        update_user_meta($current_user->ID, $prefix . 'user_finance_alerts', isset($_POST[$prefix . 'user_finance_alerts']) ? 'on' : '');

        // Update consent fields
        update_user_meta($current_user->ID, $prefix . 'user_accepted_privacy_policy', isset($_POST[$prefix . 'user_accepted_privacy_policy']) ? 'on' : '');
        update_user_meta($current_user->ID, $prefix . 'user_accepted_terms', isset($_POST[$prefix . 'user_accepted_terms']) ? 'on' : '');

        // Refresh current user data
        $current_user = wp_get_current_user();
        $user_organisation = get_user_meta($current_user->ID, $prefix . 'user_organisation', true);
        $user_federation_trust = get_user_meta($current_user->ID, $prefix . 'user_federation_trust', true);
        $user_dfe_number = get_user_meta($current_user->ID, $prefix . 'user_dfe_number', true);
    }
    ?>
    <section class="account-settings full-width">
        <header class="panel-header full-width">
            <div class="panel-header__inner">
                <div class="panel-header__content">
                    <h1 class="entry-title">Hello <?php echo esc_html($current_user->first_name . ' ' . $current_user->last_name); ?><span>Account settings</span></h1>
                </div>
            </div>
        </header>


        <form method="post" class="account-settings__form">
            <div class="account-settings__form-content">
                <div class="account-settings__left">
                    <h2>Your details</h2>

                    <?php wp_nonce_field('account_settings_nonce', 'account_settings_nonce'); ?>
                    <input type="hidden" name="action" value="account_settings" />
                    <input type="hidden" name="contact_page" value="<?php echo esc_attr(get_the_title()); ?>" />
                    <div class="account-settings__row">
                        <div class="account-settings__col">
                            <label for="contact_name">First name</label>
                            <input type="text" class="contact__name trigger-check" id="contact_name" name="contact_name" placeholder="First Name" value="<?php echo esc_attr($current_user->first_name); ?>" required />
                        </div>
                        <div class="account-settings__col">
                            <label for="last_name">Last name</label>
                            <input type="text" class="last__name trigger-check" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo esc_attr($current_user->last_name); ?>" required />
                        </div>
                    </div>

                    <div class="account-settings__row">
                        <div class="account-settings__col">
                            <label for="contact_email">Email</label>
                            <input type="email" class="contact__email" id="contact_email" name="contact_email" placeholder="Email" value="<?php echo esc_attr($current_user->user_email); ?>" required />
                        </div>
                        <div class="account-settings__col">
                            <label for="contact_email_confirm">Confirm Email</label>
                            <input type="email" class="contact__email" id="contact_email_confirm" name="contact_email_confirm" placeholder="Confirm Email" required />
                        </div>

                    </div>
                </div>
                <div class="account-settings__right">
                    <h2>School/Academy</h2>
                    <div class="account-settings__col">
                        <label for="user_organisation">Organisation name</label>
                        <input type="text" class="user_organisation" id="user_organisation" name="<?php echo $prefix . 'user_organisation'; ?>" placeholder="School/Academy" value="<?php echo esc_attr($user_organisation); ?>" required />
                    </div>
                    <div class="account-settings__col">
                        <label for="user_federation_trust">User Federation Trust</label>
                        <input type="text" class="user__federation__trust" id="user_federation_trust" name="<?php echo $prefix . 'user_federation_trust'; ?>" placeholder="User Federation Trust" value="<?php echo esc_attr($user_federation_trust); ?>" />
                    </div>
                    <div class="account-settings__col">
                        <label for="dfe_number">DfE Number</label>
                        <input type="text" class="dfe__number" id="user_dfe_number" name="<?php echo $prefix . 'user_dfe_number'; ?>" placeholder="DfE Number" value="<?php echo esc_attr($user_dfe_number); ?>" />
                    </div>
                    
                </div>
                <div class="account-settings__alerts">

                        <?php get_template_part( 'template-parts/newsletter-chooser'); ?>



                </div>

                <button type="submit">Save changes</button>
            </div>

            <div class="contact__thanks" style="display: none;">
                Thank you for your enquiry, a member of our team will be in touch.
            </div>
        </form>

    </section>
<?php endif; ?>