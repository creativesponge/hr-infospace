<?php $postId = get_the_ID(); ?>
<?php $moduleColour = $args['moduleColour'] ?? ''; ?>
<div class="cell small-12 medium-4 large-teaser">

    <div class="large-teaser__target">


        <div class="large-teaser__content">
            <p class="large-teaser__date">
                <?php echo get_the_date('jS F, Y', $postId); ?>
            </p>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo get_the_excerpt($postId); ?></p>
             <p><a href="<?php echo get_permalink($postId); ?>" class="arrow-link" style="color:<?php echo esc_attr($moduleColour); ?>;">Read</a></p>
        </div>

    </div>


</div>