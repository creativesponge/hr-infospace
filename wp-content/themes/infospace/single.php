<?php
/**
 * The template for displaying all single posts and attachments
 *

 */

get_header(); 
$post_type = get_post_type();
?>


<div class="main-container">
	<div class="main-grid">
		<main class="main-content">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', $post_type ); ?>
				<?php //the_post_navigation(); ?>
				<?php //comments_template(); ?>
			<?php endwhile; ?>
		</main>
	</div>
</div>
<?php get_footer();
