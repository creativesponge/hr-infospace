<?php


// the ajax function
add_action('wp_ajax_data_fetch', 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch', 'data_fetch');
function data_fetch()
{

    global $prefix;

    $keyword = sanitize_text_field($_POST['keyword']);
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
    $moduleMeta = get_current_module_meta($current_module_id_global);
    $moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '';
    $child_pages = get_module_child_pages_using_module_id($current_module_id_global);

    $args = array('module_colour' => $moduleColour);
    ob_start();
    get_template_part('template-parts/svgs/_linkout', '', $args);
    $linkout_svg = ob_get_clean();

    ob_start();
    get_template_part('template-parts/svgs/_screen', '', $args);
    $resource_svg = ob_get_clean();

    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verify nonce
    if (!isset($_POST['data_fetch_nonce']) || !wp_verify_nonce($_POST['data_fetch_nonce'], 'autoComplete'))
        wp_die('Permission denied');

    // Validate and sanitize input
    if (!isset($_POST['keyword']) || empty($_POST['keyword'])) {
        echo 'Invalid';
        wp_die();
    }


    //Get all page links which are active to filter later
    $active_page_links = array();
    $active_page_links = get_posts(array(
        'post_type' => 'page_link',
        'numberposts' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => $prefix . 'page_link_is_active',
                'value' => 'on',
                'compare' => '='
            )
        )
    ));

    // Find posts matching current module's child pages
    if (!empty($child_pages)) {
        $matching_post_ids = [];

        foreach ($child_pages as $child_page_id) {
            $resourceMeta = theme_get_meta($child_page_id);

            // Add in links
            $attachedLinkId = isset($resourceMeta->resource_attached_links) ? $resourceMeta->resource_attached_links : '';

            if ($attachedLinkId) {
                if (is_array($attachedLinkId)) {
                    foreach ($attachedLinkId as $linkId) {
                        if (in_array($linkId, $active_page_links)) {   // check for active links only  
                            $matching_post_ids[] = $linkId;
                        }
                    }
                    //$matching_post_ids = array_merge($matching_post_ids, $attachedLinkId);
                } else {
                    if (in_array($attachedLinkId, $active_page_links)) {  // check for active links only  
                        $matching_post_ids[] = $attachedLinkId;
                    }
                }
            }

            // Add in documents
            $attachedDocId = isset($resourceMeta->resource_attached_documents) ? $resourceMeta->resource_attached_documents : '';

            if ($attachedDocId) {
                if (is_array($attachedDocId)) {
                    $matching_post_ids = array_merge($matching_post_ids, $attachedDocId);
                } else {
                    $matching_post_ids[] = $attachedDocId;
                }
            }

            // Add in resources as well
            $matching_post_ids[] = $child_page_id;
        }
        $matching_post_ids = array_unique($matching_post_ids);
    }


    // Limit search length to prevent abuse
    if (strlen($keyword) > 100) {

        wp_die('Search term too long');
    }

    $loopArgs = array(
        'posts_per_page' => 10, // Limit results to prevent performance issues
        's' => $keyword,
        //'post_type' => array('resource_page'),
        'post_type' => array('resource_page', 'document', 'page_link'),
        'post_status' => 'publish'
    );

    // filter for this module's child pages only
    if (!empty($matching_post_ids)) {
        $loopArgs['post__in'] = $matching_post_ids;
    }

    $the_query = new WP_Query($loopArgs);

    if ($the_query->have_posts()) :
        echo '<ul>';
        while ($the_query->have_posts()): $the_query->the_post(); ?>
            <?php $result_Id = get_the_ID();
            $post_type = get_post_type($result_Id);
            $resultIcon = $post_type == 'resource_page' ? $resource_svg : $linkout_svg;
            ?>
            <?php if ($post_type == 'document') {  ?>
                <?php $attached_doc_array = get_post_meta($result_Id, $prefix . 'document_files', true);

                foreach ($attached_doc_array as $doc) {

                    // Check the start and end dates
                    $now = time();
                    $start_date = isset($doc[$prefix . 'start_date']) ?  $doc[$prefix . 'start_date'] : null;
                    $end_date = isset($doc[$prefix . 'end_date']) ? $doc[$prefix . 'end_date'] : null;
                    if (($start_date && $now < $start_date) || ($end_date && $now > $end_date)) {
                        // Skip this file as it is not currently active
                        continue;
                    }
                    $result_Id = $doc['theme_fieldsdoc_uploaded_file_id'];
                    $filename = $doc["theme_fieldsdoc_uploaded_file"];

                    $file_svg = get_file_svg_from_filename($filename, $moduleColour);
                    $doc_url = '/download-document/' . $result_Id; ?>

                    <li><a href="<?php echo esc_url($doc_url); ?>"><?php echo $file_svg; ?><?php echo esc_html(get_the_title()); ?></a></li>

                <?php
                }
            } else { ?>

                <?php
                $is_external_link = $post_type === 'page_link';
                $link_url = $is_external_link ? get_post_meta($result_Id, $prefix . 'page_link_url', true) : '';
                $permalink = (!empty($link_url)) ? $link_url : get_permalink($result_Id);
                $target = (!empty($link_url)) ? '_blank' : '_self';
                ?>
                <li><a href="<?php echo esc_url($permalink); ?>" target="<?php echo esc_attr($target ?? '_self'); ?>"><?php echo $resultIcon; ?><?php echo esc_html(get_the_title()); ?></a></li>
            <?php } ?>



<?php endwhile;
        echo '</ul>';
        wp_reset_postdata();
    endif;

    wp_die(); // Use wp_die() instead of die()
}
