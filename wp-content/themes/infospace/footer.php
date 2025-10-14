<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *

 */
global $settings;
global  $prefix;

$siteEmail = isset($settings[$prefix . 'email']) ? $settings[$prefix . 'email'] : '';
$sitePhone = isset($settings[$prefix . 'phone']) ? $settings[$prefix . 'phone'] : '';
$siteAddress = isset($settings[$prefix . 'address']) ? $settings[$prefix . 'address'] : '';
$copyright = isset($settings[$prefix . 'copyright']) ? $settings[$prefix . 'copyright'] : '';

?>
<footer class="footer">
	<div class="footer__container">
		<div class="footer__grid">
			<?php dynamic_sidebar('footer-widgets'); ?>
			<div class="cell">
				<?php if ($siteEmail) { ?>
					<a href="mailto:<?php echo $siteEmail ?>"><?php echo $siteEmail ?></a>
				<?php } ?>
				<?php if ($sitePhone) { ?>
					<a href="tel:<?php echo preg_replace('/\s+/', '', $sitePhone); ?>"><?php echo $sitePhone ?></a>
				<?php } ?>
				<?php if ($siteAddress) {
					echo wpautop($siteAddress);
				} ?>
				<?php if ($copyright) {
					echo "<p>" . $copyright . "</p>";
				} ?>
			</div>
			<div class="cell">
				<?php get_template_part('template-parts/socials') ?>
			</div>
		</div>
	</div>
</footer>


</div><!-- Close off-canvas content -->

<?php
// Display user metadata
if (is_user_logged_in()) {
	$current_user = wp_get_current_user();
	$user_meta = get_user_meta($current_user->ID);
	
	echo '<div class="user-metadata" style="background: #f0f0f0; padding: 10px; margin: 10px 0; font-family: monospace; font-size: 12px;">';
	echo '<h4>User Metadata for: ' . $current_user->display_name . '</h4>';
	echo '<pre>' . print_r($user_meta, true) . '</pre>';
	echo '</div>';
}
?>
<?php wp_footer(); ?>
</body>

</html>