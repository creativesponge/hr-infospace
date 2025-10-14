<?php

/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/CMB2/CMB2
 */

global  $prefix;
global  $settings;

$settings = get_option('theme_options', array());
/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 $cmb CMB2 object.
 *
 * @return bool      True if metabox should show
 */
function theme_show_if_front_page($cmb)
{
	// Don't show this metabox if it's not the front page template.
	if (get_option('page_on_front') == $cmb->object_id) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field $field Field object.
 *
 * @return bool              True if metabox should show
 */
function theme_hide_if_no_cats($field)
{
	// Don't show this field if not in the cats category.
	if (!has_tag('cats', $field->object_id)) {
		return false;
	}
	return true;
}

/**
 * Manually render a field.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object.
 */
function theme_render_row_cb($field_args, $field)
{
	$classes     = $field->row_classes();
	$id          = $field->args('id');
	$label       = $field->args('name');
	$name        = $field->args('_name');
	$value       = $field->escaped_value();
	$description = $field->args('description');
?>
	<div class="custom-field-row <?php echo esc_attr($classes); ?>">
		<p><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label></p>
		<p><input id="<?php echo esc_attr($id); ?>" type="text" name="<?php echo esc_attr($name); ?>" value="<?php echo $value; ?>" /></p>
		<p class="description"><?php echo esc_html($description); ?></p>
	</div>
<?php
}

/**
 * Manually render a field column display.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object.
 */
function theme_display_text_small_column($field_args, $field)
{
?>
	<div class="custom-column-display <?php echo esc_attr($field->row_classes()); ?>">
		<p><?php echo $field->escaped_value(); ?></p>
		<p class="description"><?php echo esc_html($field->args('description')); ?></p>
	</div>
<?php
}

// Meta boxes
require_once('meta/_page-meta.php');
require_once('meta/_document-meta.php');
require_once('meta/_document-file-meta.php');
require_once('meta/_page-link-meta.php');
require_once('meta/_post-meta.php');
require_once('meta/_newsletter-meta.php');
require_once('meta/_user-meta.php');
require_once('meta/_user-profile-meta.php');
require_once('meta/_favourite-meta.php');
require_once('meta/_module-meta.php');



add_action('cmb2_admin_init', 'theme_register_theme_options_metabox');
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function theme_register_theme_options_metabox()
{
	global  $prefix;
	/**
	 * Registers options page menu item and form.
	 */
	$theme_options = new_cmb2_box(array(
		'id'           => $prefix . 'theme_options_page',
		'title'        => esc_html__('Theme Options', 'cmb2'),
		'object_types' => array('options-page'),

		/*
		 * The following parameters are specific to the options-page box
		 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
		 */

		'option_key'      => 'theme_options', // The option key and admin menu page slug.
		'icon_url'        => 'dashicons-admin-generic', // Menu icon. Only applicable if 'parent_slug' is left empty.
		// 'menu_title'      => esc_html__( 'Options', 'cmb2' ), // Falls back to 'title' (above).
		// 'parent_slug'     => 'themes.php', // Make options page a submenu item of the themes menu.
		'capability'      => 'edit_pages', // Cap required to view options-page.
		 'position'        => 2, // Menu position. Only applicable if 'parent_slug' is left empty.
		// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
		// 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
		// 'save_button'     => esc_html__( 'Save Theme Options', 'cmb2' ), // The text for the options-page save button. Defaults to 'Save'.
		// 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
		// 'message_cb'      => 'theme_options_page_message_callback',
		// 'tab_group'       => '', // Tab-group identifier, enables options page tab navigation.
		// 'tab_title'       => null, // Falls back to 'title' (above).
		// 'autoload'        => false, // Defaults to true, the options-page option will be autloaded.
	));

	/**
	 * Options fields ids only need
	 * to be unique within this box.
	 * Prefix is not needed.
	 */
	$theme_options->add_field(array(
		'name' => 'Location',
		'type' => 'title',
		'id'   => $prefix . 'header_location'
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Address 1', 'cmb2'),
		'desc'    => esc_html__('The head office address in the footer', 'cmb2'),
		'id'      => $prefix . 'address',
		'type'    => 'textarea',
	));

	$theme_options->add_field(array(
		'name'    => esc_html__('Phone number', 'cmb2'),
		'desc'    => esc_html__('The phone number used in the footer', 'cmb2'),
		'id'      => $prefix . 'phone',
		'type'    => 'text',
	));

	$theme_options->add_field(array(
		'name'    => esc_html__('Email address', 'cmb2'),
		'desc'    => esc_html__('The email address to send enquiries to and used in the footer', 'cmb2'),
		'id'      => $prefix . 'email',
		'type'    => 'text',
	));
	$theme_options->add_field(array(
		'name' => 'Social',
		'type' => 'title',
		'id'   => $prefix . 'social'
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('X address', 'cmb2'),
		'desc'    => esc_html__('The X address', 'cmb2'),
		'id'      => $prefix . 'twitter',
		'type'    => 'text',
	));

	$theme_options->add_field(array(
		'name'    => esc_html__('Facebook address', 'cmb2'),
		'desc'    => esc_html__('The facebook address', 'cmb2'),
		'id'      => $prefix . 'facebook',
		'type'    => 'text',
	));

	$theme_options->add_field(array(
		'name'    => esc_html__('Instagram address', 'cmb2'),
		'desc'    => esc_html__('The instagram address', 'cmb2'),
		'id'      => $prefix . 'instagram',
		'type'    => 'text',
	));

	$theme_options->add_field(array(
		'name'    => esc_html__('YouTube address', 'cmb2'),
		'desc'    => esc_html__('The YouTube address', 'cmb2'),
		'id'      => $prefix . 'youtube',
		'type'    => 'text',
	));

	$theme_options->add_field(array(
		'name'    => esc_html__('Linkedin address', 'cmb2'),
		'desc'    => esc_html__('The Linkedin address', 'cmb2'),
		'id'      => $prefix . 'linkedin',
		'type'    => 'text',
	));

	$theme_options->add_field(array(
		'name' => 'Footer',
		'type' => 'title',
		'id'   => $prefix . 'footer'
	));

	$theme_options->add_field(array(
		'name'    => esc_html__('Footer copyright', 'cmb2'),
		'id'      => $prefix . 'copyright',
		'type'    => 'text',
	));
	$theme_options->add_field(array(
		'name' => 'Updates log',
		'type' => 'title',
		'id'   => $prefix . 'updates_log'
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Updates log file', 'cmb2'),
		'desc'    => esc_html__('Upload a file containing the updates log', 'cmb2'),
		'id'      => $prefix . 'updates_log_file',
		'type'    => 'file',
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Instruction document', 'cmb2'),
		'desc'    => esc_html__('Upload a file containing the instruction document', 'cmb2'),
		'id'      => $prefix . 'instruction_document',
		'type'    => 'file',
	));
}

/**
 * Callback to define the optionss-saved message.
 *
 * @param CMB2  $cmb The CMB2 object.
 * @param array $args {
 *     An array of message arguments
 *
 *     @type bool   $is_options_page Whether current page is this options page.
 *     @type bool   $should_notify   Whether options were saved and we should be notified.
 *     @type bool   $is_updated      Whether options were updated with save (or stayed the same).
 *     @type string $setting         For add_settings_error(), Slug title of the setting to which
 *                                   this error applies.
 *     @type string $code            For add_settings_error(), Slug-name to identify the error.
 *                                   Used as part of 'id' attribute in HTML output.
 *     @type string $message         For add_settings_error(), The formatted message text to display
 *                                   to the user (will be shown inside styled `<div>` and `<p>` tags).
 *                                   Will be 'Settings updated.' if $is_updated is true, else 'Nothing to update.'
 *     @type string $type            For add_settings_error(), Message type, controls HTML class.
 *                                   Accepts 'error', 'updated', '', 'notice-warning', etc.
 *                                   Will be 'updated' if $is_updated is true, else 'notice-warning'.
 * }
 */
function theme_options_page_message_callback($cmb, $args)
{
	if (!empty($args['should_notify'])) {

		if ($args['is_updated']) {

			// Modify the updated message.
			$args['message'] = sprintf(esc_html__('%s &mdash; Updated!', 'cmb2'), $cmb->prop('title'));
		}

		add_settings_error($args['setting'], $args['code'], $args['message'], $args['type']);
	}
}

// Enquiries
add_filter('cmb2_meta_boxes', 'cmb2_theme_enquiry_metabox');
function cmb2_theme_enquiry_metabox()
{
	global $prefix;

	$enquiry = new_cmb2_box(array(
		'id'            => 'theme_enquiry',
		'title'         => 'Enquiry Details',
		'object_types'  => ['enquiry'],
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true,
	));

	$enquiry->add_field(array(
		'id'   => $prefix . 'contact_name',
		'name' => 'Name',
		'type' => 'text',
	));

	$enquiry->add_field(array(
		'id'   => $prefix . 'contact_email',
		'name' => 'Email',
		'type' => 'text_email',
	));

	$enquiry->add_field(array(
		'id'   => $prefix . 'contact_company',
		'name' => 'Company',
		'type' => 'text',
	));

	$enquiry->add_field(array(
		'id'   => $prefix . 'contact_tel',
		'name' => 'Telephone',
		'type' => 'text',
	));

	$enquiry->add_field(array(
		'id'   => $prefix . 'contact_message',
		'name' => 'Message',
		'type' => 'textarea',
	));

	$enquiry->add_field(array(
		'id'   => $prefix . 'contact_check',
		'name' => 'GDPR Checkbox',
		'type' => 'checkbox',
	));

	$enquiry_side = new_cmb2_box(array(
		'id'            => 'theme_enquiry_side',
		'title'         => 'Form Details',
		'object_types'  => ['enquiry'],
		'context'       => 'side',
		'priority'      => 'high',
		'show_names'    => true,
	));

	$enquiry_side->add_field(array(
		'id'   => $prefix . 'contact_recipient',
		'name' => 'Recipient',
		'type' => 'text',
	));
	$enquiry_side->add_field(array(
		'id'   => $prefix . 'contact_page',
		'name' => 'Page',
		'type' => 'text',
	));
}



/**
 * Only show this box in the CMB2 REST API if the user is logged in.
 *
 * @param  bool                 $is_allowed     Whether this box and its fields are allowed to be viewed.
 * @param  CMB2_REST_Controller $cmb_controller The controller object.
 *                                              CMB2 object available via `$cmb_controller->rest_box->cmb`.
 *
 * @return bool                 Whether this box and its fields are allowed to be viewed.
 */
function theme_limit_rest_view_to_logged_in_users($is_allowed, $cmb_controller)
{
	if (!is_user_logged_in()) {
		$is_allowed = false;
	}

	return $is_allowed;
}
