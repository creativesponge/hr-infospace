<?php
$module_id = $args['module_id'] ?? null;
$post_id = $args['post_id'] ?? null;
$module_attached_resource = $args['attached_resources'] ?? null;
$moduleColour = $args['module_colour'] ?? '#fff';
$module_posts = get_posts(array(
    'post_type' => 'module',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC'
));
$module_url = get_permalink($module_attached_resource);
$numberModules = 0; 
$moduleHtml = '';

//Check there is more than one module to show

foreach ($module_posts as $module_post) : ?>

        <?php $moduleMeta = theme_get_meta($module_post->ID); ?>
        <?php $module_attached_resources = $moduleMeta->module_attached_resources ?? null; ?>
        <?php $moduleClass = $module_attached_resources == $post_id ? "active" : ""; ?>
        <?php $highlightColour = $module_attached_resources == $post_id && $moduleColour ? $moduleColour : ''; ?>
        
        <?php if (user_has_access($module_attached_resources) == true || user_has_module_access($module_attached_resources) == true) { ?>
            <?php $moduleHtml .= '<li class="' . esc_attr($moduleClass) . '"><a href="' . get_permalink($module_attached_resources) . '" style="background-color: ' . esc_html($highlightColour) . '; border-color: ' . esc_html($moduleColour) . '">' . esc_html($module_post->post_title) . '</a></li>'; ?>
        <?php $numberModules++;   ?>
        <?php } ?>
        <?php   ?>
    <?php endforeach; ?>



<?php if (is_user_logged_in() && $module_id && $numberModules > 1) : ?>
<ul class="tab-list" role="tablist" style="border-color: <?php echo esc_html($moduleColour); ?>">

    <span class="tab-list__outline"></span>
    
    <?php echo  $moduleHtml; ?>
</ul>
<?php endif; ?>