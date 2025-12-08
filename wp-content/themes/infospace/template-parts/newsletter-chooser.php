<?php global $prefix; ?>
<?php $current_user = wp_get_current_user(); ?>
<?php if (user_has_module_access(4594) || user_has_module_access(4611) || user_has_module_access(4536)) : ?>
    <h2>Alerts</h2>



    <?php if (user_has_module_access(4594)) : ?>
        <label class="checkmark-container">
            <input type="checkbox" name="<?php echo $prefix . 'user_hr_alerts'; ?>" class="hr__alerts" <?php checked(get_user_meta($current_user->ID, $prefix . 'user_hr_alerts', true), 'on'); ?>>
            <span class="checkmark"></span>I would like to receive alerts informing me of Human Resources updates
        </label>
    <?php endif; ?>
    <?php if (user_has_module_access(4611)) : ?>
        <label class="checkmark-container">
            <input type="checkbox" name="<?php echo $prefix . 'user_finance_alerts'; ?>" class="finance__alerts" <?php checked(get_user_meta($current_user->ID, $prefix . 'user_finance_alerts', true), 'on'); ?>>
            <span class="checkmark"></span>I would like to receive alerts informing me of Finance updates
        </label>
    <?php endif; ?>
    <?php if (user_has_module_access(4536)) : ?>
        <label class="checkmark-container">
            <input type="checkbox" name="<?php echo $prefix . 'user_hsw_alerts'; ?>" class="hsw__alerts" <?php checked(get_user_meta($current_user->ID, $prefix . 'user_hsw_alerts', true), 'on'); ?>>
            <span class="checkmark"></span>I would like to receive alerts informing me of Health, Safety & Wellbeing updates
        </label>
    <?php endif; ?>
    

<?php endif; ?>