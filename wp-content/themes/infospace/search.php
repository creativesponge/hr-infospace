<?php

/**
 * The template for displaying search results pages.
 *
 */
global $namespace;
global $prefix;
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
$imageId = isset($settings[$prefix . 'search_heading_image_id']) ? $settings[$prefix . 'search_heading_image_id'] : '';
$attachmentIdMob = isset($settings[$prefix . 'search_heading_image_mobile_id']) ? $settings[$prefix . 'search_heading_image_mobile_id'] : '';

$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
$moduleMeta = get_current_module_meta($current_module_id_global);
$moduleColour = (array_key_exists('module_color', $moduleMeta)) ? $moduleMeta['module_color'] : '';

ob_start();
get_template_part('template-parts/svgs/_linkout');
$linkout_svg = ob_get_clean();

get_header(); ?>

<div class="main-container">
	<div class="main-grid">
		<main class="search-results main-content">
			<div class="full-width">
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
				?>
				<div class="search-results__top-container" style="background: <?php echo esc_html($moduleColour); ?>;">
					<div class="search-results__content">
						<div class="search-results__text">
							<div class="search-results__header">
								<h1>Search &amp; results</h1>
								<div class="search-form-container">
									<?php get_search_form(); ?>
								</div>
							</div>
						</div>
					</div>
					<?php
					if ($imageId) : ?>
						<div class="search-results__image">
							<?php

							echo wp_get_attachment_image($imageId, 'imagetext', '', ["class" => "show-for-medium wp-image-$imageId"]);
							if ($attachmentIdMob) {
								echo wp_get_attachment_image($attachmentIdMob, 'imagetext', '', ["class" => "hide-for-medium wp-image-$attachmentIdMob"]);
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<header>
				<h1 class="entry-title"><?php _e('Search Results for', $namespace); ?> "<?php echo get_search_query(); ?>"</h1>
			</header>

			<?php if (have_posts()) : ?>

				<?php while (have_posts()) : the_post(); ?>
				<?php $result_Id = get_the_ID();
				$post_type = get_post_type($result_Id);
				
				?>
				<?php var_dump($post_type); if ($post_type == 'document' && empty($attached_doc_array)) {  ?>
					<?php $attached_doc_array = get_post_meta($result_Id, $prefix . 'document_files', true); var_dump($attached_doc_array); ?>
					<div class="search-results__item">
						<div class="search-icon">
							<a href="<?php the_permalink(); ?>">
								<?php  $result_Id = $attached_doc_array[0]['theme_fieldsdoc_uploaded_file_id'];
							$filename = $attached_doc_array[0]["theme_fieldsfilename"];
							$file_svg = get_file_svg_from_filename($filename);
							$doc_url = '/download-document/' . $result_Id; ?>
							<?php echo $file_svg; ?>
							</a>
						</div>
						<div class="search-excerpt">
							<h3><a href="<?php echo $doc_url ?>"><?php the_title(); ?></a></h3>
							<a href="<?php echo $doc_url ?>"><?php the_excerpt(); ?></a>

						</div>
					</div>
					<?php } else { ?>
					<div class="search-results__item">
						<div class="search-results__icon">
							<a href="<?php the_permalink(); ?>">
								<?php echo $linkout_svg; ?>
							</a>
						</div>
						<div class="search-excerpt">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a>
					</div>
					</div>
					<?php } ?>
				<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part('template-parts/content', 'none'); ?>

			<?php endif; ?>

			<?php
			if (function_exists('startertheme_pagination')) :
				startertheme_pagination();
			elseif (is_paged()) :
			?>
				<nav id="post-nav">
					<div class="post-previous"><?php next_posts_link(__('&larr; Older posts', $namespace)); ?></div>
					<div class="post-next"><?php previous_posts_link(__('Newer posts &rarr;', $namespace)); ?></div>
				</nav>
			<?php endif; ?>

		</main>
		<?php get_sidebar(); ?>

	</div>
</div>
<?php get_footer();
