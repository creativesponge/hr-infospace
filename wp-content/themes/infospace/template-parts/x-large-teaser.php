<?php $postId = $args['fpost_id'] ?? null;  ?>
<?php $featuredMeta = theme_get_meta($postId); ?>

<?php $featuredSummary = isset($featuredMeta->post_summary) ? $featuredMeta->post_summary : "<p>" . get_the_excerpt($postId). "</p>"; ?>
<?php $moduleColour = $args['moduleColour'] ?? ''; ?>
<?php $postImage = get_the_post_thumbnail($postId, 'featurednewstall') != '' ? get_the_post_thumbnail($postId, 'featurednewstall') : wp_get_attachment_image(1781, 'featurednewstall'); ?>
<div class="cell small-12 medium-8 large-teaser">

    <div class="x-large-teaser__target">

        <div class="x-large-teaser__image">
            <a href="<?php echo get_permalink($postId); ?>">
               <div style="background: <?php echo esc_html($moduleColour); ?>;">Featured story</div>
                <?php echo $postImage; ?>
            </a>
        </div>

        <div class="x-large-teaser__content">
            <p class="x-large-teaser__date">
                <?php echo get_the_date('jS F, Y', $postId); ?>
            </p>
            <h3><a href="<?php echo get_permalink($postId); ?>"><?php echo get_the_title($postId); ?></a></h3>
            <?php echo $featuredSummary; ?>
            <p><a href="<?php echo get_permalink($postId); ?>" class="arrow-link" style="color: <?php echo esc_html($moduleColour); ?>;">Read</a></p>
        </div>

    </div>


</div>