
    <?php $block_content = get_query_var('content'); ?>
    <div class="custom-login">
        <?php //global $settings;
        //global  $prefix;
        // $current_user = wp_get_current_user();
        ?>
        <?php $block_attributes = get_query_var('attributes'); ?>
        <?php echo $block_content; ?>
        <?php wp_login_form(); ?>

    


    </div>
