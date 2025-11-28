<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php $meta = theme_get_meta(); ?>
<?php
$imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '';
$attachmentIdMob = (array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : $imageId;

?>
<section class="contact-form full-width">

	<div class="contact-form__container">

		<div class="contact-form__content">
			<div class="contact-form__text">

				<?php echo $block_content; ?>
			</div>
		</div>
		<?php if ($imageId) { ?>
			<div class="contact-form__image">
				<?php if ($imageId) {
					echo wp_get_attachment_image($imageId, 'imagetext', '', ["class" => "show-for-medium wp-image-$imageId"]);
				} ?>
				<?php if ($attachmentIdMob) {
					echo wp_get_attachment_image($attachmentIdMob, 'imagetext', '', ["class" => "hide-for-medium wp-image-$attachmentIdMob"]);
				} ?>

			</div>
		<?php } ?>

	</div>

	<form method="post" class="contact-form__form">
		<div class="contact-form__form-content">
			<p>*Essential information</p>

			<?php wp_nonce_field('contact_form_nonce', 'contact_form_nonce'); ?>
			<input type="hidden" name="action" value="contact_form" />
			<input type="hidden" name="contact_page" value="<?php echo esc_attr(get_the_title()); ?>" />
			<div class="contact-form__row">
				<div class="contact-form__col">
				<label for="contact_name">First name*</label>
				<input type="text" class="contact__name trigger-check" id="contact_name" name="contact_name" placeholder="First Name" required />
			</div>
			<div class="contact-form__col">
				<label for="last_name">Last name*</label>
				<input type="text" class="last__name trigger-check" id="last_name" name="last_name" placeholder="Last Name" required />
			</div>
			</div>
			<div class="contact-form__row">
				<div class="contact-form__col">
				<label for="contact_company">Organisation name*</label>
				<input type="text" class="contact__company" id="contact_company" name="contact_company" placeholder="Company" required />
			</div>
<div class="contact-form__col">
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

		<div class="contact__thanks" style="display: none;">
			Thank you for your enquiry, a member of our team will be in touch.
		</div>
	</form>


</section>