<?php
global $prefix;
global $settings;

$siteGetInTouchText = isset($settings[$prefix . 'get_in_touch_text']) ? $settings[$prefix . 'get_in_touch_text'] : 'Can’t find what you need?';
$siteGetInTouchUrl = isset($settings[$prefix . 'get_in_touch_url']) ? $settings[$prefix . 'get_in_touch_url'] : '/contact/';

// --- Block attributes and module meta ---
$block_attributes = get_query_var('attributes');
$block_content = get_query_var('content');
$numberPosts = isset($block_attributes['numberPosts']) ? $block_attributes['numberPosts'] : '6';
$sortBy = isset($_GET['orderby']) ? $_GET['orderby'] : 'mostrecent';
$filterBy = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['q']) ? $_GET['q'] : '';
//$filterCat = get_term_by('slug', $filterBy, 'category');
$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
$moduleMeta = get_current_module_meta($current_module_id_global);

$moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '';
$child_pages = get_module_child_pages_using_module_id($current_module_id_global);
$imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '';
$attachmentIdMob = (array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : $imageId;
$args = array('module_colour' => $moduleColour);
ob_start();

get_template_part('template-parts/svgs/_linkout', '', $args);
$linkout_svg = ob_get_clean();

ob_start();

get_template_part('template-parts/svgs/_screen', '', $args);
$resource_svg = ob_get_clean();
?>

<section class="search-results full-width">
    <div class="search-results__switcher">
        <div class="news-tabs">
            <?php
            // Module switcher block
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
            ?></div>
    </div>
    <div class="search-results__top-container" style="background: <?php echo esc_html($moduleColour); ?>;">
        <div class="search-results__content">
            <div class="search-results__text">
                <div class="search-results__header">
                    <?php echo $block_content; ?>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
        <?php if ($imageId) : ?>
            <div class="search-results__image">
                <?php
                echo wp_get_attachment_image($imageId, 'imagetext', '', ["class" => "show-for-medium wp-image-$imageId"]);
                if ($attachmentIdMob) {
                    echo wp_get_attachment_image($attachmentIdMob, 'modulebanner', '', ["class" => "hide-for-medium wp-image-$attachmentIdMob"]);
                }
                ?>
                <?php set_query_var('module_color', $moduleMeta['module_color']);
                ?>
                <?php get_template_part('template-parts/svgs/_module-banner-mask-mobile'); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="search-results__box search-results__box--green hide-for-medium">
        <h3><?php echo $siteGetInTouchText; ?></h3>
        <a href="<?php echo esc_url($siteGetInTouchUrl); ?>" class="button-link button-link--rev">Contact us</a>
    </div>

    <div class="search-results__body">
        <!-- Filter and Sort UI -->
        <div class="search-results__header-filters">


            <!-- Posts List -->


            <?php
            // --- Gather posts attached to module child pages ---
            $postTypes = array('resource_page', 'document', 'page_link');
            if ($filterBy == 'infospacepage') {
                $postTypes = array(
                    'resource_page'
                );
            }
            if ($filterBy == 'externallink') {
                $postTypes = array(
                    'page_link'
                );
            }
            if ($filterBy == 'worddoc' || $filterBy == 'pdfdoc' || $filterBy == 'exceldoc' || $filterBy == 'pptdoc') {
                $postTypes = array(
                    'document'
                );
            }
            $loopArgs = array(
                'post_type' => $postTypes,
                'posts_per_page' => -1,
                'paged' => max(1, get_query_var('paged')),
                'ignore_sticky_posts' => 1,
                //'date_query' => array(
                //  'after' => '2025-11-01',
                // )
            );

            if ($filterBy == 'worddoc') {
                $loopArgs['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                        'key' => $prefix . 'document_files',
                        'value' => '.docx',
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => $prefix . 'document_files',
                        'value' => '.doc',
                        'compare' => 'LIKE'
                    )
                );
            }
            if ($filterBy == 'pdfdoc') {
                $loopArgs['meta_query'] = array(

                    array(
                        'key' => $prefix . 'document_files',
                        'value' => '.pdf',
                        'compare' => 'LIKE'
                    )
                );
            }
            if ($filterBy == 'exceldoc') {
                $loopArgs['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                        'key' => $prefix . 'document_files',
                        'value' => '.xlsx',
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => $prefix . 'document_files',
                        'value' => '.xls',
                        'compare' => 'LIKE'
                    )
                );
            }
            if ($filterBy == 'pptdoc') {
                $loopArgs['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                        'key' => $prefix . 'document_files',
                        'value' => '.pptx',
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => $prefix . 'document_files',
                        'value' => '.ppt',
                        'compare' => 'LIKE'
                    )
                );
            }


            // Search functionality
            if (!empty($search)) {
                $loopArgs['s'] = $search;
            }
            // GET POST IDS FOR NEWS
            $loop_for_resource = new WP_Query($loopArgs);
            $attached_to_page_ids = array();
            $matching_post_ids  = array(0);
            $featured_post = 0;

            // Build map of post IDs to attached resource pages for news items
            while ($loop_for_resource->have_posts()) : $loop_for_resource->the_post();
                $postId = get_the_ID();
                $post_meta = theme_get_meta($postId);
                $attached_to_pages = isset($post_meta->post_attached_resource_pages) ? $post_meta->post_attached_resource_pages : [];
                if (!empty($attached_to_pages)) {
                    $attached_to_page_ids[$postId] = $attached_to_pages;
                }
            endwhile;

            wp_reset_postdata();

            // Find posts matching current module's child pages
            if (!empty($child_pages)) {
                $matching_post_ids = [];

                foreach ($child_pages as $child_page_id) {
                    $resourceMeta = theme_get_meta($child_page_id);

                    //Add in news items
                    if (!empty($attached_to_page_ids)) {
                        foreach ($attached_to_page_ids as $post_id => $attached_pages) {
                            if (in_array($child_page_id, $attached_pages)) {
                                $matching_post_ids[] = $post_id;
                            }
                        }
                    }


                    // Add in links
                    $attachedLinkId = isset($resourceMeta->resource_attached_links) ? $resourceMeta->resource_attached_links : '';

                    if ($attachedLinkId) {
                        if (is_array($attachedLinkId)) {
                            $matching_post_ids = array_merge($matching_post_ids, $attachedLinkId);
                        } else {
                            $matching_post_ids[] = $attachedLinkId;
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




            // --- Main posts query and output ---
            if (!empty($matching_post_ids)) {
                $loopArgs['post__in'] = $matching_post_ids;
                $loopArgs['posts_per_page'] =  $numberPosts;

                //if (isset($filterCat->term_id)) {
                //$loopArgs['category__in'] = $filterCat->term_id;
                // }




                // Sorting logic
                if ($sortBy == 'mostrecent') {
                    $loopArgs['orderby'] = array('date' => 'DESC');
                } elseif ($sortBy == 'oldest') {
                    $loopArgs['orderby'] = array('date' => 'ASC');
                    //} //elseif ($sortBy == 'featured') {
                    // $loopArgs['ignore_sticky_posts'] = 0;
                    // $loopArgs['orderby'] = array('date' => 'DESC');
                } elseif ($sortBy == 'name') {
                    $loopArgs['orderby'] = array('title' => 'ASC');
                } elseif ($sortBy == 'namereversed') {
                    $loopArgs['orderby'] = array('title' => 'DESC');
                }

                $loopArgs['paged'] = get_query_var('paged') ? get_query_var('paged') : 1;
                $loop = new WP_Query($loopArgs);



                $total_posts_before_featured_removed = $loop->found_posts;



            ?> <p class="search-results__number">Showing <?php echo $total_posts_before_featured_removed; ?> results for '<?php echo esc_html($search); ?>'</p>
                <div id="list-top" class="listing-filter">
                    <div class="filter-list">
                        <button class="filter-list__filter-heading">Filter</button>
                        <div class="filter-list__nav-filter">
                            <ul>
                                <li>
                                    <?php $linkClass = ($filterBy == '') ? ' selected' : ''; ?>
                                    <a href="<?php echo esc_url(build_filter_url('all', $sortBy, $search)); ?>" class='tick-button<?php echo $linkClass; ?>'>All</a>
                                </li>
                                <?php
                                $filter_options = [

                                    'externallink' => 'External link',
                                    'infospacepage' => 'Infospace page',
                                    'worddoc' => 'Word document',
                                    'pdfdoc' => 'PDF document',
                                    'exceldoc' => 'Excel document',
                                    'pptdoc' => 'Powerpoint document',
                                    //'featured' => 'Featured'
                                ];
                                foreach ($filter_options as $slug => $name) :

                                    $linkClass = ($filterBy == $slug) ? ' selected' : '';
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url(build_filter_url($slug, $sortBy, $search)); ?>" class='tick-button<?php echo $linkClass; ?>'>
                                            <?php echo esc_html($name); ?>
                                        </a>
                                    </li>
                                <?php
                                endforeach; ?>
                            </ul>

                        </div>
                    </div>
                    <div class="filter-list">
                        <button class="filter-list__filter-heading" style="background: <?php echo esc_html($moduleColour); ?>;">Sort by</button>
                        <div class="filter-list__nav-filter" style="background: <?php echo esc_html($moduleColour); ?>;">
                            <ul>
                                <?php
                                $sort_options = [

                                    'mostrecent' => 'Time added (Most recent)',
                                    'oldest' => 'Time added (Least recent)',
                                    'name' => 'Alphabetical (A-Z)',
                                    'namereversed' => 'Alphabetical (Z-A)',
                                    //'featured' => 'Featured'
                                ];
                                foreach ($sort_options as $key => $label) {
                                    $linkClass = ($sortBy == $key) ? ' selected' : '';
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url(build_filter_url($filterBy, $key, $search)); ?>" class='tick-button<?php echo $linkClass; ?>'>
                                            <?php echo esc_html($label); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="search-results__key-mobile hide-for-medium">
                    <?php get_template_part('template-parts/key', '', array('module_colour' => $moduleColour, 'linkout_svg' => $linkout_svg, 'resource_svg' => $resource_svg)); ?>
                </div>
        </div>
        <div class="search-results__container">
            <div class="search-results__grid">
                <div class="search-results__col1">
                    <?php
                    // Output posts: featured and regular
                    $total_posts = $loop->found_posts;
                    $topnewsCounter = 0;



                    while ($loop->have_posts()) : $loop->the_post();
                        $result_Id = get_the_ID();
                        $post_type = get_post_type($result_Id);
                        $resultIcon = $post_type == 'resource_page' ? $resource_svg : $linkout_svg;
                    ?>
                        <?php //var_dump($post_type);
                        if ($post_type == 'document') {  ?>
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
                                $doc_summary = get_post_meta($result_Id, $prefix . 'summary', true);
                                $file_id = isset($doc['theme_fieldsdoc_uploaded_file_id']) ? $doc['theme_fieldsdoc_uploaded_file_id'] : null;
                                $upload_date = $file_id ? get_the_date('jS F, Y', $file_id) : null;

                                $doc_file_id = $doc['theme_fieldsdoc_uploaded_file_id'];
                                $filename = $doc["theme_fieldsdoc_uploaded_file"];

                                $file_svg = get_file_svg_from_filename($filename, $moduleColour);
                                $doc_url = '/download-document/' . $doc_file_id;
                            ?>
                                <div class="search-results__item">
                                    <div class="search-results__icon">
                                        <a href="<?php echo $doc_url ?>">
                                            <?php echo $file_svg; ?>
                                        </a>
                                    </div>
                                    <div class="search-excerpt">
                                        <h3><a href="<?php echo $doc_url ?>"><?php the_title(); ?></a></h3>

                                        <?php if ($upload_date) { ?>
                                            <p class="search-results__date">Uploaded <?php echo esc_html($upload_date); ?></p>
                                        <?php } ?>
                                        <p><a href="<?php echo $doc_url ?>"><?php echo $doc_summary ?></a></p>

                                    </div>
                                </div>
                            <?php
                            }
                        } else { ?>
                            <?php $upload_date = $result_Id ? get_the_date('jS F, Y', $result_Id) : null; ?>
                            <?php $resultSummary = get_post_meta($result_Id, $prefix . 'page_link_summary', true);
                            if (empty($resultSummary)) {
                                $resultSummary = get_the_excerpt();
                            } ?>
                            <?php
                            $is_external_link = $post_type === 'page_link';
                            $link_url = $is_external_link ? get_post_meta($result_Id, $prefix . 'page_link_url', true) : '';
                            $permalink = (!empty($link_url)) ? $link_url : get_permalink($result_Id);
                            $target = (!empty($link_url)) ? '_blank' : '_self';
                            ?>
                            <div class="search-results__item">
                                <div class="search-results__icon">
                                    <a href="<?php echo esc_url($permalink); ?>" target="<?php echo esc_attr($target ?? '_self'); ?>">
                                        <?php echo $resultIcon; ?>
                                    </a>
                                </div>
                                <div class="search-excerpt">
                                    <h3><a href="<?php echo esc_url($permalink); ?>" target="<?php echo esc_attr($target ?? '_self'); ?>"><?php the_title(); ?></a></h3>
                                    <?php if ($upload_date) { ?>
                                        <p class="search-results__date">Added <?php echo esc_html($upload_date); ?></p>
                                    <?php } ?>
                                    <p><a href="<?php echo esc_url($permalink); ?>" target="<?php echo esc_attr($target ?? '_self'); ?>"><?php echo  $resultSummary; ?></a></p>
                                </div>
                            </div>
                        <?php } ?>
                    <?php endwhile;

                    // Store the loop for pagination before resetting
                    $pagination_query = $loop;
                    wp_reset_postdata();
                    ?>

                <?php // .resource-page__col1

                echo '<div class="search-results__footer">'; // .search-results__container

                // Pagination
                if (function_exists('startertheme_pagination')) {
                    global $wp_query;
                    $temp_query = $wp_query;
                    $wp_query = $pagination_query;
                    startertheme_pagination();
                    $wp_query = $temp_query;
                }
                echo '</div>';
            } else {
                echo '<p>No posts found.</p>';
            }
                ?>
                </div>
                <div class="search-results__col2">
                    <div class="search-results__box show-for-medium">
                        <?php get_template_part('template-parts/key', '', array('module_colour' => $moduleColour, 'linkout_svg' => $linkout_svg, 'resource_svg' => $resource_svg)); ?>
                    </div>
                    <div class="search-results__box search-results__box--green show-for-medium">
                        <h3><?php echo $siteGetInTouchText; ?></h3>
                        <a href="<?php echo esc_url($siteGetInTouchUrl); ?>" class="button-link button-link--rev">Contact us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>