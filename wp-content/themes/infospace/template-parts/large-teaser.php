<?php  $postId = get_the_ID(); ?>

<div class="cell small-12 medium-4 large-teaser">

    <div class="large-teaser__target">

        <div class="large-teaser__image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('smallsquare'); ?>
            </a>
        </div>

        <div class="large-teaser__content">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php echo the_excerpt(20,$postId);?>
        </div>

</div>
   
    
</div>