<?php

/**
 * Template part for mobile top bar menu
 *

 */
global $settings;
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
<div class="off-canvas__top-bar">
	<a href="<?php echo esc_url(home_url('/')); ?>" aria-label="Home link" class="off-canvas__logo">
		<?php get_template_part('template-parts/svgs/_logo-rev') ?>
	</a>
	<div class="off-canvas__module-info show-for-large">
		<div style="color: <?php echo $moduleColour; ?>;">
			<?php if (!empty($moduleName)) { ?>
				<span style="color: <?php echo $moduleColour; ?>;"><?php echo $moduleName; ?></span>
			<?php } else { ?>
				<span>Menu</span>
			<?php } ?>
			<?php if ($sitePhone) { ?>
				| <a href="tel:<?php echo preg_replace('/\s+/', '', $sitePhone); ?>" style="color: <?php echo $moduleColour; ?>;"><?php echo $sitePhone ?></a>
			<?php } ?>
			<?php if ($siteEmail) { ?>
				| <a href="mailto:<?php echo $siteEmail ?>" style="color: <?php echo $moduleColour; ?>;"><?php echo $siteEmail ?></a>
			<?php } ?>
		</div>
	</div>
	<div class="off-canvas__actions">
		<button class="off-canvas__close">Close</button>
	</div>
</div>
<div class="off-canvas__module-info hide-for-large show-for-medium">
		<div style="color: <?php echo $moduleColour; ?>;">
			<?php if (!empty($moduleName)) { ?>
				<span style="color: <?php echo $moduleColour; ?>;"><?php echo $moduleName; ?></span>
			<?php } else { ?>
				<span>Menu</span>
			<?php } ?>
			<?php if ($sitePhone) { ?>
				| <a href="tel:<?php echo preg_replace('/\s+/', '', $sitePhone); ?>" style="color: <?php echo $moduleColour; ?>;"><?php echo $sitePhone ?></a>
			<?php } ?>
			<?php if ($siteEmail) { ?>
				| <a href="mailto:<?php echo $siteEmail ?>" style="color: <?php echo $moduleColour; ?>;"><?php echo $siteEmail ?></a>
			<?php } ?>
		</div>
	</div>
	<div class="off-canvas__module-info-mob hide-for-medium">
		<div>
			<?php if (!empty($moduleName)) { ?>
				<span><?php echo $moduleName; ?></span>
			<?php } else { ?>
				<span>Menu</span><
			<?php } ?><br/>
			<?php if ($sitePhone) { ?>
				<a href="tel:<?php echo preg_replace('/\s+/', '', $sitePhone); ?>" ><?php echo $sitePhone ?></a><br/>
			<?php } ?>
			<?php if ($siteEmail) { ?>
				<a href="mailto:<?php echo $siteEmail ?>"><?php echo $siteEmail ?></a>
			<?php } ?>
		</div>
	</div>
<?php


if (!empty($attachedResource)) {
	// Get all child resources recursively
	$accessible_pages = return_users_pages_with_access();
	

	// Display the hierarchy starting from $attachedResource
	echo '<nav class="module-menu">';
	echo get_child_resources($attachedResource, $attachedResource, $moduleMeta, $accessible_pages);
	echo '</nav>';
}
?>
<nav class="mobile-menu vertical menu" role="navigation">
	<?php startertheme_mobile_nav(); ?>
</nav>