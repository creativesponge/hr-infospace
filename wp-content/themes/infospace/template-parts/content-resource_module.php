<?php

/**
 * The default template for displaying resource pages
 *
 *
 */
global $prefix;
global $namespace;
$user = wp_get_current_user();
$post_id = get_the_ID();
$resourceMeta = theme_get_meta($post_id);

global $settings;


$moduleMeta = get_current_module_meta(null);
$child_pages = get_module_child_pages($post_id);
$child_pages[] = $post_id;
$policiesTabText = $moduleMeta['module_id'] == 1738 ? 'Compliance code' : 'Policies';
$documentsTabText = $moduleMeta['module_id'] == 1738 ? 'Guidance & Forms' : 'Documents';

// updates file
$updatesLog = isset($settings[$prefix . 'updates_log_file']) ? $settings[$prefix . 'updates_log_file'] : '';
$updatesLogDate = isset($settings[$prefix . 'updates_log_date']) ? $settings[$prefix . 'updates_log_date'] : '';
if (!empty($updatesLogDate) && !is_numeric($updatesLogDate)) {
	$updatesLogDate = strtotime($updatesLogDate);
}
$updatesLogDate = date('j F Y', $updatesLogDate);

//$filename = $newsletter_meta->newsletter_file;
$log_doc_id = $settings[$prefix . 'updates_log_file_id'];
$log_file_svg = get_file_svg_from_filename($updatesLog);
$log_url = '/download-document/' . $log_doc_id;

$immediate_child_pages = get_children(array(
	'post_parent' => $post_id,
	'post_type'   => 'resource_page',
	'post_status' => 'publish',
	'fields'      => 'ids',
	'orderby'     => 'menu_order',
	'order'       => 'ASC',
));
ob_start();
echo '<button class="resource-page__favourite-icon">';
get_template_part('template-parts/svgs/_favourite');
echo '</button>';
$favourite_svg = ob_get_clean();

ob_start();
get_template_part('template-parts/svgs/_linkout');
$linkout_svg = ob_get_clean();

ob_start();
get_template_part('template-parts/svgs/_screen');
$screen_svg = ob_get_clean();

//ob_start();
//get_template_part('template-parts/svgs/_word-doc');
// = ob_get_clean();

ob_start();
get_template_part('template-parts/svgs/_download-icon');
$download_svg = ob_get_clean();
?>

<article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>

	<div class="entry-content resource-module">
		<?php if (is_user_logged_in()) : ?>
		<div class="module-tabs">
			<?php get_template_part(
				'template-parts/module-switcher',
				null,
				array(
					'module_id' => $moduleMeta['module_id'] ?? null,
					'post_id' => $post_id,
					'attached_resources' => $moduleMeta['attached_resources'] ?? null,
					'module_colour' => $moduleMeta['module_color'] ?? null,
				)
			); ?>
		</div>
		<?php endif; ?>
		<header class="resource-module__header full-width" style="background-color: <?php echo esc_html($moduleMeta['module_color']); ?>">
			<?php if (!empty($moduleMeta['module_banner'])) : ?>
				<div class="resource-module__banner">
					<?php echo wp_get_attachment_image($moduleMeta['module_banner'], 'modulebanner'); ?>
					<?php set_query_var('module_color', $moduleMeta['module_color']);
					?>
					<?php get_template_part('template-parts/svgs/_module-banner-mask-mobile'); ?>
					<?php get_template_part(
						'template-parts/svgs/_banner-mask',
						null,
						array(

							'module_colour' => $moduleMeta['module_color'] ?? null,
						)
					); ?>
				</div>

			<?php endif; ?>
			<div class="resource-module__header-inner">
				<div class="resource-module__header-content">
					<h1 class="entry-title">Welcome to <span><?php the_title(); ?></span></h1>
					<?php
					$args = ['show-module' => true];
					get_search_form($args);
					?>


				</div>


			</div>

		</header>
		<?php
		//var_dump($moduleMeta["attached_resources"]);
		if (user_has_access($post_id) || user_has_module_access($moduleMeta["attached_resources"])) { ?>
			<?php echo '<div class="resource-module__news full-width">'; ?>
			<?php echo '<div class="resource-module__news-container">'; ?>
			<?php
			//the_content();
			?>
			<?php

			$accessible_pages = return_users_pages_with_access();

			// Filter children to only include accessible pages
			if (!empty($accessible_pages) && !current_user_can('administrator')) {
				$immediate_child_pages = array_intersect($immediate_child_pages, $accessible_pages);
			}

			//Show sub pages
			if (!empty($immediate_child_pages)) {

				echo '<div class="quick-links">';
				echo '<button class="quick-links__toggle" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">Quick links</button>';
				echo '<ul style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
				foreach ($immediate_child_pages as $child_id) {
					echo '<li><a href="' . get_permalink($child_id) . '">' . get_the_title($child_id) . '</a></li>';
				}
				echo '</ul>';
				echo '</div>';
			}
			?>

			<?php // Show recent news posts attached to this resource module
			$news_args = array(
				'post_type'      => 'post',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
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

            $news_args['meta_query'] = $meta_query;

			// Get the posts for this module to check their attached resource pages
			$loop_for_resource = new WP_Query($news_args);
			$newsCount = 0;
			$attached_to_page_ids = array();
			$matching_post_ids  = array(0);
			$featured_post = 0;

			// filter the posts based on their attached resource pages
			while ($loop_for_resource->have_posts()) : $loop_for_resource->the_post();
				$postId = get_the_ID();
				$news_meta = theme_get_meta($postId);
				$attached_to_pages = isset($news_meta->post_attached_resource_pages) ? $news_meta->post_attached_resource_pages : [];

				if (!empty($attached_to_pages)) {
					$attached_to_page_ids[$postId] = $attached_to_pages;
				}
			endwhile;

			if (!empty($child_pages) && !empty($attached_to_page_ids)) {
				$matching_post_ids = [];
				foreach ($child_pages as $child_page_id) {
					foreach ($attached_to_page_ids as $attached_post_id => $attached_pages) {
						if (in_array($child_page_id, $attached_pages)) {
							$matching_post_ids[] = $attached_post_id;
						}
					}
				}
				// Remove duplicates if any
				$matching_post_ids = array_unique($matching_post_ids);
				// Example: output matching post IDs

			}
			//echo '<pre>';
			//print_r($child_pages);
			//echo '</pre>';

			//echo "<pre>";
			//var_dump($attached_to_page_ids);
			//echo "</pre>";

			if (!empty($matching_post_ids)) {
				// loop for news

				$args['post__in'] = $matching_post_ids;
				//$args['posts_per_page'] =  $numberPosts;
				$news_query = new WP_Query($args);


				// Get the ID of the first item in $loop_for_resource and set as featured

				if ($news_query->have_posts()) {
					$news_query->the_post();
					$featured_post = get_the_ID();

					// Rewind posts so the main loop works as expected
					$news_query->rewind_posts();
				}

				// Check for a featured post

				while ($news_query->have_posts()) : $news_query->the_post();
					// check if it is featured
					$postId = get_the_ID();
					$news_meta = theme_get_meta($postId);
					if (isset($news_meta->post_featured) && $news_meta->post_featured == 'on') {
						// add to featured post
						$featured_post = $postId;
						break;
					}
				endwhile;
				$news_query->rewind_posts();

				if ($news_query->have_posts()) {


					echo '<div class="module-panel">';
					echo '<div class="module-panel__header module-panel__header--news">';
					echo '<h2 style="color: ' . esc_html($moduleMeta['module_color']) . '">Latest news</h2><a href="/news" class="button-link">View all</a>';
					echo '</div>';

					if ($featured_post && !empty($featured_post)) {
						echo '<div class="module-panel__content">';
						$featuredMeta = theme_get_meta($featured_post);
						$featuredSummary = isset($featuredMeta->post_summary) ? $featuredMeta->post_summary : get_the_excerpt($featured_post);
						$featuredUpdatedDate = date('j F Y', strtotime(get_the_date('Y-m-d', $featured_post)));
						$featuredPostImage = get_the_post_thumbnail($featured_post, 'featurednews') != '' ? get_the_post_thumbnail($featured_post, 'featurednews') : wp_get_attachment_image(1781, 'featurednews');

						echo '<div class="grid-x module-panel__news-grid">';
						echo '<div class="cell 12 medium-6 module-panel__news-grid-featured-post">';
						//if (has_post_thumbnail($featured_post)) {
						echo '<div class="module-panel__thumbnail">';
						echo '<div class="module-panel__thumbnail-label" style="color: ' . esc_html($moduleMeta['module_color']) . '">Featured story</div>';
						echo $featuredPostImage;
						echo '</div>';
						//}
						echo '<p class="newsletter-date">' . $featuredUpdatedDate . '</p>';
						echo '<h3 style="color: ' . esc_html($moduleMeta['module_color']) . '">' . get_the_title($featured_post) . '</h3>';
						echo '<p>' . $featuredSummary . '</p>';
						echo '<a href="' . get_the_permalink($featured_post)  . '" rel="nofollow" class="arrow-link" style="color: ' . esc_html($moduleMeta['module_color']) . '">read</a>';
						echo '</div>'; // <-- Close featured post column
					}

					echo '<div class="cell small-12 medium-6 mobile-only-carousel">';
					while ($news_query->have_posts()) {
						$news_query->the_post();
						$newsID = get_the_ID();

						$news_meta = theme_get_meta($newsID);
						$updatedDate = get_the_date('j F Y', $newsID);
						$attached_post_resources = isset($news_meta->post_attached_resource_pages) ? $news_meta->post_attached_resource_pages : '';
						$postStartDate = isset($news_meta->post_start_date) ? $news_meta->post_start_date : '';
						$postEndDate = isset($news_meta->post_end_date) ? $news_meta->post_end_date : '';
						if (!empty($postEndDate) && ($postEndDate < time())) {
							continue;
						}
						// Check start date
						if (!empty($postStartDate) && ($postStartDate > time())) {
							continue;
						}
						if (!empty($featured_post) && ($featured_post == $newsID)) {
							continue;
						}
						if (
							!empty($newsID)
						) {



							echo '<div class="resource-module__news-teaser">';
							echo '<p class="news-date">' . $updatedDate . '</p>';
							echo '<h3 style="color: ' . esc_html($moduleMeta['module_color']) . '">' . get_the_title() . '</h3>';
							echo '<p class="news-excerpt">' . get_the_excerpt() . '</p>';
							echo '<a href="' . get_the_permalink()  . '" rel="nofollow" class="arrow-link" style="color: ' . esc_html($moduleMeta['module_color']) . '">read</a>';
							echo '</div>';

							$newsCount++;
							if ($newsCount >= 3) {
								break;
							}
						}
					}
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';

					wp_reset_postdata();
				}
			}
			?>
			<?php echo '</div>'; ?>
			<?php echo '</div>'; ?>

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
				$fileNewDate = isset($fav_doc_meta->is_new) ? $fav_doc_meta->is_new : '';
			$fileUpdatedDate = isset($fav_doc_meta->is_updated) ? $fav_doc_meta->is_updated : '';
			if ($fileNewDate && $fileNewDate > time() - (30 * DAY_IN_SECONDS)) {
				$fileDateLabel = ' <strong><i class="document-label document-label--new" aria-label="New document">New</i></strong>';
			} elseif ($fileUpdatedDate && $fileUpdatedDate > time() - (30 * DAY_IN_SECONDS)) {
				$fileDateLabel = ' <strong><i class="document-label document-label--updated" aria-label="Updated document">Updated</i></strong>';
			} else {
				$fileDateLabel = '';
			}
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
							foreach ($attached_fav_doc_array as $file_data) {
								if (!empty($file_data['theme_fieldsdoc_uploaded_file_id'])) {
									$fav_doc_id = $file_data['theme_fieldsdoc_uploaded_file_id'];
									$filename = $file_data["theme_fieldsdoc_uploaded_file"];
									$file_svg = get_file_svg_from_filename($filename);
									$file_title = get_the_title($doc);
									$doc_url = '/download-document/' . $fav_doc_id;

									// Check the start and end dates
									$now = time();
									$start_date = isset($file_data[$prefix . 'start_date']) ?  $file_data[$prefix . 'start_date'] : null;
									$end_date = isset($file_data[$prefix . 'end_date']) ? $file_data[$prefix . 'end_date'] : null;
									if (($start_date && $now < $start_date) || ($end_date && $now > $end_date)) {
										// Skip this file as it is not currently active
										continue;
									}

									// Store the HTML output for later use
									$fav_doc_html .= '<li><a href="' . esc_url($doc_url) . '" data-download-name="' . esc_html($file_title) . '" data-download-id="' . esc_attr($doc) . '" rel="nofollow"><span>' . $file_svg . $file_title . '</span>'.$fileDateLabel . '</a></li>';
								}
							}}
					}
					$fav_doc_html .= '</ul>';
				}


				// Favourite Resources
				// loop through resources and get the links
				$attached_resources_list = array_unique($attached_resources_list);
				if (!empty($attached_resources_list)) {
					$fav_res_html .=  '<ul>';
					foreach ($attached_resources_list as $res) {
						$fav_res_html .= '<li><a href="' . esc_url(get_permalink($res)) . '" rel="nofollow"><span>' . $linkout_svg . get_the_title($res) . '</span></a></li>';
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
						$linkActive = isset($link_meta->page_link_is_active) ? $link_meta->page_link_is_active : 'off';
					
						$link_url = isset($link_meta->page_link_url) ? $link_meta->page_link_url : '';
						if (empty($link_url ) || $linkActive == 'off') {
							continue;
						}
						$fav_link_html .= '<li><a href="' . esc_url($link_url) . '" rel="nofollow"><span>' . $linkout_svg . esc_html(get_the_title($link)) . '</span></a></li>';
					}
					$fav_link_html .= '</ul>';
				}


				// Output results
				if (
					(!empty($fav_doc_html) && $fav_doc_html != '<ul></ul>') ||
					(!empty($fav_res_html) && $fav_res_html != '<ul></ul>') ||
					(!empty($fav_link_html) && $fav_link_html != '<ul></ul>')
				) {
					echo '<div class="resource-module__favourites">';
					echo '<div class="module-panel module-panel--favourites">';
					echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
					echo '<div>';
					get_template_part(
						'template-parts/svgs/_favourite'
					);
					echo '<h2>My favourites</h2>';
					echo '</div>';
					echo '<div><a href="' . get_permalink(1911) . '"><span>EDIT FAVOURITES</span></a>';
					get_template_part(
						'template-parts/svgs/_cog-icon',
					);
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
				} else {
					// No favourites
					echo '<div class="resource-module__favourites resource-module__favourites--empty">';
					echo '<div class="module-panel module-panel--favourites">';
					echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
					echo '<div>';
					get_template_part(
						'template-parts/svgs/_favourite'
					);
					echo '<h2>My favourites</h2>';
					echo '</div>';
					echo '</div>';
					echo '<div class="module-panel__content tabbed-content">';
					echo '<p>You have not added any favourites yet. To add a favourite, click the ' . $favourite_svg . ' icon next to a document, link, or resource page.</p>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
				wp_reset_postdata();
			} else {


				// No favourites
				echo '<div class="resource-module__favourites resource-module__favourites--empty">';
				echo '<div class="module-panel module-panel--favourites">';
				echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
				echo '<div>';
				get_template_part(
					'template-parts/svgs/_favourite'
				);
				echo '<h2>My favourites</h2>';
				echo '</div>';
				echo '</div>';
				echo '<div class="module-panel__content tabbed-content">';
				echo '<p>You have not added any favourites yet. To add a favourite, click the ' . $favourite_svg . ' icon next to a document, link, or resource page.</p>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';
			?>

			<?php echo '<div class="resource-module__grid full-width">'; ?>
			<?php echo '<div class="resource-module__container">'; ?>
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

				while ($newsletter_query->have_posts()) {
					$newsletter_query->the_post();
					$newsletterID = get_the_ID();
					$newsletter_meta = theme_get_meta($newsletterID);
					$updatedDate = isset($newsletter_meta->newsletter_date) && !empty($newsletter_meta->newsletter_date)
						? date('j F Y', $newsletter_meta->newsletter_date)
						: get_the_date('j F Y', $newsletterID);
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
					$newsletterTitle = get_the_title($newsletterID);
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
						echo '<div class="module-panel module-panel--newsletter">';

						echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
						echo '<h2>Latest Newsletter</h2>';
						echo '</div>';

						echo '<div class="module-panel__content">';
						echo '<div class="module-panel__content-grid" style="border-color: ' . esc_html($moduleMeta['module_color']) . ';">';
						echo '<div class="module-panel__content-top">';
						echo '<h3>' . $file_svg . '<span>' . get_the_title() . '</span></h3>';
						echo '<p class="newsletter-date hide-for-large">Updated on: ' . $updatedDate . '</p>';
						echo '</div>';
						if ($attached_documents_link) {
							echo '<a href="' . esc_url($attached_documents_link) . '" data-newsletter-name="' . esc_html($newsletterTitle) . '" data-newsletter-id="' . esc_attr($newsletterID) . '" rel="nofollow" class="download-link download-link--out show-for-large" target="_blank">View ' . $linkout_svg . '</a>';
						} else {
							echo '<a href="' . esc_url($doc_url) . '"  data-newsletter-name="' . esc_html($newsletterTitle) . '" data-newsletter-id="' . esc_attr($newsletterID) . '" rel="nofollow" class="download-link show-for-large">Download ' . $download_svg . '</a>';
						}

						echo '</div>';
						echo '<p class="newsletter-date show-for-large">Updated on: ' . $updatedDate . '</p>';

						echo '<div class="module-panel__content-bottom">';
						if ($attached_documents_link) {
							echo '<a href="' . esc_url($attached_documents_link) . '" data-newsletter-name="' . esc_html($newsletterTitle) . '" data-newsletter-id="' . esc_attr($newsletterID) . '" rel="nofollow" class="download-link download-link--out hide-for-large" target="_blank">View ' . $linkout_svg . '</a>';
						} else {
							echo '<a href="' . esc_url($doc_url) . '"  data-newsletter-name="' . esc_html($newsletterTitle) . '" data-newsletter-id="' . esc_attr($newsletterID) . '" rel="nofollow" class="download-link hide-for-large">Download ' . $download_svg . '</a>';
						}

						echo '<a href="' . get_permalink(1932) . '" rel="nofollow" class="arrow-link" style="color: ' . esc_html($moduleMeta['module_color']) . ';">View all</a>';
						echo '</div>';

						echo '</div>';

						echo '</div>';
						break;
					}
				}
				//echo '</div>';
				wp_reset_postdata();
			}
			?>

			<?php
			// Show the most recent 'updates log' custom post type
			global $hr_module_id;
			if (!empty($updatesLog)  && $moduleMeta['module_id'] == $hr_module_id) {
				echo '<div class="module-panel module-panel--log">';
				echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
				echo '<h2>Updates log</h2>';
				echo '</div>';
				echo '<div class="module-panel__content">';
				echo '<div class="module-panel__content-grid">';
				echo '<div class="module-panel__content-top">';
				echo '<h3>' . $log_file_svg . '<span>Read the latest update log</span></h3>';
				echo '<p class="newsletter-date hide-for-large">Updated on: ' . $updatesLogDate . '</p>';
				echo '</div>';
				echo '<a href="' . esc_url($log_url) . '" rel="nofollow" class="download-link show-for-large">Download ' . $download_svg . '</a>';
				echo '</div>';
				echo '<p class="newsletter-date show-for-large">Updated on: ' . $updatesLogDate . '</p>';

				echo '<div class="module-panel__content-bottom hide-for-large">';
				echo '<a href="' . esc_url($log_url) . '" rel="nofollow" class="download-link">Download ' . $download_svg . '</a>';
				echo '</div>';

				echo '</div>';
				echo '</div>';
			}
			?>

			<?php
			// Show the most recently updated docs
			$updated_args = array(
				'post_type'      => 'document',
				'posts_per_page' => -1,
				'post_status'    => 'publish',

				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key'     => $prefix . 'is_new',
						'value'   => strtotime('-1 months'),
						'compare' => '>=',
						'type'    => 'NUMERIC',
					),
					array(
						'key'     => $prefix . 'is_updated',
						'value'   => strtotime('-1 months'),
						'compare' => '>=',
						'type'    => 'NUMERIC',
					),
				),
				'orderby' => array(
					$prefix . 'is_new'     => 'DESC',
					$prefix . 'is_updated' => 'DESC',
					'date'                 => 'DESC',
				),



			);
			$updated_query = new WP_Query($updated_args);
			$updatedDocsList = "";
			$updatedDocsListPolicies = "";
			if ($updated_query->have_posts()) {
				
				$updatedDocsList = "<ul>";
				$updatedDocsListPolicies = "<ul>";

				while ($updated_query->have_posts()) {

					$updated_query->the_post();
					$docID = get_the_ID();

					// check if it is attached to a resource in this module
					$child_attached_doc_ids = array();

					foreach ($child_pages as $child_page_id) {
						
						if (user_has_access($child_page_id) === false) {
							continue;
						}


						$child_meta = theme_get_meta($child_page_id);

						$attached_docs = isset($child_meta->resource_attached_documents) ? $child_meta->resource_attached_documents : [];

						if (!empty($attached_docs)) {
							$child_attached_doc_ids = array_merge($child_attached_doc_ids, (array)$attached_docs);
						}
					}
					$child_attached_doc_ids = array_unique($child_attached_doc_ids);
				
					if (!in_array($docID, $child_attached_doc_ids)) {

						continue;
					}

					$updated_meta = theme_get_meta($docID);

					if (!empty($updated_meta->is_updated)) {
						$updatedDate = date('d/m/Y', $updated_meta->is_updated);
					} elseif (!empty($updated_meta->is_new)) {
						$updatedDate = date('d/m/Y', $updated_meta->is_new);
					} else {
						$updatedDate = get_the_date('F j, Y', $docID);
					}
					$startDate = isset($updated_meta->start_date) ? $updated_meta->start_date : '';
					$endDate = isset($updated_meta->end_date) ? $updated_meta->end_date : '';
					$attached_documents_list = isset($updated_meta->document_files) ? $updated_meta->document_files : '';
				
					if (!empty($endDate) && ($endDate < time())) {
						continue;
					}

					// Check start date
					if (!empty($startDate) && ($startDate > time())) {
						continue;
					}

					$doc_url = '';

					if (!empty($attached_documents_list[0]['theme_fieldsdoc_uploaded_file_id'])) {
						$docFileId = $attached_documents_list[0]['theme_fieldsdoc_uploaded_file_id'];
						$filename = $attached_documents_list[0]["theme_fieldsdoc_uploaded_file"];
						$file_svg = get_file_svg_from_filename($filename);
						$doc_url = '/download-document/' . $docFileId;
						$file_title = get_the_title($docID);
						if (has_term(3, 'doc_type', $docID)) {
							$updatedDocsListPolicies .= '<li><a href="' . esc_url($doc_url) . '" data-download-name="' . esc_html($file_title) . '" data-download-id="' . esc_attr($docID) . '" rel="nofollow"><span>' . $file_svg . $file_title . '</span><i>' . $updatedDate . '</i></a> </li>';
						} else {
							$updatedDocsList .= '<li><a href="' . esc_url($doc_url) . '" data-download-name="' . esc_html($file_title) . '" data-download-id="' . esc_attr($docID) . '" rel="nofollow"><span>' . $file_svg . $file_title . '</span><i>' . $updatedDate . '</i></a></li>';
						}
					}
				}
				$updatedDocsListPolicies .= "</ul>";
				$updatedDocsList .= "</ul>";
				//echo '</div>';

				wp_reset_postdata();
			}


			if ((!empty($updatedDocsListPolicies) && $updatedDocsListPolicies != '<ul></ul>') || (!empty($updatedDocsList) && $updatedDocsList != '<ul></ul>')) {
				echo '<div class="module-panel  module-panel--updated">';
				echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
				echo '<h2>Recently Added/Updated</h2>';
				echo '</div>';
				echo '<div class="module-panel__content tabbed-content">';

				echo '<ul class="tab-list tabbed-content__list" role="tablist"></ul>';
				echo '<p class="module-panel__updated">Updated on</p>';


				if (!empty($updatedDocsListPolicies) && $updatedDocsListPolicies != '<ul></ul>') {
					echo '<div class="tabbed-content__panel active">';
					echo '<h3 class="show-for-sr">' . $policiesTabText . '</h3>';
					echo $updatedDocsListPolicies;
					echo '</div>';
				}
				if (!empty($updatedDocsList) && $updatedDocsList != '<ul></ul>') {
					echo '<div class="tabbed-content__panel active">';
					echo '<h3 class="show-for-sr">' . $documentsTabText . '</h3>';
					echo $updatedDocsList;
					echo '</div>';
				}
				echo '</div>';
				echo '</div>';
			}
			?>

			<?php
			// Show the links attached to this resource
			$attachedLinks = $resourceMeta->resource_attached_links ?? [];

			if (!empty($attachedLinks)) {
				echo '<div class="module-panel module-panel--resources">';
				echo '<div class="module-panel__header" style="background-color: ' . esc_html($moduleMeta['module_color']) . ';">';
				echo '<h2>Helpful resources</h2>';
				echo '</div>';
				echo '<div class="module-panel__content tabbed-content__panel active">';
				echo '<ul class="module-panel__list">';
				foreach ($attachedLinks as $linkID) {
					$linkMeta = theme_get_meta($linkID);
					$linkURL = isset($linkMeta->page_link_url) ? $linkMeta->page_link_url : '';
					$linkActive = isset($linkMeta->page_link_is_active) ? $linkMeta->page_link_is_active : 'off';
					
					$linkTitle = get_the_title($linkID);
					if (!empty($linkURL) && $linkActive != 'off') {
						echo '<li><a href="' . esc_url($linkURL) . '" target="_blank" rel="nofollow"><span>' . $linkout_svg . esc_html($linkTitle) . '</span></a></li>';
					}
				}
				echo '</ul>';
				echo '</div>';
				echo '</div>';
			}
			?>
			<?php echo '</div>'; ?>
			<?php echo '</div>'; ?>
		<?php // var_dump($post_id);
			log_user_interaction(get_permalink(), $post_id, 10, 'Viewed page', get_the_title());
		} else {
			//$content = get_the_content();
			//$content = wp_strip_all_tags($content);
			//$words = explode(' ', $content);
			//$limited_content = implode(' ', array_slice($words, 0, 50));
			//echo '<p>' . $limited_content . '...</p>';
			echo '<div class="resource-module__login-prompt full-width">';
			echo "<p><strong><span class=\"is-login\"><a href='" . home_url() . "'>Login</a></span> or <span class=\"is-register\"><a href='" . home_url() . "'>register</a></span> to access this content.</strong></p>";
		echo '</div>';
		} ?>

		<?php edit_post_link(__('(Edit)', $namespace), '<span class="edit-link">', '</span>'); ?>