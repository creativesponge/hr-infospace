<?php

// Get meta data
function theme_get_meta($post_ID = false)
{
    global $prefix;
    if (!$post_ID) {
        $post_ID = get_the_ID();
    }

    $meta = (array) get_metadata('post', $post_ID);
    $meta_object = new stdClass();

    foreach ($meta as $key => $value) {
        if ($key == '_wp_page_template' || $key == '_edit_lock' || $key == '_edit_last') {
            continue;
        }

        $key = str_replace($prefix, '', $key);

        if (is_serialized($value[0])) {
            $array = unserialize($value[0]);
            $meta_object->{$key} = $array;
        } else {
            $meta_object->{$key} = $value[0];
        }
    }

    return $meta_object;
}


/**
 *  Interchange for CMB2 images
 */
function theme_get_interchange($id)
{
    $return = array();
    $return['original'] = wp_get_attachment_url($id);

    $return['small'] = wp_get_attachment_image_src($id, 'fpsmall');
    $return['small'] = $return['small'][0];

    $return['medium'] = wp_get_attachment_image_src($id, 'fpmedium');
    $return['medium'] = $return['medium'][0];

    $return['large'] = wp_get_attachment_image_src($id, 'fplarge');
    $return['large'] = $return['large'][0];

    $return['xlarge'] = wp_get_attachment_image_src($id, 'fpxlarge');
    $return['xlarge'] = $return['xlarge'][0];

    echo 'data-interchange="[' . $return["small"] . ', small],
          [' . $return["medium"] . ', medium],
          [' . $return["large"] . ', large],
          [' . $return["xlarge"] . ', xlarge]"';
}




/**
 *  Removing WP meta boxes
 */

add_action('admin_head', 'theme_remove_admin_metaboxes');
function theme_remove_admin_metaboxes()
{

    global $post;
    if (empty($post)) {

        return false;
    }

    $current_template = get_post_meta($post->ID, '_wp_page_template', true);
    // Always remove these for pages
    remove_meta_box('commentstatusdiv', 'page', 'normal');
    remove_meta_box('commentsdiv', 'page', 'normal');
    remove_meta_box('postcustom', 'page', 'normal');
    remove_meta_box('authordiv', 'page', 'normal');

    // Always remove these for posts
    remove_meta_box('postcustom', 'post', 'normal');
    remove_meta_box('trackbacksdiv', 'post', 'normal');
    remove_meta_box('commentstatusdiv', 'post', 'normal');
    remove_meta_box('commentsdiv', 'post', 'normal');
    remove_meta_box('tagsdiv-post_tag', 'post', 'side');
}

// Localise ajax for form
function theme_assets()
{
    if ($GLOBALS['pagenow'] !== 'wp-login.php' && !is_admin()) {

        wp_localize_script('foundation', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'theme_assets');

// Remove dashicons in frontend for unauthenticated users
add_action('wp_enqueue_scripts', 'bs_dequeue_dashicons');
function bs_dequeue_dashicons()
{
    if (! is_user_logged_in()) {
        wp_deregister_style('dashicons');
    }
}

// Disable auto image resizing
add_filter('big_image_size_threshold', '__return_false');


//Redirect user after login to modules
add_filter('login_redirect', 'infospace_login_redirect');
function infospace_login_redirect()
{
    // Change this to the url to Updates page.
    // $user = wp_get_current_user();
    // $valid_roles = ['employer', 'candidate'];

    //if (empty($the_roles)) {
    return home_url('/module/');
    exit;
    // }
}

//If a user is logged in and tries to access the home page, redirect them to the /module/ page
add_action('template_redirect', 'infospace_redirect_home_to_modules');
function infospace_redirect_home_to_modules()
{
    if (is_front_page() && is_user_logged_in()) {
        wp_redirect(home_url('/module/'));
        exit;
    }
}

function infospace_custom_excerpt_length($length)
{
    return 15;
}
add_filter('excerpt_length', 'infospace_custom_excerpt_length', 999);

// Helper function to build filter/sort URLs
function build_filter_url($filter = '', $orderby = '', $search = '')
{
    $base = get_permalink();
    $params = [];
    if ($filter && $filter !== 'all') $params['filter'] = $filter;
    if ($orderby) $params['orderby'] = $orderby;
    if ($search) $params['q'] = $search;
    return $base . (!empty($params) ? '?' . http_build_query($params) : '');
}

function get_file_svg_from_filename($filename, $colour = null)
{
    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $file_svg = '';
    $colour_attr = $colour ? esc_attr($colour)  : '';
    switch ($file_extension) {
        case 'pdf':
           ob_start();
            get_template_part('template-parts/svgs/_pdf', '', array('module_colour' => $colour_attr));
            $file_svg = ob_get_clean();
            break;
        case 'doc':
        case 'docx':

            ob_start();
            get_template_part('template-parts/svgs/_word-doc', '', array('module_colour' => $colour_attr));
            $file_svg = ob_get_clean();
            break;
        case 'xls':
        case 'xlsx':
           ob_start();
           get_template_part('template-parts/svgs/_excel', '', array('module_colour' => $colour_attr));
           $file_svg = ob_get_clean();
            break;
        case 'ppt':
        case 'pptx':
            ob_start();
            get_template_part('template-parts/svgs/_powerpoint', '', array('module_colour' => $colour_attr));
            $file_svg = ob_get_clean();
            break;
        default:
            $file_svg = 'unknown';
            break;
    }
    return $file_svg;
}


// Redirect search to custom search page
function redirect_search_to_custom_page() {
    if (is_search() ) {
        wp_redirect(home_url('/search-page/?q=' . urlencode(get_query_var('s'))));
        exit();
    }
}
add_action('template_redirect', 'redirect_search_to_custom_page');


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    //if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
   // }
}

function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a {
            background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/dist/assets/images/infospace-logo.svg');
		height: 65px;
		width: 320px;
		background-size: 182px 56px;
		background-repeat: no-repeat;
        	padding-bottom: 10px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );


function wpdocs_logout_redirect( $redirect_to, $requested_redirect_to, $user ) {

    $user_roles = $user->roles;
    $user_has_admin_role = in_array( 'administrator', $user_roles );

	if ( $user_has_admin_role ) :
		$redirect_to = home_url();
	else:
		$redirect_to = home_url();
	endif;

	return $redirect_to;
} 
add_filter( 'logout_redirect', 'wpdocs_logout_redirect', 9999, 3 );


add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
function logout_without_confirm($action, $result)
{
    /**
     * Allow logout without confirmation
     */
    if ($action == "log-out" && !isset($_GET['_wpnonce'])) {
        $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : 'url-you-want-to-redirect';
        $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
        header("Location: $location");
        die;
    }
}


// Function to check if the $prefix . 'user_is_active' meta field is checked on login and if not don't allow the user to log in


function check_user_is_active_on_login($user)
{

    
    if (is_wp_error($user)) {
        return $user;
    }

    $prefix = 'theme_fields';
    $is_active = get_user_meta($user->ID, $prefix . 'user_is_active', true);

    if ($is_active !== 'on') {
        return new WP_Error('inactive_user', __('Your account is inactive. Please contact the administrator.', 'hrinfospace'));
    }

    return $user;
}
add_filter('wp_authenticate_user', 'check_user_is_active_on_login', 10, 3);