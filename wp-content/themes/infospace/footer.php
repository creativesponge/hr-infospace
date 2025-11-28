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
			<div class="footer__contacts show-for-medium">
				<?php dynamic_sidebar('footer-widgets'); ?>
				<?php if ($sitePhone) { ?>
					<a href="tel:<?php echo preg_replace('/\s+/', '', $sitePhone); ?>"><?php echo $sitePhone ?></a>
				<?php } ?>
				<?php if ($siteEmail) { ?>
					<br><a href="mailto:<?php echo $siteEmail ?>"><?php echo $siteEmail ?></a>
				<?php } ?>


			</div>
			<div class="footer__logo-cell">
				<img class="footer__logo" src="<?php echo get_template_directory_uri(); ?>/src/assets/images/ncc-logo.png" alt="<?php bloginfo('name'); ?>" />
			</div>

		</div>
		<div class="footer__grid footer__grid--bottom">
			<div class="footer__contacts hide-for-medium">
				<?php dynamic_sidebar('footer-widgets'); ?>
				<?php if ($sitePhone) { ?>
					<a href="tel:<?php echo preg_replace('/\s+/', '', $sitePhone); ?>"><?php echo $sitePhone ?></a>
				<?php } ?>
				<?php if ($siteEmail) { ?>
					<br><a href="mailto:<?php echo $siteEmail ?>"><?php echo $siteEmail ?></a>
				<?php } ?>


			</div>
			<nav class="footer__navigation">

				<?php startertheme_footer_nav(); ?>

			</nav>
			<div class="footer__credits">

				<a href="https://www.creative-sponge.co.uk/" target="_blank">Web design by <?php get_template_part('template-parts/svgs/_sponge-logo') ?></a>
				<?php if ($copyright) {
					echo "<p>" . $copyright . "</p>";
				} ?>
			</div>

		</div>
	</div>
</footer>

<div class="form-pop-up form-pop-up--login" id="login-form-pop-up">
	<div class="form-pop-up__content ">
		<h2>Login</h2>
		
		<?php
		$args = array(
			'redirect' => home_url($_SERVER['REQUEST_URI']),
			'label_username' => __('Username'),
			'label_password' => __('Password'),
			'label_remember' => __('Remember Me'),
			'label_log_in' => __('Log In'),
			'remember' => true
		);
		?>
		<?php wp_login_form($args); ?>

		<button class="form-pop-up__close">Close</button>
	</div>
	<div class="form-pop-up__overlay"></div>
</div>

<div class="form-pop-up form-pop-up--register" id="register-form-pop-up">
	<div class="form-pop-up__content ">
		<h2>Register</h2>
		
		<?php
		$args = array(
			'redirect' => home_url($_SERVER['REQUEST_URI']),
			'label_username' => __('Username'),
			'label_password' => __('Password'),
			'label_remember' => __('Remember Me'),
			'label_log_in' => __('Log In'),
			'remember' => true
		);
		?>
		<?php
		// Display registration form
		//if (get_option('users_can_register')) {
			?>
			<form name="registerform" id="login-form-register" action="<?php echo esc_url(site_url('wp-login.php?action=register', 'login_post')); ?>" method="post" novalidate="novalidate">
			
				
				<div>
					<label for="first_name"><?php _e('First Name'); ?></label>
					<input type="text" name="first_name" id="first_name" class="input" value="" size="20" />
				</div>
				<div>
					<label for="last_name"><?php _e('Last Name'); ?></label>
					<input type="text" name="last_name" id="last_name" class="input" value="" size="20" />
				</div>
				<div>
					<label for="organisation_name"><?php _e('Organisation Name'); ?></label>
					<input type="text" name="organisation_name" id="organisation_name" class="input" value="" size="30" />
				</div>
				<div>
					<label for="dfe_number"><?php _e('DFE Number'); ?></label>
					<input type="text" name="dfe_number" id="dfe_number" class="input" value="" size="20" />
				</div>
				<div>
					<label for="user_email"><?php _e('Email'); ?></label>
					<input type="email" name="user_email" id="user_email" class="input" value="" size="25" />
				</div>
				<div>
					<label for="user_confirm_email"><?php _e('Confirm email'); ?></label>
					<input type="email" name="user_confirm_email" id="user_confirm_email" class="input" value="" size="25" />
				</div>
				<div class="recaptcha">
					Captcha
				</div>
				<div class="submit">
					<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Register'); ?>" />
				</div>
			</form>
			<?php
		//} else {
			//echo '<p>' . __('User registration is currently not allowed.') . '</p>';
		//}
		?>

		<button class="form-pop-up__close">Close</button>
	</div>
	<div class="form-pop-up__overlay"></div>
</div>

</div><!-- Close off-canvas content -->

<?php
// Display user metadata
/*
if (is_user_logged_in()) {
	$current_user = wp_get_current_user();
	$user_meta = get_user_meta($current_user->ID);

	echo '<div class="user-metadata" style="background: #f0f0f0; padding: 10px; margin: 10px 0; font-family: monospace; font-size: 12px;">';
	echo '<h4>User Metadata for: ' . $current_user->display_name . '</h4>';
	echo '<pre>' . print_r($user_meta, true) . '</pre>';
	echo '</div>';
} */
?>
<?php wp_footer(); ?>
</body>

</html>