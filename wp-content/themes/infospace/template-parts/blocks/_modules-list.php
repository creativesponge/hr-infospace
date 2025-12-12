<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
$userid = get_current_user_id();

?>


<?php
$blockHeading = isset($block_attributes['mainHeading']) ? $block_attributes['mainHeading'] : "";
?>
<section class="modules-list full-width">
    <div class="modules-list__container">
        <?php if (isset($blockHeading)) {
            echo "<h1>" . $blockHeading . "</h1>";
        } ?>

        

        <div class="modules-list__modules-container">
            <?php
            $modules = get_posts(array(
                'post_type' => 'module',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC'
            ));
            if ($modules) {
                foreach ($modules as $module) {
                    set_query_var('module', $module);
                    $module_id = $module->ID;

                    $meta = theme_get_meta($module_id);
                    $module_page = isset($meta->module_attached_resources) ? $meta->module_attached_resources : null;



                    //var_dump(user_has_module_access($module_page));
                    // var_dump($userid);
                    // var_dump($meta->module_attached_resources);
                    if (user_has_access($module_page) || user_has_module_access($module_page)) {
                        // if (user_has_module_access($module_page)) {
                        //continue;

                        get_template_part('template-parts/module-teaser');
                    }
                }
            } else {
                echo "<p>No modules found.</p>";
            }
            ?>
        </div>
        <?php get_template_part('template-parts/svgs/_globe-outline') ?>
    </div>
    <div class="modules-list__footer">
        <?php echo $block_content; ?>
    </div>


</section>