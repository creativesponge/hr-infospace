<?php $block_attributes = get_query_var('attributes'); ?>
<?php global $shownPosts; ?>

<?php $numberPosts = isset($block_attributes['numberPosts']) ? $block_attributes['numberPosts'] : '8'; ?>
<?php //$numberPosts = '3'; 
?>

<?php
global $site_options;
// get terms to build  list
$atts = '';
$a = shortcode_atts(array(
    'terms'    => false, // Get specific taxonomy terms only
    'active'   => false, // Set active term by ID
    'per_page' => $numberPosts // How many posts per page
), $atts);
$result = NULL;
$postsCategory = 'category';
$postsCategory2 = 'category';
$slug = 'all-terms';
$terms1  = get_terms(
    array(
        'taxonomy' => $postsCategory,
    )
);
$terms2  = get_terms(
    array(
        'taxonomy' => $postsCategory2,
    )
);

global $shownPosts;
$shownList = isset($shownPosts) ? implode(', ', $shownPosts) : null;

if (count($terms1) && count($terms2)) :
?>

    <section class="posts-list">
        <div id="posts-container-async" data-notin="<?php echo $shownList; ?>" data-paged="<?php echo $a['per_page']; ?>" data-term="<?php echo $slug; ?>" data-page="1" class="ajax-filter cell posts-list show-<?php echo $numberPosts; ?>">
            <div class="posts-list-filter">

                <form role="search" method="get" id="postsform" class="searchform searchform--posts" action="<?php echo home_url('/'); ?>">
                    <div class="input-group">
                        <input type="text" class="input-group-field" value="" name="s" id="posts-search" aria-label="Search" placeholder="<?php
                                                                                                                                            esc_attr_e('SEARCH', 'foundationpress'); ?>">
                        <div class="input-group-button">
                            <button form="postsform" value="<?php esc_attr_e('Search', 'foundationpress'); ?>" type="submit" id="searchsubmit"><span class="show-for-sr"><?php esc_attr_e('Search', 'foundationpress'); ?></span></button>
                        </div>

                    </div>
                </form>
                <div class="filter__listing">

                    <div class="filter__reveal-content" id="filter__reveal-content">
                        <div class="all__list">
                            <ul class="nav-filter" aria-label="Post filter taxonomy list">

                                <li>
                                    <a class="show-all posts-list-filters" href="#" data-filter="<?php echo $postsCategory ?>" data-term="<?php echo $slug; ?>" data-page="1" data-type="link" aria-label="show all">
                                        All
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <div class="filter__list">
                            <button class="filter__heading" data-label="Post type">Cat 1 type</button>
                            <ul class="nav-filter nav-filter--cats" aria-label="Post filter taxonomy list">

                                <?php foreach ($terms1 as $term) : ?>
                                    <?php $meta = get_metadata('term', $term->term_id); ?>

                                    <?php if ($term->name != "None") { ?>
                                        <li <?php if ($term->term_id == $a['active']) : ?> class="active" <?php endif; ?>>

                                            <a href="#" data-filter="<?php echo $term->taxonomy; ?>" data-term="<?php echo $term->slug; ?>" data-page="1" data-type="link" aria-label="Filter link for <?php echo $term->slug; ?>">

                                                <?php echo $term->name; ?>
                                            </a>

                                        </li>


                                    <?php } ?>
                                <?php endforeach; ?>

                            </ul>
                        </div>
                       <!-- <div class="filter__list">
                            <button class="filter__heading" data-label="Category">Category</button>
                            <ul class="nav-filter nav-filter--cats" aria-label="Post filter taxonomy list">

                                <?php //foreach ($terms2 as $term) : ?>
                                    <?php //$meta = get_metadata('term', $term->term_id); ?>

                                    <?php //if ($term->name != "None") { ?>
                                        <li <?php //if ($term->term_id == $a['active']) : ?> class="active" <?php //endif; ?>>

                                            <a href="#" data-filter="<?php //echo $term->taxonomy; ?>" data-term="<?php //echo $term->slug; ?>" data-page="1" data-type="link" aria-label="Filter link for <?php //echo $term->slug; ?>">

                                                <?php //echo $term->name; ?>
                                            </a>

                                        </li>
                                    <?php //} ?>
                                <?php //endforeach; ?>



                            </ul>
                        </div>-->
                        <div class="filter__list">
                            <button class="filter__heading" data-label="Sort">Sort</button>
                            <ul class="nav-filter nav-filter--sort" aria-label="Post filter taxonomy list">
                                <li <?php  if ($term->term_id == $a['active']) : ?> class="active" <?php endif; ?>>
                                    <a href="#" data-order="ASC" data-type="link" data-filter="A - Z" data-page="1" aria-label="Filter link for A - Z">
                                        A - Z
                                    </a>
                                </li>
                                <li <?php  if ($term->term_id == $a['active']) : ?> class="active" <?php endif; ?>>
                                    <a href="#" data-order="DESC" data-type="link" data-filter="Z - A" data-page="1" aria-label="Filter link for Z - A">
                                        Z - A
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>


                </div>

            </div>
            <section class="posts-list__results">
                <div class="filter-data grid-container">
                    <div class="filters-data__results"></div>
                    <div class="filters-data__topics"><button class="clear-button temp-hidden" data-filter="<?php echo $term->taxonomy; ?>" data-term="all-terms" data-page="1" data-order="ASC" data-type="link" aria-label="show all"></button></div>
                </div>
                <div class="posts-list">

                    <div class="content posts-list__container grid-x" data-equalizer data-equalize-by-row="true" data-equalize-on="medium" aria-live="assertive" role="grid" id="post-list-results"></div>
                    <div class="status" role="alert"> </div>
                </div>

            </section>
        </div>
    </section>
<?php


endif;

?>