<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
$siteKey = '6Ld4_iQsAAAAAM18DdZ0dvv1KUGcDr_Ic9bcsmzl';
?>
<?php $meta = theme_get_meta(); ?>
<?php
$imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '';
$attachmentIdMob = (array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : $imageId;
$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
$pageModuleMeta = get_current_module_meta($current_module_id_global);


?>
<section class="page-contact full-width">

	<div class="page-contact__container">

		<div class="page-contact__content">
			<div class="page-contact__text">
				<?php echo $block_content; ?>
			</div>
		</div>
	</div>

	<form method="post" action="ajax" class="page-contact__form">
		<div class="page-contact__form-content">
			<p>*Essential information</p>

			<?php wp_nonce_field('contact_form_nonce', 'contact_form_nonce'); ?>
			<input type="hidden" name="action" value="contact_form" />
			<input type="hidden" name="contact_page" value="<?php echo esc_attr(get_the_title()); ?>" />
			<div class="page-contact__row">
				<div class="page-contact__col">
					<label for="contact_name">First name*</label>
					<input type="text" class="contact__name trigger-check" id="contact_name" name="contact_name" placeholder="First Name" required />
				</div>
				<div class="page-contact__col">
					<label for="last_name">Last name*</label>
					<input type="text" class="last__name trigger-check" id="last_name" name="last_name" placeholder="Last Name" required />
				</div>
			</div>
			<div class="page-contact__row">
				<div class="page-contact__col">
					<label for="contact_company">Organisation name*</label>
					<input type="text" class="contact__company" id="contact_company" name="contact_company" placeholder="Company" required />
				</div>
				<div class="page-contact__col">
					<label for="contact_tel">Phone number*</label>
					<input type="tel" class="contact__tel" id="contact_tel" name="contact_tel" placeholder="Telephone Number" required />
				</div>
			</div>
			<label for="contact_email">Email*</label>
			<input type="email" class="contact__email" id="contact_email" name="contact_email" placeholder="Email" required />

			<label for="contact_message">Your message*</label>
			<textarea class="contact__message" id="contact_message" name="contact_message" placeholder="Enter your message" rows="10" required></textarea>

			<div class="contact__confirm">
				<label class="checkmark-container">
					<input type="checkbox" name="contact_check" class="check-submit contact__check" required>
					<span class="checkmark"></span>I consent to the collection and storage of the data entered via the above form, for the purpose of replying to my enquiry. Please view our <a target="_blank" href="/privacy">privacy policy</a>
				</label>
			</div>

			<button type="submit">Send</button>
		</div>
		<!-- reCAPTCHA V3 -->
		<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $siteKey; ?>"></script>
		<script>
			grecaptcha.ready(function() {

				grecaptcha.execute('<?php echo $siteKey; ?>', {
					action: 'ajax'
				}).then(function(token) {
					// Add your logic to submit to your backend server here.
					var recaptchaResponse = document.getElementById('recaptchaResponse');
					recaptchaResponse.value = token;
				});
			});
		</script>
		<input type="hidden" name="recaptcha_response" id="recaptchaResponse">

		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<?php // End reCAPTCHA integration  
		?>
		<div class="contact__thanks">
			Thank you for your enquiry, a member of our team will be in touch.
		</div>
		<div class="contact__error">
			There was an error. Please try again later.
		</div>
	</form>


</section>