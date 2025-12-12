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
	function get_child_resources($parent_id, $attachedResource, $moduleMeta, $accessible_pages)
	{
		$args = array(
			'post_type'      => 'resource_page',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'post_parent'    => $parent_id,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);
		// Lighten the module color by 50%
		$originalColor = $moduleMeta['module_color'];
		if (!empty($originalColor)) {
			// Remove # if present
			$hex = ltrim($originalColor, '#');
			
			// Convert hex to RGB
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
			
			// Lighten by 50% (blend with white)
			$r = $r + (255 - $r) * 0.5;
			$g = $g + (255 - $g) * 0.5;
			$b = $b + (255 - $b) * 0.5;
			
			// Convert back to hex
			$lighterColor = '#' . str_pad(dechex(round($r)), 2, '0', STR_PAD_LEFT) . 
								  str_pad(dechex(round($g)), 2, '0', STR_PAD_LEFT) . 
								  str_pad(dechex(round($b)), 2, '0', STR_PAD_LEFT);
			
			$moduleMeta['module_color'] = $lighterColor;
		}
		$children = get_posts($args);
		// Get accessible page IDs
		
		// Filter children to only include accessible pages
		if (!empty($accessible_pages) && !current_user_can('administrator')) {
			$children = array_filter($children, function($child) use ($accessible_pages) {
				return in_array($child->ID, $accessible_pages);
			});
		}
		$output = '';
		if ($children) {

			if ($parent_id == $attachedResource) {

				$output .= '<ul class="module-menu__toplevel">';
			} else {
				$output .= '<ul class="module-menu__submenu">';
			}
			foreach ($children as $child) {
				//if(!user_has_page_access(get_current_user_id(), $child->ID, 'resource_page')) {
					//continue;
				//}
				$output .= '<li style="border-left-color: ' . esc_html($moduleMeta['module_color']) . ';">';

				$output .= '<a href="' . get_permalink($child->ID) . '">' . esc_html(get_the_title($child->ID)) . '</a>';

				// Recursive call for further children

				$output .= get_child_resources($child->ID, $attachedResource, $moduleMeta, $accessible_pages);

				$output .= '</li>';
			}
			$output .= '</ul>';
		}
		return $output;
	}

	// Display the hierarchy starting from $attachedResource
	echo '<nav class="module-menu">';
	echo get_child_resources($attachedResource, $attachedResource, $moduleMeta, $accessible_pages);
	echo '</nav>';
}
?>
<nav class="mobile-menu vertical menu" role="navigation">
	<?php startertheme_mobile_nav(); ?>
</nav>