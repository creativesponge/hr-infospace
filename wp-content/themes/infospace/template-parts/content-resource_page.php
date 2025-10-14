<?php

/**
 * The default template for displaying page content
 *
 *
 */
global $prefix;
global $namespace;
$user = wp_get_current_user();
$post_id = get_the_ID();

?>

<article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
	<!--<header>
		<h1 class="entry-title"><?php //the_title(); 
								?></h1>
	</header>-->
	<div class="entry-content">
		<?php
		if (user_has_access($post_id)) {
			echo "has access"; // The user has the "main" role, do redirects for main users
			the_content();

			// show a list of document attached to this page
			$attached_docs = get_post_meta($post_id, $prefix . 'resource_attached_documents', true);
			if (!empty($attached_docs)) {
				echo '<h2>Documents</h2>';
				echo '<ul class="attached-page-docs">';
				foreach ($attached_docs as $docId) {
					$doc_post = get_post($docId);

					$doc_files = get_post_meta($docId, $prefix . 'document_files', true);
			
					if (!empty($doc_files)) {


						foreach ($doc_files as $docFile) {

							$docFileId = $docFile[$prefix . 'doc_uploaded_file_id'];
							$title = $docId ? get_the_title($docId) : "No title";
							$doc_url = $docFileId ? '/download-document/' . $docFileId : "";
							echo '<li><a href="' . esc_url($doc_url) . '" data-download-id="' . esc_attr($docId) . '" rel="nofollow">' . esc_html($title) . '</a></li>';
						}
					}
				}
				echo '</ul>';
			}

			// show a list of page_link attached to this page
			$attached_links = get_post_meta($post_id, $prefix . 'resource_attached_links', true);
			if (!empty($attached_links)) {
				echo '<h2>Links</h2>';
				echo '<ul class="attached-page-links">';
				foreach ($attached_links as $linkId) {
					$link_post = get_post($linkId);

					$link_url = get_post_meta($linkId, $prefix . 'page_link_url', true);


					$title = $linkId ? get_the_title($linkId) : "No title";
					echo '<li><a href="' . esc_url($link_url) . '" data-link-id="' . esc_attr($linkId) . '" target="_blank" rel="nofollow">' . esc_html($title) . '</a></li>';
				}
				echo '</ul>';
			}

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