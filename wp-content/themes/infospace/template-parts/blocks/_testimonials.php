<section class="testimonials">
    <div class="testimonials__carousel">
        <?php
        $loop = new WP_Query(
            array(
                'post_type' => 'testimonial', // This is the name of your post type - change this as required,
                'posts_per_page' => -1 // This is the amount of posts per page you want to show
            )
        );
        while ($loop->have_posts()) : $loop->the_post();
            // The content you want to loop goes in here:
            $testimonialsId = get_the_ID();
       

            if ($testimonialsId) {
                $testimonialMeta = theme_get_meta($testimonialsId);
            ?>
        
                <article class="testimonial">
                    
                    <div class="testimonial__container">
                        <?php if (isset($testimonialMeta->testimonial_quote)) { ?>
                            <div class="testimonial__quote">
                                <p><?php echo $testimonialMeta->testimonial_quote;  ?></p>
                                <?php if (isset($testimonialMeta->testimonial_citation)) { ?>
                                <cite><?php echo $testimonialMeta->testimonial_citation;  ?></cite>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                </article>

            <?php } ?>
        <?php 
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
</section>