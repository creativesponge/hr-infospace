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
get_template_part('template-parts/svgs/_linkout');
$linkout_svg = ob_get_clean();

// get favourites for this user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_user_favourites = isset($_SESSION['current_user_favourite_ids']) ? $_SESSION['current_user_favourite_ids'] : '';

?>
<section class="all-favourites">
    <div class="all-favourites__container">

        <?php
        // Show the favorites 
        echo '<div class="resource-module__white-background full-width">';
        $favourite_args = array(
            'post_type'      => 'favourite',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'author__in'     => array(get_current_user_id()),
        );
        $favourite_query = new WP_Query($favourite_args);

        if ($favourite_query->have_posts()) {

            $attached_resources_list = array();
            $attached_links_list = array();
            $attached_documents_list = array();
            // loop through favourites
            while ($favourite_query->have_posts()) {
                $favourite_query->the_post();
                $favouriteID = get_the_ID();

                $favourite_meta = theme_get_meta($favouriteID);
                //echo '<pre>';
                //var_dump($favourite_meta);
                //echo '</pre>';
                $attached_documents = isset($favourite_meta->favourite_attached_documents) ? $favourite_meta->favourite_attached_documents : '';
                $attached_links = isset($favourite_meta->favourite_attached_links) ? $favourite_meta->favourite_attached_links : '';
                $attached_resources = isset($favourite_meta->favourite_attached_resources) ? $favourite_meta->favourite_attached_resources : '';

                if (!empty($attached_documents)) {
                    $attached_documents_list = array_merge($attached_documents_list, (array)$attached_documents);
                }
                if (!empty($attached_resources)) {
                    $attached_resources_list = array_merge($attached_resources_list, (array)$attached_resources);
                }
                if (!empty($attached_links)) {
                    $attached_links_list = array_merge($attached_links_list, (array)$attached_links);
                }
            }
            // Remove any content not attached to this page for resources
            $attached_resources_list = array_intersect($attached_resources_list, $child_pages);
            $fav_doc_html = '';
            $fav_res_html = '';
            $fav_link_html = '';
            // Favourite Documents
            // loop through documents and get the files
            if (!empty($attached_documents_list)) {
                $fav_doc_html .= '<ul>';
                foreach ($attached_documents_list as $doc) {
                    $fav_doc_meta = theme_get_meta($doc);

                    $attached_fav_doc_array = isset($fav_doc_meta->document_files) ? $fav_doc_meta->document_files : '';

                    // check if it is attached to a resource in this module
                    $child_attached_doc_ids = array();

                    foreach ($child_pages as $child_page_id) {
                        $child_meta = theme_get_meta($child_page_id);

                        $attached_docs = isset($child_meta->resource_attached_documents) ? $child_meta->resource_attached_documents : [];

                        if (!empty($attached_docs)) {
                            $child_attached_doc_ids = array_merge($child_attached_doc_ids, (array)$attached_docs);
                        }
                    }
                    $child_attached_doc_ids = array_unique($child_attached_doc_ids);

                    if (!in_array($doc, $child_attached_doc_ids)) {
                        continue;
                    }
                    if (!empty($attached_fav_doc_array) && !empty($attached_fav_doc_array[0]['theme_fieldsdoc_uploaded_file_id'])) {
                        $fav_doc_id = $attached_fav_doc_array[0]['theme_fieldsdoc_uploaded_file_id'];
                        $filename = $attached_fav_doc_array[0]["theme_fieldsdoc_uploaded_file"];
                        $file_svg = get_file_svg_from_filename($filename);
                        $doc_url = '/download-document/' . $fav_doc_id;

                        // Create favoutite button
                        $button_class = ' add-to-favourites--filled';
                        $button_text = 'Remove from \'my favourites\'';

                        ob_start();
                        echo '<button class="add-to-favourites add-to-favourites--small' . esc_attr($button_class) . '" data-id="' . esc_attr($doc) . '" data-name="' . esc_attr(get_the_title($doc)) . '" data-type="' . esc_attr(get_post_type($doc)) . '">';
                        get_template_part('template-parts/svgs/_favourite');
                        echo '<span class="show-for-sr">' . esc_html($button_text) . '</span>';
                        echo '</button>';

                        $favourite_svg = ob_get_clean();


                        // Store the HTML output for later use
                        $fav_doc_html .= '<li><a href="' . esc_url($doc_url) . '" rel="nofollow"><span>' . $file_svg . get_the_title($doc) . '</span>' . $favourite_svg . '</a></li>';
                    }
                }
                $fav_doc_html .= '</ul>';
            }


            // Favourite Resources
            // loop through resources and get the links
            $attached_resources_list = array_unique($attached_resources_list);
            if (!empty($attached_resources_list)) {
                $fav_res_html .=  '<ul>';
                foreach ($attached_resources_list as $res) {
                    // Create favoutite button
                    $button_class = ' add-to-favourites--filled';
                    $button_text = 'Remove from \'my favourites\'';

                    ob_start();
                    echo '<button class="add-to-favourites add-to-favourites--small' . esc_attr($button_class) . '" data-id="' . esc_attr($res) . '" data-name="' . esc_attr(get_the_title($res)) . '" data-type="' . esc_attr(get_post_type($res)) . '">';
                    get_template_part('template-parts/svgs/_favourite');
                    echo '<span class="show-for-sr">' . esc_html($button_text) . '</span>';
                    echo '</button>';

                    $favourite_svg = ob_get_clean();

                    $fav_res_html .= '<li><a href="' . esc_url(get_permalink($res)) . '" rel="nofollow"><span>' . $linkout_svg . get_the_title($res) . '</span>' . $favourite_svg . '</a></li>';
                }
                $fav_res_html .=  '</ul>';
            }

            // Favourite Links
            // loop through links and get the links
            $attached_links_list = array_unique($attached_links_list);

            if (!empty($attached_links_list)) {
                $fav_link_html .= '<ul>';
                foreach ($attached_links_list as $link) {

                    // check if it is attached to a resource in this module
                    $child_attached_link_ids = array();
                    foreach ($child_pages as $child_page_id) {
                        $child_meta = theme_get_meta($child_page_id);
                        $attached_links = isset($child_meta->resource_attached_links) ? $child_meta->resource_attached_links : [];
                        if (!empty($attached_links)) {
                            $child_attached_link_ids = array_merge($child_attached_link_ids, (array)$attached_links);
                        }
                    }
                    $child_attached_link_ids = array_unique($child_attached_link_ids);

                    if (!in_array($link, $child_attached_link_ids)) {
                        continue;
                    }
                    $link_meta = theme_get_meta($link);
                    $link_url = isset($link_meta->page_link_url) ? $link_meta->page_link_url : '';
                    if (empty($link_url)) {
                        continue;
                    }
                    // Create favoutite button
                    $button_class = ' add-to-favourites--filled';
                    $button_text = 'Remove from \'my favourites\'';

                    ob_start();
                    echo '<button class="add-to-favourites add-to-favourites--small' . esc_attr($button_class) . '" data-id="' . esc_attr($link) . '" data-name="' . esc_attr(get_the_title($link)) . '" data-type="' . esc_attr(get_post_type($link)) . '">';
                    get_template_part('template-parts/svgs/_favourite');
                    echo '<span class="show-for-sr">' . esc_html($button_text) . '</span>';
                    echo '</button>';

                    $favourite_svg = ob_get_clean();

                    $fav_link_html .= '<li><a href="' . esc_url($link_url) . '" rel="nofollow"><span>' . $linkout_svg . esc_html(get_the_title($link)) . '</span>' . $favourite_svg . '</a></li>';
                }
                $fav_link_html .= '</ul>';
            }

            // Module switcher block
            if (is_user_logged_in()) :
            echo '<div class="all-favourites__switcher  module-tabs">';
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

            // Output results
            echo '<div class="module-panel module-panel--favourites">';
            echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
            echo '<div>';
            get_template_part(
                'template-parts/svgs/_favourite'
            );
            echo '<h2>Your favourites</h2>';
            echo '</div>';
            echo '</div>';
            echo '<div class="module-panel__content tabbed-content">';
            echo '<ul class="tab-list tabbed-content__list" role="tablist"></ul>';
            if (!empty($fav_doc_html) && $fav_doc_html != '<ul></ul>') {
                echo '<div class="tabbed-content__panel active">';
                echo '<h3 class="show-for-sr">' . $policiesTabText . ' & ' . $documentsTabText . '</h3>';
                echo $fav_doc_html;
                echo '</div>';
            }
            if (!empty($fav_res_html) && $fav_res_html != '<ul></ul>') {
                echo '<div class="tabbed-content__panel active">';
                echo '<h3 class="show-for-sr">Pages</h3>';
                echo $fav_res_html;
                echo '</div>';
            }
            if (!empty($fav_link_html) && $fav_link_html != '<ul></ul>') {
                echo '<div class="tabbed-content__panel active">';

                echo '<h3 class="show-for-sr">Links</h3>';
                echo $fav_link_html;
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            wp_reset_postdata();
        }

        echo '</div>';
        ?>
        <button class="button-back" style="background-color: <?php echo $moduleColour; ?>" onclick="history.back()">Back</button>
    </div>

</section>