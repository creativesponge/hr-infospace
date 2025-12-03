<?php

/**
 * The default template for displaying resource pages
 *
 *
 */


$post_id = get_the_ID();

// check if is landing page for modules
$moduleMeta = get_current_module_meta(null);
$moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '';

if (check_if_is_module_landing($post_id, $moduleMeta["attached_resources"])) {
	get_template_part('template-parts/content-resource_module', get_post_format());
} else {



	global $prefix;
	global $namespace;
	global $settings;
	$user = wp_get_current_user();
	$policiesTabText = $moduleMeta['module_id'] == 1738 ? 'Compliance code' : 'Policies';
	$documentsTabText = $moduleMeta['module_id'] == 1738 ? 'Guidance & Forms' : 'Documents';
	$siteGetInTouchText = isset($settings[$prefix . 'get_in_touch_text']) ? $settings[$prefix . 'get_in_touch_text'] : 'Can’t find what you need?';
	$siteGetInTouchUrl = isset($settings[$prefix . 'get_in_touch_url']) ? $settings[$prefix . 'get_in_touch_url'] : '/contact/';


	ob_start();
	get_template_part('template-parts/svgs/_linkout');
	$linkout_svg = ob_get_clean();

	// get favourites for this user
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$current_user_favourites = isset($_SESSION['current_user_favourite_ids']) ? $_SESSION['current_user_favourite_ids'] : '';

	//ob_start();
	//get_template_part('template-parts/svgs/_word-doc');
	//$word_doc_svg = ob_get_clean();

	// Get a list of document attached to this page
	$attached_docs = get_post_meta($post_id, $prefix . 'resource_attached_documents', true);
	$attached_docs_list = "";
	$attached_policies_list = "";
	if (!empty($attached_docs)) {

		foreach ($attached_docs as $docId) {
			$doc_post = get_post($docId);

			$doc_files = get_post_meta($docId, $prefix . 'document_files', true);

			if (!empty($doc_files)) {
				foreach ($doc_files as $docFile) {
					$filename = $docFile["theme_fieldsdoc_uploaded_file"];

					$file_svg = get_file_svg_from_filename($filename);
					$docFileId = $docFile[$prefix . 'doc_uploaded_file_id'];
					$title = $docId ? get_the_title($docId) : "No title";
					$doc_url = $docFileId ? '/download-document/' . $docFileId : "";

					// Get the taxonomy terms for this document
					$taxonomies = get_the_terms($docId, 'doc_type'); // Adjust taxonomy name as needed

					// Check if document has 'policy' taxonomy
					$is_policy = $taxonomies && !is_wp_error($taxonomies) && in_array('policy', wp_list_pluck($taxonomies, 'slug'));

					// Create favoutite button

					$button_class = '';
					$button_text = 'Add to \'my favourites\'';

					if (is_array($current_user_favourites) && in_array($docId, $current_user_favourites)) {
						$button_class .= ' add-to-favourites--filled';
						$button_text = 'Remove from \'my favourites\'';
					}
					ob_start();

					echo '<button class="add-to-favourites add-to-favourites--small' . esc_attr($button_class) . '" data-id="' . esc_attr($docId) . '" data-name="' . esc_attr(get_the_title($docId)) . '" data-type="' . esc_attr(get_post_type($docId)) . '">';
					get_template_part('template-parts/svgs/_favourite');
					echo '<span class="show-for-sr">' . esc_html($button_text) . '</span>';
					echo '</button>';

					$favourite_svg = ob_get_clean();

					// Add to appropriate list based on taxonomy
					if ($is_policy) {
						$attached_policies_list .= '<li class="policy-doc"><a href="' . esc_url($doc_url) . '" data-download-id="' . esc_attr($docId) . '" rel="nofollow"><span>' . $file_svg . esc_html($title) . '</span>' . $favourite_svg . '</a></li>';
					} else {
						$attached_docs_list .= '<li class="document-doc"><a href="' . esc_url($doc_url) . '" data-download-id="' . esc_attr($docId) . '" rel="nofollow"><span>' . $file_svg . esc_html($title) . '</span>' . $favourite_svg . '</a></li>';
					}
				}
			}
		}
	}

	// show a list of page_link attached to this page
	$attached_links = get_post_meta($post_id, $prefix . 'resource_attached_links', true);
	$attached_links_list = "";

	if (!empty($attached_links)) {
		foreach ($attached_links as $linkId) {
			$link_post = get_post($linkId);
			$link_url = get_post_meta($linkId, $prefix . 'page_link_url', true);
			$title = $linkId ? get_the_title($linkId) : "No title";

			// Create favoutite button

					$button_class = '';
					$button_text = 'Add to \'my resources\'';

					if (is_array($current_user_favourites) && in_array($linkId, $current_user_favourites)) {
						$button_class .= ' add-to-favourites--filled';
						$button_text = 'Remove from \'my resources\'';
					}
					ob_start();

					echo '<button class="add-to-favourites add-to-favourites--small' . esc_attr($button_class) . '" data-id="' . esc_attr($linkId) . '" data-name="' . esc_attr(get_the_title($linkId)) . '" data-type="' . esc_attr(get_post_type($linkId)) . '">';
					get_template_part('template-parts/svgs/_favourite');
					echo '<span class="show-for-sr">' . esc_html($button_text) . '</span>';
					echo '</button>';

					$favourite_svg = ob_get_clean();

			$attached_links_list .= '<li><a href="' . esc_url($link_url) . '" data-link-id="' . esc_attr($linkId) . '" target="_blank" rel="nofollow"><span>' . $linkout_svg . esc_html($title) . '</span>' . $favourite_svg . '</a></li>';
		}
	}

	// Get a list of resources attached to this page
	$attached_resources = get_post_meta($post_id, $prefix . 'resource_attached_resources', true);
	// get linked resource
	$attached_resources_list = "";
	if (!empty($attached_resources)) {
		foreach ($attached_resources as $resourceId) {
			if (user_has_access($resourceId)) {
				// User has access to this resource
				$resource_post = get_post($resourceId);
				$resource_url = get_permalink($resourceId);
				$title = $resourceId ? get_the_title($resourceId) : "No title";
				$attached_resources_list .= '<li class="side-link"><a href="' . esc_url($resource_url) . '" data-link-id="' . esc_attr($resourceId) . '">' . esc_html($title) . '</a></li>';
			}
		}
	}


	// Get child resources (resources that have this page as their parent)
	$child_resources_list = "";
	$child_resources = get_posts(array(
		'post_type' => 'resource_page',
		'post_parent' => $post_id,
		'post_status' => 'publish',
		'numberposts' => -1
	));


	if (!empty($child_resources)) {
		foreach ($child_resources as $child_resource) {
			if (user_has_access($child_resource->ID)) {
				$child_url = get_permalink($child_resource->ID);
				$child_resources_list .= '<li class="side-link"><a href="' . esc_url($child_url) . '">' . esc_html($child_resource->post_title) . '</a></li>';
			}
		}
	}





?>

	<article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>

		<div class="entry-content resource-page">
			<div class="yoast-breadcrumbs">
				<?php echo do_shortcode('[wpseo_breadcrumb]'); ?>

			</div>
			<header class="resource-page__header full-width" style="background-color: <?php echo esc_html($moduleColour); ?>;">
				<div class="resource-page__header-inner">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php get_template_part('template-parts/add-to-favourites') ?>
				</div>
			</header>
			<div class="resource-page__box resource-page__box--green hide-for-medium">
				<h3><?php echo $siteGetInTouchText; ?></h3>
				<a href="<?php echo esc_url($siteGetInTouchUrl); ?>" class="button-link button-link--rev">Contact us</a>
			</div>
			<?php //var_dump(user_has_page_access(get_current_user_id(), $post_id, 'resource_page')); 
			if (user_has_access($post_id)) { ?>
				<div class="resource-page__grid">
					<div class="resource-page__col1">
						<?php
						//echo "has access"; // The user has the "main" role, do redirects for main users
						the_content();
						get_template_part('template-parts/add-to-favourites');
						if ((!empty($attached_docs_list) && $attached_docs_list !='')  || (!empty($attached_policies_list) && $attached_policies_list !='') || ( !empty($attached_links_list) && $attached_links_list !='')) {
						?><div class="resource-page__attachments tabbed-content">
								<h2>Helpful resources</h2>
								<ul class="resource-page__tabs tab-list tabbed-content__list" role="tablist">

								</ul>
								<div class="resource-page__tab-panels">
									<?php
									// Show a list of document attached to this page
									if (!empty($attached_docs_list)) {
										echo '<div class="resource-page__panel tabbed-content__panel active">';
										echo '<h3 class="show-for-sr">' . $documentsTabText . '</h3>';
										echo '<ul class="attached-page-docs">';
										echo $attached_docs_list;
										echo '</ul>';
										echo '</div>';
									}

									// Show a list of policies attached to this page

									if (!empty($attached_policies_list)) {
										echo '<div class="resource-page__panel tabbed-content__panel active">';
										echo '<h3 class="show-for-sr">' . $policiesTabText . '</h3>';
										echo '<ul class="attached-page-policies">';
										echo $attached_policies_list;
										echo '</ul>';
										echo '</div>';
									}
									//var_dump($taxonomies);
									// Show a list of page_link attached to this page

									if (!empty($attached_links_list)) {
										echo '<div class="resource-page__panel tabbed-content__panel active">';
										echo '<h3 class="show-for-sr">Links</h3>';
										echo '<ul class="attached-page-links">';
										echo $attached_links_list;
										echo '</ul>';
										echo '</div>';
									}
									?>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="resource-page__col2">
						<?php if (!empty($child_resources_list)) {
							
							echo '<div class="resource-page__box">';
							echo '<h3 style="color: ' . esc_html($moduleColour) . ';">Also in this section</h3>';
							echo '<ul class="child-resources">';
							echo $child_resources_list;
							echo '</ul>';
							echo '</div>';
						}

						// Show a list of resources attached to this page
						if (!empty($attached_resources_list)) {
							echo '<div class="resource-page__box">';
							echo '<h3 style="color: ' . esc_html($moduleColour) . ';">Related topics</h3>';
							echo '<ul class="attached-page-links">';
							echo $attached_resources_list;
							echo '</ul>';
							echo '</div>';
						}
						?>
						<div class="resource-page__box resource-page__box--green show-for-medium">
							<h3><?php echo $siteGetInTouchText; ?></h3>
							<a href="<?php echo esc_url($siteGetInTouchUrl); ?>" class="button-link button-link--rev">Contact us</a>
						</div>
					</div>
				</div>
			<?php
				log_user_interaction(get_permalink(), $post_id, 10, 'Viewed page', get_the_title());
			} else {
				$content = get_the_content();
				$content = wp_strip_all_tags($content);
				$words = explode(' ', $content);
				$limited_content = implode(' ', array_slice($words, 0, 50));
				echo '<p>' . $limited_content . '...</p>';
				echo "Login or <a href='" . wp_login_url() . "'>register</a> to access this content.";
			} ?>

			<?php edit_post_link(__('(Edit)', $namespace), '<span class="edit-link">', '</span>'); ?>

		</div>
		<footer>
			<?php
			wp_link_pages(
				array(
					'before' => '<nav id="page-nav"><p>' . __('Pages:', $namespace),
					'after'  => '</p></nav>',
				)
			);
			?>
			<?php $tag = get_the_tags();
			if ($tag) { ?><p><?php the_tags(); ?></p><?php } ?>
		</footer>
	</article>
<?php } ?>