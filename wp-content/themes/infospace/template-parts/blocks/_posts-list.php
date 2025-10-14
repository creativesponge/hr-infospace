<?php $block_attributes = get_query_var('attributes'); ?>
<?php //print_r($block_attributes); ?>
<?php $mainCat = isset($block_attributes['selectedCategory']) ? $block_attributes['selectedCategory'] : ''; ?>
<?php $numberPosts = isset($block_attributes['numberPosts']) ? $block_attributes['numberPosts'] : '3'; ?>

<section class="posts-list type-<?php echo $mainCat; ?> ">
    <div class="posts-list__container grid-x">

        <?php
        $args = array(
            'post_type' => 'post', // This is the name of your post type - change this as required,
            'posts_per_page' => $numberPosts, // This is the amount of posts per page you want to show
            'paged' => get_query_var('paged'),
            'ignore_sticky_posts' => 1,
            'category__in' => $mainCat,
            'orderby' => 'date',
            'order'   => 'DESC',
        );

        $loop = new WP_Query($args);

        // Get current page and append to custom query parameters array
        $args['paged'] = get_query_var('paged') ? get_query_var('paged') : 1;

        // Instantiate custom query
        $loop = new WP_Query($args);

        // Pagination fix
        $temp_query = $wp_query;
        $wp_query   = NULL;
        $wp_query   = $loop;

        while ($loop->have_posts()) : $loop->the_post();
            $postId = get_the_ID();

            if ($postId) {
                get_template_part('template-parts/large-teaser', '', $args);
            }
        endwhile;

        if (function_exists('startertheme_pagination')) :
            startertheme_pagination();
        endif;
        
        wp_reset_postdata();
        // Reset main query object
        $wp_query = NULL;
        $wp_query = $temp_query;
        ?>
    </div>

</section>