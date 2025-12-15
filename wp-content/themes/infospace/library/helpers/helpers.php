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
function redirect_search_to_custom_page()
{
    if (is_search()) {
        wp_redirect(home_url('/search-page/?q=' . urlencode(get_query_var('s'))));
        exit();
    }
}
add_action('template_redirect', 'redirect_search_to_custom_page');


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    //if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
    // }
}


function my_login_logo()
{ ?>
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
add_action('login_enqueue_scripts', 'my_login_logo');


function wpdocs_logout_redirect($redirect_to, $requested_redirect_to, $user)
{

    $user_roles = $user->roles;
    $user_has_admin_role = in_array('administrator', $user_roles);

    if ($user_has_admin_role) :
        $redirect_to = home_url();
    else:
        $redirect_to = home_url();
    endif;

    return $redirect_to;
}
add_filter('logout_redirect', 'wpdocs_logout_redirect', 9999, 3);


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


// Create finance editor role with editor capabilities but restricted to resource_page post type
function create_finance_editor_role()
{
    // Remove the role if it already exists to avoid conflicts
    remove_role('finance_editor');

    // Get editor role capabilities
    $editor = get_role('editor');
    $editor_caps = $editor->capabilities;

    // Add the finance editor role with editor capabilities
    add_role('finance_editor', 'Finance Editor', $editor_caps);
}
add_action('init', 'create_finance_editor_role');

// Restrict finance editor access to only resource_page post type and specific pages
function restrict_finance_editor_access($query)
{
    if (is_admin() && !defined('DOING_AJAX') && $query->is_main_query()) {
        $user = wp_get_current_user();

        if (in_array('finance_editor', $user->roles)) {
            global $pagenow;

            // Restrict to edit.php (post list) for resource_page only
            if ($pagenow == 'edit.php' && (!isset($_GET['post_type']) || ($_GET['post_type'] !== 'resource_page') && $_GET['post_type'] != 'document') && $_GET['post_type'] != 'page_link') {
                if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'page') {
                    wp_redirect(admin_url('edit.php?post_type=resource_page'));
                    exit;
                }
            }

            // Restrict resource_page access to page ID 4611 and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'resource_page') {
                $children = get_posts(array(
                    'post_type' => 'resource_page',
                    'post_parent' => 4611,
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_ids = array_merge([4611], $children);
                $query->set('post__in', $allowed_ids);
            }
        }
    }
}
add_action('pre_get_posts', 'restrict_finance_editor_access');

// Hide menu items for finance editor
function hide_admin_menus_for_finance_editor()
{
    $user = wp_get_current_user();

    if (in_array('finance_editor', $user->roles)) {
        // Remove all menu items except the ones they should access
        remove_menu_page('index.php'); // Dashboard
        remove_menu_page('edit.php'); // Posts
        remove_menu_page('upload.php'); // Media (keep if needed)
        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('themes.php'); // Appearance
        remove_menu_page('plugins.php'); // Plugins
        remove_menu_page('users.php'); // Users
        remove_menu_page('tools.php'); // Tools
        remove_menu_page('options-general.php'); // Settings
        remove_menu_page('edit.php?post_type=module'); // Module
        remove_menu_page('edit.php?post_type=newsletter'); // Newsletter
        remove_menu_page('edit.php?post_type=enquiry'); // Enquiry
        remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('edit.php?post_type=favourite'); // Favourite
        remove_menu_page('admin.php?page=theme_options'); // Theme Options
        remove_menu_page('admin.php?page=wpseo_workouts'); // SEO Workouts

        // Keep only resource_page and specific pages access
        // The pages menu will be filtered by the query restriction above
    }
}
add_action('admin_menu', 'hide_admin_menus_for_finance_editor');

// Restrict post/page editing access
function restrict_finance_editor_post_access()
{
    $user = wp_get_current_user();

    if (in_array('finance_editor', $user->roles)) {
        global $pagenow;

        if (($pagenow == 'post.php' || $pagenow == 'post-new.php') && isset($_GET['resource_page'])) {
            $post_id = intval($_GET['post']);
            $post = get_post($post_id);

            if ($post) {
                // Allow resource_page post type
                if ($post->post_type == 'resource_page') {
                    //return;
                }

                // Allow page ID 4611 and its children
                if ($post->post_type == 'resource_page') {
                    if ($post_id == 4611 || $post->post_parent == 4611 || is_child_of_page($post_id, 4611)) {
                        return;
                    }
                }

                // Redirect if not allowed
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'restrict_finance_editor_post_access');




// Create HR editor role with editor capabilities but restricted to resource_page post type
function create_hr_editor_role()
{
    // Remove the role if it already exists to avoid conflicts
    remove_role('hr_editor');

    // Get editor role capabilities
    $editor = get_role('editor');
    $editor_caps = $editor->capabilities;

    // Add the HR editor role with editor capabilities
    add_role('hr_editor', 'HR Editor', $editor_caps);
}
add_action('init', 'create_hr_editor_role');

// Restrict HR editor access to only resource_page post type and specific pages
function restrict_hr_editor_access($query)
{
    if (is_admin() && !defined('DOING_AJAX') && $query->is_main_query()) {
        $user = wp_get_current_user();

        if (in_array('hr_editor', $user->roles)) {
            global $pagenow;

            // Restrict to edit.php (post list) for resource_page only
            if ($pagenow == 'edit.php' && (!isset($_GET['post_type']) || ($_GET['post_type'] !== 'resource_page') && $_GET['post_type'] != 'document') && $_GET['post_type'] != 'page_link') {
                if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'page') {
                    wp_redirect(admin_url('edit.php?post_type=resource_page'));
                    exit;
                }
            }

            // Restrict resource_page access to page ID 4594 and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'resource_page') {
                $children = get_posts(array(
                    'post_type' => 'resource_page',
                    'post_parent' => 4594,
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_ids = array_merge([4594], $children);
                $query->set('post__in', $allowed_ids);
            }
        }
    }
}
add_action('pre_get_posts', 'restrict_hr_editor_access');

// Hide menu items for HR editor
function hide_admin_menus_for_hr_editor()
{
    $user = wp_get_current_user();

    if (in_array('hr_editor', $user->roles)) {
        // Remove all menu items except the ones they should access
        remove_menu_page('index.php'); // Dashboard
        remove_menu_page('edit.php'); // Posts
        remove_menu_page('upload.php'); // Media (keep if needed)
        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('themes.php'); // Appearance
        remove_menu_page('plugins.php'); // Plugins
        remove_menu_page('users.php'); // Users
        remove_menu_page('tools.php'); // Tools
        remove_menu_page('options-general.php'); // Settings
        remove_menu_page('edit.php?post_type=module'); // Module
        remove_menu_page('edit.php?post_type=newsletter'); // Newsletter
        remove_menu_page('edit.php?post_type=enquiry'); // Enquiry
        remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('edit.php?post_type=favourite'); // Favourite
        remove_menu_page('admin.php?page=theme_options'); // Theme Options
        remove_menu_page('admin.php?page=wpseo_workouts'); // SEO Workouts

        // Keep only resource_page and specific pages access
        // The pages menu will be filtered by the query restriction above
    }
}
add_action('admin_menu', 'hide_admin_menus_for_hr_editor');

// Restrict post/page editing access
function restrict_hr_editor_post_access()
{
    $user = wp_get_current_user();

    if (in_array('hr_editor', $user->roles)) {
        global $pagenow;

        if (($pagenow == 'post.php' || $pagenow == 'post-new.php') && isset($_GET['resource_page'])) {
            $post_id = intval($_GET['post']);
            $post = get_post($post_id);

            if ($post) {
                // Allow resource_page post type
                if ($post->post_type == 'resource_page') {
                    //return;
                }

                // Allow page ID 4594 and its children
                if ($post->post_type == 'resource_page') {
                    if ($post_id == 4594 || $post->post_parent == 4594 || is_child_of_page($post_id, 4594)) {
                        return;
                    }
                }

                // Redirect if not allowed
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'restrict_hr_editor_post_access');



// Create HSW editor role with editor capabilities but restricted to resource_page post type
function create_hsw_editor_role()
{
    // Remove the role if it already exists to avoid conflicts
    remove_role('hsw_editor');

    // Get editor role capabilities
    $editor = get_role('editor');
    $editor_caps = $editor->capabilities;

    // Add the HSW editor role with editor capabilities
    add_role('hsw_editor', 'HSW Editor', $editor_caps);
}
add_action('init', 'create_hsw_editor_role');

// Restrict HSW editor access to only resource_page post type and specific pages
function restrict_hsw_editor_access($query)
{
    if (is_admin() && !defined('DOING_AJAX') && $query->is_main_query()) {
        $user = wp_get_current_user();

        if (in_array('hsw_editor', $user->roles)) {
            global $pagenow;

            // Restrict to edit.php (post list) for resource_page only
            if ($pagenow == 'edit.php' && (!isset($_GET['post_type']) || ($_GET['post_type'] !== 'resource_page') && $_GET['post_type'] != 'document') && $_GET['post_type'] != 'page_link') {
                if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'page') {
                    wp_redirect(admin_url('edit.php?post_type=resource_page'));
                    exit;
                }
            }

            // Restrict resource_page access to page ID 4536 and its children
            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'resource_page') {
                $children = get_posts(array(
                    'post_type' => 'resource_page',
                    'post_parent' => 4536,
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $allowed_ids = array_merge([4536], $children);
                $query->set('post__in', $allowed_ids);
            }
        }
    }
}
add_action('pre_get_posts', 'restrict_hsw_editor_access');

// Hide menu items for HSW editor
function hide_admin_menus_for_hsw_editor()
{
    $user = wp_get_current_user();

    if (in_array('hsw_editor', $user->roles)) {
        // Remove all menu items except the ones they should access
        remove_menu_page('index.php'); // Dashboard
        remove_menu_page('edit.php'); // Posts
        remove_menu_page('upload.php'); // Media (keep if needed)
        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('themes.php'); // Appearance
        remove_menu_page('plugins.php'); // Plugins
        remove_menu_page('users.php'); // Users
        remove_menu_page('tools.php'); // Tools
        remove_menu_page('options-general.php'); // Settings
        remove_menu_page('edit.php?post_type=module'); // Module
        remove_menu_page('edit.php?post_type=newsletter'); // Newsletter
        remove_menu_page('edit.php?post_type=enquiry'); // Enquiry
        remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('edit.php?post_type=favourite'); // Favourite
        remove_menu_page('admin.php?page=theme_options'); // Theme Options
        remove_menu_page('admin.php?page=wpseo_workouts'); // SEO Workouts

        // Keep only resource_page and specific pages access
        // The pages menu will be filtered by the query restriction above
    }
}
add_action('admin_menu', 'hide_admin_menus_for_hsw_editor');

// Restrict post/page editing access
function restrict_hsw_editor_post_access()
{
    $user = wp_get_current_user();

    if (in_array('hsw_editor', $user->roles)) {
        global $pagenow;

        if (($pagenow == 'post.php' || $pagenow == 'post-new.php') && isset($_GET['resource_page'])) {
            $post_id = intval($_GET['post']);
            $post = get_post($post_id);

            if ($post) {
                // Allow resource_page post type
                if ($post->post_type == 'resource_page') {
                    //return;
                }

                // Allow page ID 4536 and its children
                if ($post->post_type == 'resource_page') {
                    if ($post_id == 4536 || $post->post_parent == 4536 || is_child_of_page($post_id, 4536)) {
                        return;
                    }
                }

                // Redirect if not allowed
                wp_redirect(admin_url('edit.php?post_type=resource_page'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'restrict_hsw_editor_post_access');




// Helper function to check if a page is a child of a specific page
function is_child_of_page($page_id, $parent_id)
{
    $page = get_post($page_id);
    if ($page && $page->post_parent) {
        if ($page->post_parent == $parent_id) {
            return true;
        }
        return is_child_of_page($page->post_parent, $parent_id);
    }
    return false;
}

// add descriptive classes to user admin page body
function custom_user_admin_body_class($classes)
{
    $classes .= ' user-role-' . implode(' user-role-', wp_get_current_user()->roles);
    return trim($classes);
}
add_filter('admin_body_class', 'custom_user_admin_body_class'); 

// Remove Personal Options from user profile
function remove_personal_options()
{
    echo '<style>
        .user-rich-editing-wrap,
        .user-syntax-highlighting-wrap,
        .user-admin-color-wrap,
    .user-nickname-wrap,
        .user-url-wrap,
        .user-description-wrap,
        .user-profile-picture,
        .user-comment-shortcuts-wrap,
        .user-display-name-wrap,
        .yoast.yoast-settings,
        .application-passwords,
        .user-role-main #dashboard-widgets,
        .user-role-main ul.subsubsub {
            display: none !important;
        }
          
    </style>';
}
//add_action('admin_head-profile.php', 'remove_personal_options');
//add_action('admin_head-user-edit.php', 'remove_personal_options');
//add_action('admin_head-index.php', 'remove_personal_options');

add_action('admin_head', 'remove_personal_options');

/**
 * Remove annoying Yoast social fields from user profile forms.
 */
function yoast_seo_admin_user_remove_social( $contactmethods ) {
    // Return an empty array to remove all Yoast-added social fields.
    return array();
}
add_filter( 'user_contactmethods', 'yoast_seo_admin_user_remove_social', 100, 1 );