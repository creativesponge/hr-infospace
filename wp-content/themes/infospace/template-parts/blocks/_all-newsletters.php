<?php $block_attributes = get_query_var('attributes'); ?>
<?php //print_r($block_attributes); 
?>
<?php
$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';

$moduleMeta = get_current_module_meta($current_module_id_global);
$post_id = get_the_ID();
$child_pages = get_module_child_pages_using_module_id($current_module_id_global);
$policiesTabText = $current_module_id_global == 1738 ? 'Compliance code' : 'Policies';
$documentsTabText = $current_module_id_global == 1738 ? 'Guidance & Forms' : 'Documents';
$moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '#004481';

ob_start();
get_template_part('template-parts/svgs/_download-icon');
$download_svg = ob_get_clean();

ob_start();
get_template_part('template-parts/svgs/_linkout');
$linkout_svg = ob_get_clean();

ob_start();
get_template_part('template-parts/svgs/_screen');
$screen_svg = ob_get_clean();

// get favourites for this user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_user_favourites = isset($_SESSION['current_user_favourite_ids']) ? $_SESSION['current_user_favourite_ids'] : '';

?>
<section class="all-newsletters">

    <?php // Module switcher block
    if (is_user_logged_in()) :
    echo '<div class="all-favourites__switcher module-tabs">';
    get_template_part(
        'template-parts/module-switcher',
        null,
        array(
            'module_id' => $moduleMeta['module_id'] ?? null,
            'post_id' => $moduleMeta['attached_resources'] ?? null,
            'attached_resources' => $moduleMeta['attached_resources'] ?? null,
            'module_colour' => $moduleMeta['module_color'] ?? null,
        )
    );
    echo '</div>';
    endif;
    ?>


    <div class="all-newsletters__container">

        <?php
        // Show the most recent 'newsletter' custom post type

        $newsletter_args = array(
            'post_type'      => 'newsletter',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',

        );
        $newsletter_query = new WP_Query($newsletter_args);

        if ($newsletter_query->have_posts()) {
            echo '<div class="module-panel module-panel--newsletter">';

            echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
            echo '<h2>Newsletters</h2>';
            echo '</div>';

            echo '<div class="module-panel__content">';

            while ($newsletter_query->have_posts()) {

                $newsletter_query->the_post();
                $newsletterID = get_the_ID();
                $newsletter_meta = theme_get_meta($newsletterID);
                $updatedDate = isset($newsletter_meta->newsletter_date) && !empty($newsletter_meta->newsletter_date)
                    ? date('F j, Y', $newsletter_meta->newsletter_date)
                    : get_the_date('F j, Y', $newsletterID);
                $startDate = isset($newsletter_meta->newsletter_start_date) ? $newsletter_meta->newsletter_start_date : '';
                $endDate = isset($newsletter_meta->newsletter_end_date) ? $newsletter_meta->newsletter_end_date : '';
                $attached_resources = isset($newsletter_meta->newsletter_attached_resource_pages) ? $newsletter_meta->newsletter_attached_resource_pages : '';
                $attached_documents_url = isset($newsletter_meta->newsletter_file) ? $newsletter_meta->newsletter_file : '';
                //var_dump($newsletter_meta);
                $attached_documents_link = isset($newsletter_meta->newsletter_link) ? $newsletter_meta->newsletter_link : '';
                $filename = isset($newsletter_meta->newsletter_file) ? $newsletter_meta->newsletter_file : '';
                $file_svg = $attached_documents_link ? $screen_svg : get_file_svg_from_filename($filename);
                $attached_documents = '';
                $docId = '';

                if (!empty($endDate) && ($endDate < time())) {
                    continue;
                }
                // Check start date
                if (!empty($startDate) && ($startDate > time())) {
                    continue;
                }

                if (!empty($attached_documents_url)) {
                    // Try to get the attachment ID from the URL
                    $attachment = attachment_url_to_postid($attached_documents_url);
                    if ($attachment) {
                        $docId = $attachment;
                    }
                    $doc_url = $docId ? '/download-document/' . $docId : "";
                } else {
                    $doc_url = '';
                }
                if (
                    !empty($doc_url || $attached_documents_link) &&
                    array_intersect((array)$attached_resources, (array)$child_pages)
                ) {
                    echo '<div class="module-panel__block">';
                    echo '<div class="module-panel__content-grid" style="border-color: ' . esc_html($moduleMeta['module_color']) . ';">';
                    echo '<div class="module-panel__content-top">';
                    echo '<h3>' . $file_svg . '<span>' . get_the_title() . '</span></h3>';
                    echo '<p class="newsletter-date hide-for-large">Updated on: ' . $updatedDate . '</p>';
                    echo '</div>';
                    if ($attached_documents_link) {
                        echo '<a href="' . esc_url($attached_documents_link) . '" rel="nofollow" class="download-link download-link--out show-for-large" target="_blank">View ' . $linkout_svg . '</a>';
                    } else {
                        echo '<a href="' . esc_url($doc_url) . '" rel="nofollow" class="download-link show-for-large">Download ' . $download_svg . '</a>';
                    }

                    echo '</div>';
                    echo '<p class="newsletter-date show-for-large">Updated on: ' . $updatedDate . '</p>';

                    echo '<div class="module-panel__content-bottom">';
                    echo '<a href="' . esc_url($doc_url) . '" rel="nofollow" class="download-link hide-for-large">Download ' . $download_svg . '</a>';
                    echo '</div>';
                    echo '</div>';
                   

                  
                }
            }

            echo '</div>';
            echo '</div>';
            wp_reset_postdata();
        }

        ?>
    </div>

</section>