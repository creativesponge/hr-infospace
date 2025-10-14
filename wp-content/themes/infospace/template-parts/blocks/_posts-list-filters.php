<?php $block_attributes = get_query_var('attributes'); ?>
<?php $numberPosts = isset($block_attributes['numberPosts']) ? $block_attributes['numberPosts'] : '9'; ?>
<?php $sorttBy = isset($_GET['orderby']) ? $_GET['orderby'] : 'mostrecent'; ?>
<?php $filterBy = isset($_GET['cat']) ? $_GET['cat'] : ''; ?>
<?php $filterCat = get_term_by('slug', $filterBy, 'category'); ?>

<section class="posts-list-filters full-width">
 
    <div id="list-top" class="listing-filter">
        <div class="filter__list">
            <button class="filter__heading">Filter</button>
            <ul class="nav-filter" id="filter">

                <li>
                    <?php $linkClass = (isset($filterCat->slug)) ? '' : ' selected'; ?>
                    <a href="<?php the_permalink(); ?>?cat=all<?php echo $sorttBy ? '&orderby=' . $sorttBy : ''; ?>" class='tick-button<?php echo $linkClass; ?>'>
                        All
                    </a>
                </li>

                <?php

                $terms = get_terms('category');
                $terms = array_reverse($terms);
                ?>

                <?php foreach ($terms as $term) : ?>
                    <?php if ($term->slug != 'uncategorized') { ?>

                        <?php $linkClass = (isset($filterCat->slug) && $term->slug == $filterCat->slug) ? ' selected' : ''; ?>

                        <li>
                            <a href="<?php the_permalink(); ?>?cat=<?php echo esc_attr($term->slug); ?>&orderby=<?php echo $sorttBy; ?>" class='tick-button<?php echo $linkClass; ?>'>
                                <?php echo esc_html($term->name); ?>
                            </a>
                        </li>
                    <?php } ?>
                <?php endforeach; ?>

            </ul>
        </div>
        <div class="filter__list">
            <button class="filter__heading">Sort by</button>
            <ul class="nav-filter" id="filter">
            <li>
                    <?php $linkClass = ($sorttBy == 'name') ? ' selected' : ''; ?>
                    <a href="<?php the_permalink(); ?>?orderby=name&cat=<?php echo $filterBy; ?>" class='tick-button<?php echo $linkClass; ?>'>
                        Name
                    </a>
                </li>
                <li>
                    <?php $linkClass = ($sorttBy == 'mostrecent') ? ' selected' : ' '; ?>
                    <a href="<?php the_permalink(); ?>?orderby=mostrecent&cat=<?php echo $filterBy; ?>" class='tick-button<?php echo $linkClass; ?>'>
                        Most recent
                    </a>
                </li>
                <li>
                    <?php $linkClass = ($sorttBy == 'oldest') ? ' selected' : ' '; ?>
                    <a href="<?php the_permalink(); ?>?orderby=oldest&cat=<?php echo $filterBy; ?>" class='tick-button<?php echo $linkClass; ?>'>
                        Oldest
                    </a>
                </li>
                <li>
                    <?php $linkClass = ($sorttBy == 'featured') ? ' selected' : ' '; ?>
                    <a href="<?php the_permalink(); ?>?orderby=featured&cat=<?php echo $filterBy; ?>" class='tick-button<?php echo $linkClass; ?>'>
                        Featured
                    </a>
                </li>
                
            </ul>
        </div>
    </div>
    <div class="posts-list-filters__container">
        <div class="grid-margin-x grid-x">
            <?php
            $args = array(
                'post_type' => 'post', // This is the name of your post type - change this as required,
                'posts_per_page' => $numberPosts, // This is the amount of posts per page you want to show
                'paged' => get_query_var('paged'),
                'ignore_sticky_posts' => 1,

            );
            if (isset($filterCat->term_id)) {
                $args['category__in'] = $filterCat->term_id;
            }
            

            if ($sorttBy == 'mostrecent') {
                $args['orderby'] = array('date' => 'DESC');
            }  elseif ($sorttBy == 'oldest') {
                $args['orderby'] = array('date' => 'ASC');
            } elseif ($sorttBy == 'featured') {
                $args['ignore_sticky_posts'] = 0;
                $args['orderby'] = array('date' => 'DESC');
            } elseif ($sorttBy == 'name') {
                $args['orderby'] = array('title' => 'ASC');
                
            }

         

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

                $termsString = "heading";
                $args = array($termsString);

                if ($postId) {
                    get_template_part('template-parts/large-teaser', '', $args);
                }
            endwhile;
            ?>
        </div>
        <?php
        if (function_exists('startertheme_pagination')) :

            startertheme_pagination();
            //elseif (is_paged()) :
        ?>
           

        <?php endif;

        wp_reset_postdata();

        // Reset main query object
        $wp_query = NULL;
        $wp_query = $temp_query;
        ?>
    </div>

</section>