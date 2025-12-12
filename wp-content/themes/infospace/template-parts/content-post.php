<?php

/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 */

global $namespace;
$post_id = get_the_ID();

$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
$moduleMeta = get_current_module_meta($current_module_id_global);
$moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="resource-module__header full-width" style="background-color: <?php echo esc_html($moduleColour); ?>;">
		<div class="resource-module__header-inner"  >
			<div class="resource-module__header-content">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</div>
		</div>
		
	</header>
	<div class="entry-content" id="contentskip">
		<?php echo '<div class="resource-module__news full-width">'; ?>
		<?php echo '<div class="resource-module__news-container">'; ?>
		<?php the_content(); ?>
		<?php echo '<div>'; ?>
		<?php echo '</div>'; ?>
		<?php log_user_interaction(get_permalink(), $post_id, 8, 'Viewed news', get_the_title()); ?>
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