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
require_once('meta/_resource-meta.php');
require_once('meta/_document-meta.php');
require_once('meta/_document-file-meta.php');
require_once('meta/_page-link-meta.php');
require_once('meta/_post-meta.php');
require_once('meta/_newsletter-meta.php');
require_once('meta/_user-meta.php');
require_once('meta/_user-profile-meta.php');
require_once('meta/_favourite-meta.php');
require_once('meta/_module-meta.php');
require_once('meta/_page-meta.php');



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
		'capability'      => 'administrator', // Cap required to view options-page.
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
		'name'    => esc_html__('Updates log date', 'cmb2'),
		'desc'    => esc_html__('Enter the date of the updates log', 'cmb2'),
		'id'      => $prefix . 'updates_log_date',
		'type'    => 'text_date',
	));
$theme_options->add_field(array(
		'name' => 'Instruction document',
		'type' => 'title',
		'id'   => $prefix . 'instruction_document_title'
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Instruction document', 'cmb2'),
		'desc'    => esc_html__('Upload a file containing the instruction document', 'cmb2'),
		'id'      => $prefix . 'instruction_document',
		'type'    => 'file',
	));

	$theme_options->add_field(array(
		'name' => 'Privacy document',
		'type' => 'title',
		'id'   => $prefix . 'privacy_document_title'
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Privacy document', 'cmb2'),
		'desc'    => esc_html__('Upload a file containing the privacy document', 'cmb2'),
		'id'      => $prefix . 'privacy_document',
		'type'    => 'file',
	));

	$theme_options->add_field(array(
		'name' => 'Resources section',
		'type' => 'title',
		'id'   => $prefix . 'resources_section'
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Get in touch text', 'cmb2'),
		'id'      => $prefix . 'get_in_touch_text',
		'type'    => 'text',
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Get in touch url', 'cmb2'),
		'id'      => $prefix . 'get_in_touch_url',
		'type'    => 'text',
	));

	$theme_options->add_field(array(
		'name' => 'Search page',
		'type' => 'title',
		'id'   => $prefix . 'search_page_section'
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Heading image', 'cmb2'),
		'desc'    => esc_html__('Upload an image for the search page heading', 'cmb2'),
		'id'      => $prefix . 'search_heading_image',
		'type'    => 'file',
	));
	$theme_options->add_field(array(
		'name'    => esc_html__('Heading image mobile', 'cmb2'),
		'desc'    => esc_html__('Upload an image for the search page heading for mobile', 'cmb2'),
		'id'      => $prefix . 'search_heading_image_mobile',
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


// Add custom fields to menu items
add_action('wp_nav_menu_item_custom_fields', 'add_menu_item_custom_fields', 10, 4);
function add_menu_item_custom_fields($item_id, $item, $depth, $args)
{
	global $prefix;

	$nav_file = get_post_meta($item_id, $prefix . 'nav_file', true);
	$nav_accesskey = get_post_meta($item_id, $prefix . 'nav_accesskey', true);
?>
	<p class="field-nav-file description description-wide">
		<label for="edit-menu-item-nav-file-<?php echo $item_id; ?>">
			File Attachment ID<br />
			<input type="text" id="edit-menu-item-nav-file-<?php echo $item_id; ?>"
				name="menu-item-nav-file[<?php echo $item_id; ?>]" value="<?php echo esc_attr($nav_file); ?>" />
		</label>
	</p>

	<p class="field-nav-accesskey description description-wide">
		<label for="edit-menu-item-nav-accesskey-<?php echo $item_id; ?>">
			Access Key<br />
			<input type="text" id="edit-menu-item-nav-accesskey-<?php echo $item_id; ?>"
				name="menu-item-nav-accesskey[<?php echo $item_id; ?>]" value="<?php echo esc_attr($nav_accesskey); ?>" />
		</label>
	</p>

	<?php



}

// Save the custom fields
add_action('wp_update_nav_menu_item', 'save_menu_item_custom_fields', 10, 3);
function save_menu_item_custom_fields($menu_id, $menu_item_db_id, $args)
{
	global $prefix;

	if (isset($_POST['menu-item-nav-file'][$menu_item_db_id])) {
		update_post_meta($menu_item_db_id, $prefix . 'nav_file', sanitize_text_field($_POST['menu-item-nav-file'][$menu_item_db_id]));
	}

	if (isset($_POST['menu-item-nav-accesskey'][$menu_item_db_id])) {
	update_post_meta($menu_item_db_id, $prefix . 'nav_accesskey', sanitize_text_field($_POST['menu-item-nav-accesskey'][$menu_item_db_id]));
}
}
