<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php global $settings;
global $prefix;
$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
$siteEmail = isset($settings[$prefix . 'email']) ? $settings[$prefix . 'email'] : '';
$sitePhone = isset($settings[$prefix . 'phone']) ? $settings[$prefix . 'phone'] : '';
$moduleName = get_the_title($current_module_id_global);
$moduleMeta = get_current_module_meta($current_module_id_global);
$moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '';
$attachedResource = $moduleMeta["attached_resources"];
$moduleRealMeta = theme_get_meta($current_module_id_global ?? null);
if (isset($moduleRealMeta->module_phone_number)) {
	$sitePhone = $moduleRealMeta->module_phone_number;
}
if (isset($moduleRealMeta->module_email_address)) {
	$siteEmail = $moduleRealMeta->module_email_address;
}

?>

<div class="site-map">
    <?php 

    ?>
    <?php $block_attributes = get_query_var('attributes'); ?>

    <h1>Site map</h1>


    <?php
    echo $block_content;


    ?><?php //get_template_part('template-parts/mobile-top-bar'); ?>
    <?php ////get_template_part('template-parts/mobile-top-bar'); ?>
    <?php startertheme_sitemap_nav(); ?>
    <?php //startertheme_footer_nav(); ?>
    <?php //startertheme_top_bar_r(); 
    ?>
    <?php
    if (!empty($attachedResource)) {
	// Get all child resources recursively
	$accessible_pages = return_users_pages_with_access();
	

	// Display the hierarchy starting from $attachedResource
	echo '<nav class="sitemap-menu">';
	echo get_child_resources($attachedResource, $attachedResource, $moduleMeta, $accessible_pages);
	echo '</nav>';
}
    ?>
    <?php //get_template_part('template-parts/mobile-top-bar'); 
    ?>
</div>