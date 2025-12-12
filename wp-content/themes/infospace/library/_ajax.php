<?php

/**
 *
 * AJAX Functions
 *
 */

/**
 * Contact form
 */

add_action('wp_ajax_nopriv_contact_form', 'theme_contact_form');
add_action('wp_ajax_contact_form', 'theme_contact_form');
function theme_contact_form()
{
    if (defined('DOING_AJAX') && DOING_AJAX) {
        global $settings;
        global $prefix;

        // Do recaptcha check
        $recaptcha_response = sanitize_text_field($_POST['recaptcha_response']);
        $recaptcha_secret = '6Ld4_iQsAAAAAJZCHORH432pyFxffNPyMckL2WJd';
        $recaptcha_verify = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
        $recaptcha_result = json_decode(wp_remote_retrieve_body($recaptcha_verify));
        if (!$recaptcha_result->success || $recaptcha_result->score < 0.5) {
            echo json_encode([
                'success' => false,
                'message' => '<p>reCAPTCHA verification failed. Please try again.</p>'
            ]);
            die();
        }


        // Format $_POST data
        $data = array_map('esc_attr', $_POST);

        // Only allow certain fields from user
        $filtered = array_filter(array_keys($data), function ($key) {
            return in_array($key, [
                'contact_page',
                'contact_name',
                'contact_email',
                'contact_company',
                'contact_message',
                'contact_tel',
                'contact_check',
            ]);
        });
        $filtered_data = array_intersect_key($data, array_flip($filtered));
        $page = sanitize_text_field($filtered_data['contact_page']);
        $name = sanitize_text_field($filtered_data['contact_name']);
        $email = sanitize_text_field($filtered_data['contact_email']);
        $company = sanitize_text_field($filtered_data['contact_company']);
        $tel = sanitize_text_field($filtered_data['contact_tel']);
        $message = sanitize_text_field($filtered_data['contact_message']);
        $check = $filtered_data['contact_check'] ? 'Yes' : "No";

       $current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
       $moduleMeta = theme_get_meta($current_module_id_global);
        // set default email
        $defaultAdminEmail =  empty($settings[$prefix . 'email']) ? get_option('admin_email') : $settings[$prefix . 'email'];
        // get module email if set
        //$admin_email = isset($moduleMeta->module_email_address) ? $moduleMeta->module_email_address : $defaultAdminEmail;
        $admin_email = 'barry@creativesponge.co.uk';

        // add prefix for meta
        $meta = [];
        foreach ($filtered_data as $key => $val) {
            $meta[$prefix . $key] = $val;
        }
        $meta[$prefix . 'contact_recipient'] = $admin_email;

        // Create post object
        $new_enquiry = array(
            'post_title' => wp_strip_all_tags($name),
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'enquiry',
            'meta_input' => $meta
        );

        // Insert the post into the database
        $post_id = wp_insert_post($new_enquiry);

        // Create email html
        $body = '';

        // foreach ($data as $key => $value) {
        //     $key = str_replace('_', ' ', $key);
        //     $value = is_array($value) ? implode("<br />", $value) : $value;
        //     $body .= '<p><strong>' . $key . ':</strong> ' . $value . '</p>';
        // }

        $body .= '<p><strong>Landing page:</strong> ' . $page . '</p>';
        $body .= '<p><strong>Name:</strong> ' . $name . '</p>';
        $body .= '<p><strong>Email:</strong> ' . $email . '</p>';
        $body .= '<p><strong>Company:</strong> ' . $company . '</p>';
        $body .= '<p><strong>Telephone:</strong> ' . $tel . '</p>';
        $body .= '<p><strong>Message:</strong> ' . $message . '</p>';
        $body .= '<p><strong>Consent:</strong> ' . $check . '</p>';

        // Get the enquiry email address


        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = "From: " . $name . " <" . $admin_email . ">";

        $subject = 'Website enquiry from: ' . $name;

        echo json_encode([
            'success' => wp_mail($admin_email, $subject, $body, $headers)
        ]);
    }

    die();
}

/**
 * Posts load more for posts
 */

// news_assets
function news_assets()
{

    wp_localize_script('foundation', 'newsCats', array(
        'newsnonce'    => wp_create_nonce('newsCats'),
        'news_ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'news_assets', 100);

function posts_filter_posts()
{
    if (!isset($_POST['newsnonce']) || !wp_verify_nonce($_POST['newsnonce'], 'newsCats'))
        die('Permission denied');
    /**
     * Default response
     */
    $responsenews = [
        'status'  => 500,
        'message' => 'Something is wrong, please try again later ...',
        'content' => false,
        'found'   => 0
    ];
    //$tax  = sanitize_text_field($_POST['params']['tax']);
    //$term = sanitize_text_field($_POST['params']['term']);
    $post_type = 'post';
    //$cats = sanitize_text_field($_POST['params']['cats']);
    $decodedParams = urldecode($_POST['params']);
    $params = json_decode(stripslashes($decodedParams), true);
    $page = intval($params['page']);
    $qty = intval($params['qty']);
    $order = sanitize_text_field($params['order']);
    $searchTerm = !empty($params['search']) ? sanitize_text_field($params['search']) : '';
    $cats = !empty($params['cats']) ? $params['cats'] : [];
    $tax_qry = [];

    //$moduleId = $_SESSION['current_module_slug'] ?? '';



    if (!empty($cats)) {
        $tax_qry['relation'] = 'AND';
        foreach ($cats as $cat) {
            $tax_qry[] = [
                'taxonomy' => $cat['filter'],
                'field'    => 'slug',
                'terms'    => $cat['term'],
            ];
        }
    }

    /**
     * Setup query
     */

    $args = [
        'paged'          => $page,
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'posts_per_page' => $qty,
        'tax_query'      => $tax_qry,


    ];
    // Search
    if ($searchTerm) {
        $args['s'] = $searchTerm;
        $args['orderby'] = 'relevance';
    } else {
        $args['orderby'] = 'title date';
    }
    $args['order'] = $order;

    $qry = new WP_Query($args);

    ob_start();

    if ($qry->have_posts()) : ?>


        <?php while ($qry->have_posts()) : $qry->the_post();
        ?>

            <?php $postId = get_the_ID();

            $termsString = "heading";
            $args = array($termsString);

            if ($postId) {
                get_template_part('template-parts/large-teaser', '', $args);
            } ?>

        <?php endwhile; ?>


    <?php
        /**
         * Pagination
         */
        vb_ajax_pager($qry, $page, $searchTerm);

        $responsenews = [
            'status' => 200,
            'post_count' => $qry->post_count,
            'found_posts' => (int)$qry->found_posts,
            'found' => $qry->found_posts
        ];

    else :
        $responsenews = [
            'status'  => 201,
            'message' => 'No ' . $post_type . 's found',
        ];
    endif;
    $responsenews['content'] = ob_get_clean();
    //$responsenews['content'] = $decodedParams;
    die(json_encode($responsenews));
}
add_action('wp_ajax_posts_do_filter_posts', 'posts_filter_posts');
add_action('wp_ajax_nopriv_posts_do_filter_posts', 'posts_filter_posts');

/**
 * Pagination
 */
function vb_ajax_pager($query = null, $paged = 1)
{
    if (!$query)
        return;

    if ($query->max_num_pages > 1) : ?>
        <?php $pagenum = $query->query_vars['paged'] < 1 ? 1 : $query->query_vars['paged']; ?>
        <div class="pagination">
            <?php if ($paged > 1) { ?>
                <a href="#page=<?php echo $paged - 1 ?>">
                    << /a>
                    <?php } ?>

                    <?php echo "P" . $pagenum . " <span>of</span> " . $query->max_num_pages; ?>

                    <?php if ($paged < $query->max_num_pages) { ?>
                        <a href="#page=<?php echo $paged + 1 ?>">></a>
                    <?php } ?>

        </div>
<?php endif;
}

// Log links
function log_links()
{
    wp_localize_script('foundation', 'ajaxVars', array(
        'linksnonce'    => wp_create_nonce('ajaxVars'),
        'links_ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'log_links', 100);

add_action('wp_ajax_log_link_click', 'log_link_click');
add_action('wp_ajax_nopriv_log_link_click', 'log_link_click');

function log_link_click()
{
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $link_id = intval($input['link_id']);
    $link_url = sanitize_text_field($input['link_url']);
    $page_title = sanitize_text_field($input['page_title']);
    //error_log(print_r($input, true) . "Logging link click: link_id=$link_id, link_url=$link_url, page_title=$page_title");
    // log the interaction
    log_user_interaction($link_url, $link_id, 19, 'Clicked link', $page_title);
    wp_die();
}

// Log Downloads
function log_downloads()
{
    wp_localize_script('foundation', 'ajaxVarsDownloads', array(
        'downloadsnonce'    => wp_create_nonce('ajaxVarsDownloads'),
        'downloads_ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'log_downloads', 100);

add_action('wp_ajax_log_download_click', 'log_download_click');
add_action('wp_ajax_nopriv_log_download_click', 'log_download_click');
function log_download_click()
{
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $download_id = intval($input['download_id']);
    $download_url = sanitize_text_field($input['download_url']);
    $file_name = sanitize_text_field($input['file_name']);
    //error_log(print_r($input, true) . "Logging download click: download_id=$download_id, download_url=$download_url, file_name=$file_name");
    // log the interaction
    log_user_interaction($download_url, $download_id, 12, 'Downloaded file', $file_name);
    wp_die();
}

// Log Newsletters
function log_newsletters()
{
    wp_localize_script('foundation', 'ajaxVarsNewsletters', array(
        'newslettersnonce'    => wp_create_nonce('ajaxVarsNewsletters'),
        'newsletters_ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'log_newsletters', 100);

add_action('wp_ajax_log_newsletter_click', 'log_newsletter_click');
add_action('wp_ajax_nopriv_log_newsletter_click', 'log_newsletter_click');
function log_newsletter_click()
{
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $newsletter_id = intval($input['newsletter_id']);
    $newsletter_url = sanitize_text_field($input['newsletter_url']);

    $newsletter_name = sanitize_text_field($input['file_name']);
    //error_log(print_r($input, true) . "Logging newsletter click: newsletter_id=$newsletter_id, newsletter_url=$newsletter_url, newsletter_name=$newsletter_name");
    // log the interaction
    log_user_interaction($newsletter_url, $newsletter_id, 9, 'Clicked newsletter', $newsletter_name);
    wp_die();
}

// Autocomplete assets
function autocomplete_assets()
{

    wp_localize_script('foundation', 'autoComplete', array(
        'data_fetch_nonce'    => wp_create_nonce('autoComplete'),
        'autocomplete_ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'autocomplete_assets', 100);
