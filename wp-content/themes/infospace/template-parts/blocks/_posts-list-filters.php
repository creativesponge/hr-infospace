<?php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
global $prefix;
// --- Block attributes and module meta ---
$block_attributes = get_query_var('attributes');
$block_content = get_query_var('content');
$numberPosts = isset($block_attributes['numberPosts']) ? $block_attributes['numberPosts'] : '6';
$sortBy = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'mostrecent';
$filterBy = isset($_GET['filter']) ? sanitize_text_field($_GET['filter']) : '';
$search = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
//$filterCat = get_term_by('slug', $filterBy, 'category');
$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
$moduleMeta = get_current_module_meta($current_module_id_global);

// Ensure $moduleMeta is an array
if (!is_array($moduleMeta)) {
    $moduleMeta = array();
}

$moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '';
$child_pages = get_module_child_pages_using_module_id($current_module_id_global);
$imageId = (is_array($block_attributes) && array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '1781';
$attachmentIdMob = (is_array($block_attributes) && array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : $imageId;

?>
<section class="posts-list-filters full-width">
    <?php if (is_user_logged_in()) : ?>
        <div class="posts-list-filters__switcher">
            <div class="news-tabs">
                <?php
                // Module switcher block
               /* get_template_part(
                    'template-parts/module-switcher',
                    null,
                    array(
                        'module_id' => $moduleMeta['module_id'] ?? null,
                        'post_id' => $moduleMeta['attached_resources'] ?? null,
                        'attached_resources' => $moduleMeta['attached_resources'] ?? null,
                        'module_colour' => $moduleMeta['module_color'] ?? null,
                    )
                );*/
                
                ?><br></br></br>
            </div>
        </div>
    <?php endif; ?>
    <div class="posts-list-filters__top-container" style="background: <?php echo esc_html($moduleColour); ?>;">
        <div class="posts-list-filters__content">
            <div class="posts-list-filters__text">
                <div class="posts-list-filters__header">
                    <?php echo $block_content; ?>
                </div>
            </div>
        </div>
        <?php if ($imageId) : ?>
            <div class="posts-list-filters__image">
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

    <div class="posts-list-filters__body">
        <!-- Filter and Sort UI -->
        <div class="posts-list-filters__header-filters">
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

                                'lastseven' => 'Last 7 days',
                                'thismonth' => 'This month',
                                'threemonths' => 'Last 3 months',
                                'thisyear' => 'This year',
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
                        <p><strong>Enter keywords</strong><br />(Separate with a comma)</p>
                        <form method="GET" action="<?php echo esc_url(get_permalink()); ?>">
                            <input type="text" name="q" value="<?php echo isset($_GET['q']) ? esc_attr($_GET['q']) : ''; ?>" placeholder="Search posts..." />
                            <input type="hidden" name="orderby" value="<?php echo esc_attr($sortBy); ?>" />
                            <input type="hidden" name="filter" value="<?php echo esc_attr($filterBy); ?>" />
                            <button type="submit" class="button--search"><span class="show-for-sr">Search</span><?php get_template_part(
                                                                                                                    'template-parts/svgs/_magnifying-glass',
                                                                                                                    null,
                                                                                                                    []
                                                                                                                ); ?></button>
                        </form>
                    </div>
                </div>
                <div class="filter-list">
                    <button class="filter-list__filter-heading" style="background: <?php echo esc_html($moduleColour); ?>;">Sort by</button>
                    <div class="filter-list__nav-filter" style="background: <?php echo esc_html($moduleColour); ?>;">
                        <ul>
                            <?php
                            $sort_options = [

                                'mostrecent' => 'Most recent',
                                'oldest' => 'Oldest',
                                'name' => 'A-Z',
                                'namereversed' => 'Z-A',
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

            <!-- Posts List -->


            <?php
            // --- Gather posts attached to module child pages ---
            $loopArgs = array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'paged' => max(1, get_query_var('paged')),
                'ignore_sticky_posts' => 1,
                //'date_query' => array(
                //  'after' => '2025-11-01',
                // )
            );

            // Add meta query to exclude posts with start/end date restrictions
            $current_date = current_time('timestamp');
            $meta_query = array(
                'relation' => 'AND',
                // Exclude posts with start date in the future
                array(
                    'relation' => 'OR',
                    array(
                        'key' => $prefix . 'post_start_date',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => $prefix . 'post_start_date',
                        'value' => '',
                        'compare' => '='
                    ),
                    array(
                        'key' => $prefix . 'post_start_date',
                        'value' => 0,
                        'compare' => '='
                    ),
                    array(
                        'key' => $prefix . 'post_start_date',
                        'value' => $current_date,
                        'compare' => '<=',
                        'type' => 'NUMERIC'
                    )
                ),
                // Exclude posts with end date in the past
                array(
                    'relation' => 'OR',
                    array(
                        'key' => $prefix . 'post_end_date',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => $prefix . 'post_end_date',
                        'value' => '',
                        'compare' => '='
                    ),
                    array(
                        'key' => $prefix . 'post_end_date',
                        'value' => 0,
                        'compare' => '='
                    ),
                    array(
                        'key' => $prefix . 'post_end_date',
                        'value' => $current_date,
                        'compare' => '>=',
                        'type' => 'NUMERIC'
                    )
                )
            );

            $loopArgs['meta_query'] = $meta_query;

            // Search functionality
            if (!empty($search)) {
                $loopArgs['s'] = $search;
            }


            // Date filtering logic
            $date_query = array();
            if ($filterBy == 'lastseven') {
                $date_query[] = array(
                    'after' => date('Y-m-d', strtotime('-7 days'))
                );
            } elseif ($filterBy == 'thismonth') {
                $lastDDayOfLastMonth = (new DateTime('last day of last month'))->format('Y-m-d');
                $date_query[] = array(
                    'after' => date($lastDDayOfLastMonth)
                );
            } elseif ($filterBy == 'threemonths') {
                $date_query[] = array(
                    'after' => date('Y-m-d', strtotime('-3 months')),
                );
            } elseif ($filterBy == 'thisyear') {
                $date_query[] = array(
                    'after' => date('Y-01-01'),
                );
            }
            if (!empty($date_query)) {
                $loopArgs['date_query'] = $date_query;
            }

            $loop_for_resource = new WP_Query($loopArgs);
            relevanssi_do_query($loop_for_resource);
            $attached_to_page_ids = array();
            $matching_post_ids  = array(0);
            $featured_post = 0;

            // Build map of post IDs to attached resource pages
            while ($loop_for_resource->have_posts()) : $loop_for_resource->the_post();
                $postId = get_the_ID();
                $news_meta = theme_get_meta($postId);
                $attached_to_pages = isset($news_meta->post_attached_resource_pages) ? $news_meta->post_attached_resource_pages : [];
                if (!empty($attached_to_pages)) {
                    $attached_to_page_ids[$postId] = $attached_to_pages;
                }
            endwhile;



            // Find posts matching current module's child pages
            if (!empty($child_pages) && !empty($attached_to_page_ids)) {
                $matching_post_ids = [];
                foreach ($child_pages as $child_page_id) {
                    foreach ($attached_to_page_ids as $post_id => $attached_pages) {
                        if (in_array($child_page_id, $attached_pages)) {
                            $matching_post_ids[] = $post_id;
                        }
                    }
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
                relevanssi_do_query($loop);
                // Find and remove featured post from loop
                if ($loop->have_posts()) {
                    $loop->the_post();
                    $featured_post = get_the_ID();
                    $loop->rewind_posts();
                }
                $total_posts_before_featured_removed = $loop->found_posts;
                while ($loop->have_posts()) : $loop->the_post();
                    $postId = get_the_ID();
                    $news_meta = theme_get_meta($postId);
                    if (isset($news_meta->post_featured) && $news_meta->post_featured == 'on') {
                        $featured_post = $postId;
                        $loop->posts = array_values(array_filter($loop->posts, function ($post) use ($postId) {
                            return $post->ID != $postId;
                        }));
                        $loop->post_count = count($loop->posts);
                        $loop->found_posts = $loop->post_count;
                        $loop->rewind_posts();
                        break;
                    }
                endwhile;
                $loop->rewind_posts();
                $postCount = $loop->post_count;
                wp_reset_postdata();

                // If no featured post, use first post as featured
                if ($featured_post == 0 && $loop->have_posts() && $loopArgs['paged'] == 1) {
                    $loop->the_post();
                    $featured_post = get_the_ID();
                    $loop->rewind_posts();
                }

                $search_term_text = !empty($search) ? ' for "' . esc_html($search) . '"' : '';
                $search_stories_text = $total_posts_before_featured_removed > 1 ? ' stories found' : ' story found';


            ?> <p class="posts-list-filters__number"><?php echo $total_posts_before_featured_removed; ?> news <?php echo $search_stories_text . $search_term_text; ?></p>
        </div>
        <div class="posts-list-filters__container">
            <div class="grid-x">
            <?php




                // Output posts: featured and regular
                $total_posts = $loop->found_posts;
                $topnewsCounter = 0;

                // the loop won't happen if there is only a featured post

                if ($total_posts_before_featured_removed == 1 && $total_posts == 0) {
                    $args = array('fpost_id' => $featured_post, 'moduleColour' => $moduleColour);
                    get_template_part('template-parts/x-large-teaser', '', $args);
                }

                while ($loop->have_posts()) : $loop->the_post();
                    $postId = get_the_ID();

                    // If only one post, show as large teaser
                    if ($total_posts_before_featured_removed == 1) {
                        $args = array('fpost_id' => $featured_post, 'moduleColour' => $moduleColour);
                        get_template_part('template-parts/large-teaser', '', $args);
                    } else {
                        if ($loop->current_post == 0 && $loopArgs['paged'] == 1) {
                            // Featured post
                            $args = array('fpost_id' => $featured_post, 'moduleColour' => $moduleColour);
                            echo '<div class="cell small-12 grid-x">';
                            get_template_part('template-parts/x-large-teaser', '', $args);
                            echo '<div class="cell medium-4">';
                            if ($postId && $postId != $featured_post) {
                                $args = array('fpost_id' => $featured_post, 'moduleColour' => $moduleColour);
                                get_template_part('template-parts/large-teaser', '', $args);
                                $topnewsCounter++;
                            }
                        } elseif ($postId) {
                            get_template_part('template-parts/large-teaser', '', $args);
                            $topnewsCounter++;
                        }

                        // Close divs after 2 posts or if only 1 post
                        if (($topnewsCounter == 2 && $total_posts_before_featured_removed >= 2 && $loopArgs['paged'] == 1) || ($topnewsCounter == 1 && $total_posts_before_featured_removed == 1 && $loopArgs['paged'] == 1) || ($topnewsCounter == 1 && $total_posts_before_featured_removed == 2 && $loopArgs['paged'] == 1)) {
                            echo '</div>';
                            //echo 'closed';
                            echo '</div>';
                        }
                        // VAR_DUMP($total_posts_before_featured_removed);
                    }
                endwhile;

                // Store the loop for pagination before resetting
                $pagination_query = $loop;
                wp_reset_postdata();

                echo '</div>'; // .grid-x
                echo '<div class="posts-list-filters__footer">'; // .posts-list-filters__container
                //if (!empty($moduleMeta['attached_resources'])) {
                    //echo '<a href="' . get_permalink($moduleMeta['attached_resources']) . '" class="button-posts-list-filters__footer" style="background: ' . esc_html($moduleColour) . ';">Back<span class="show-for-medium"> to ' . get_the_title($moduleMeta['attached_resources']) . '<span> </a>';
               // }
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
        </div>
</section>