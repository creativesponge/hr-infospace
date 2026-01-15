<?php global $prefix; ?>
<?php $current_user = wp_get_current_user(); ?>
<?php //local $finance_landing_id = 1737 
?>
<?php //local $hr_landing_id = 1735 
?>
<?php 
global $finance_page;
global $hr_page;
global $hsafety_page; ?>


<?php if (user_has_module_access($finance_page) || user_has_module_access($hr_page) || user_has_module_access($hsafety_page)) : ?>
    <h2>Alerts</h2>

    <?php if (user_has_module_access($hr_page)) : ?>
        <label class="checkmark-container">
            <input type="checkbox" name="<?php echo $prefix . 'user_hr_alerts'; ?>" class="hr__alerts" <?php checked(get_user_meta($current_user->ID, $prefix . 'user_hr_alerts', true), 'on'); ?>>
            <span class="checkmark"></span>I would like to receive alerts informing me of Human Resources updates
        </label>
    <?php endif; ?>
    <?php if (user_has_module_access($finance_page)) : ?>
        <label class="checkmark-container">
            <input type="checkbox" name="<?php echo $prefix . 'user_finance_alerts'; ?>" class="finance__alerts" <?php checked(get_user_meta($current_user->ID, $prefix . 'user_finance_alerts', true), 'on'); ?>>
            <span class="checkmark"></span>I would like to receive alerts informing me of Finance updates
        </label>
    <?php endif; ?>
    <?php if (user_has_module_access($hsafety_page)) : ?>
        <label class="checkmark-container">
            <input type="checkbox" name="<?php echo $prefix . 'user_hsw_alerts'; ?>" class="hsw__alerts" <?php checked(get_user_meta($current_user->ID, $prefix . 'user_hsw_alerts', true), 'on'); ?>>
            <span class="checkmark"></span>I would like to receive alerts informing me of Health, Safety & Wellbeing updates
        </label>
    <?php endif; ?>


<?php endif; ?>