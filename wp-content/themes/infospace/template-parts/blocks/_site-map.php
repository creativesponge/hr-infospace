<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php global $settings;
global $prefix;
$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';

$moduleName = get_the_title($current_module_id_global);
$moduleMeta = get_current_module_meta($current_module_id_global);
$moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '';
$attachedResource = $moduleMeta["attached_resources"];

?>

<div class="site-map">


    <?php
    echo $block_content;
    ?>
    <?php startertheme_sitemap_nav(); ?>
  
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
</div>