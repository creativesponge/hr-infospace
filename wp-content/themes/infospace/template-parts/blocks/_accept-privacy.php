<?php if (is_user_logged_in()) : ?>
    <?php global $settings;
    global  $prefix;
    $privacyDocId = isset($settings[$prefix . 'privacy_document_id']) ? $settings[$prefix . 'privacy_document_id'] : '';
    $current_user = wp_get_current_user();
    $privacyDocUrl = '/download-document/' . $privacyDocId;
    ?>
    <?php $block_attributes = get_query_var('attributes'); ?>
    <?php $block_content = get_query_var('content'); ?>
    <section class="account-settings full-width">
        <header class="panel-header full-width">
            <div class="panel-header__inner">
                <div class="panel-header__content">
                    <h1 class="entry-title">Hello <?php echo esc_html($current_user->first_name . ' ' . $current_user->last_name); ?><span>Privacy Policy</span></h1>
                </div>
            </div>
        </header>
        <div class="account-settings__content">

            <?php if ($privacyDocUrl): ?>
                <p>Please <a href="<?php echo esc_url($privacyDocUrl); ?>" target="_blank" rel="noopener noreferrer">click here</a> to view our Privacy Policy in line with GDPR. The policy includes information on how we store, manage and use your personal data.</p>
            <?php endif; ?>


            <?php
            echo $block_content; ?>
            <form method="post" action="">
                <div class="accept-privacy__privacy">
                    <label class="checkmark-container">
                        <input type="checkbox" name="accept_privacy" class="accept-privacy" <?php checked(get_user_meta($current_user->ID, $prefix . 'user_accepted_privacy_policy', true), 'on'); ?>>
                        <span class="checkmark"></span> I accept the Privacy Policy
                    </label>
                </div>

                <div class="accept-privacy__alerts">
                    <?php get_template_part('template-parts/newsletter-chooser'); ?>
                </div>


                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </section>
<?php endif; ?>