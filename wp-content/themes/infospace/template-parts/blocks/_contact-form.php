<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php $meta = theme_get_meta(); ?>

<section class="contact-form">

	<?php echo $block_content; ?>

	<form action="ajax" class="contact-form__form">
		<div class="form__content">
			<p>*Essential information</p>


			<input type="hidden" name="action" value="contact_form" />
			<input type="hidden" name="contact_page" value="<?php echo get_the_title(); ?>" />
			<label for="contact_name">Your name*</label>
			<input type="text" class="contact__name trigger-check" id="contact_name" name="contact_name" placeholder="Name" required />
			<label for="contact_email">Contact email address*</label>
			<input type="email" class="contact__email" id="contact_email" name="contact_email" placeholder="Email" required />

			<label for="contact_company">Company name*</label>
			<input type="text" class="contact__company" id="contact_company" name="contact_company" placeholder="Company" required />
			<label for="contact_tel">Phone number*</label>
			<input type="tel" class="contact__tel" id="contact_tel" name="contact_tel" placeholder="Telephone Number" required />
			<label for="contact_message">Your message*</label>
			<textarea class="contact__message" id="contact_message" name="contact_message" placeholder="Enter your your message" rows="4" required /></textarea>
			<label for="cars">Choose a car:</label>

			<select name="cars" id="cars">
				<option value="volvo">Volvo</option>
				<option value="saab">Saab</option>
				<option value="mercedes">Mercedes</option>
				<option value="audi">Audi</option>
			</select>

			<div class="contact__confirm">
				<label class="checkmark-container">
					<input type="checkbox" name="contact_check" class="check-submit contact__check check-submit">
					<span class="checkmark"></span>I consent to the collection and storage of the data entered via the above form, for the purpose of replying to my enquiry. Please view our <a target="_blank" href="/privacy">privacy policy</a>
				</label>
			</div>

			<button type="submit" class="button">
				<span>Send</span>
			</button>
		</div>
		<div class="contact__thanks">
			Thank you for your enquiry, a member of our team will be in touch.
		</div>

	</form>


</section>