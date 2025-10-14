<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>

<?php $mainCatImport = isset($block_attributes['selectedCategory']) ? $block_attributes['selectedCategory'] : ''; ?>
<?php $mainCat = explode(',', $mainCatImport); // convert to array to set up cats for featured resources 
?>
<?php $blockHeading = (array_key_exists('mainHeading', $block_attributes)) ? $block_attributes['mainHeading'] : '' ;
 ?>
<?php $currentPostId = get_the_ID(); ?>

<?php if (isset($block_attributes['urlone'])) {
    $storyIdOne = url_to_postid($block_attributes['urlone']);
} ?>
<?php if (isset($block_attributes['urltwo'])) {
    $storyIdTwo = url_to_postid($block_attributes['urltwo']);
} ?>
<?php if (isset($block_attributes['urlthree'])) {
    $storyIdThree = url_to_postid($block_attributes['urlthree']);
} ?>
<?php if (isset($block_attributes['urlfour'])) {
    $storyIdFour = url_to_postid($block_attributes['urlfour']);
} ?>
<?php if (isset($block_attributes['urlfive'])) {
    $storyIdFive = url_to_postid($block_attributes['urlfive']);
} ?>
<?php if (isset($block_attributes['urlsix'])) {
    $storyIdSix = url_to_postid($block_attributes['urlsix']);
} ?>
<?php $postIdOne = isset($storyIdOne) ? $storyIdOne : ''; ?>
<?php $postIdTwo = isset($storyIdTwo) ? $storyIdTwo : ''; ?>
<?php $postIdThree = isset($storyIdThree) ? $storyIdThree : ''; ?>
<?php $postIdFour = isset($storyIdFour) ? $storyIdFour : ''; ?>
<?php $postIdFive = isset($storyIdFive) ? $storyIdFive : ''; ?>
<?php $postIdSix = isset($storyIdSix) ? $storyIdSix : ''; ?>
<?php $postIn = [$postIdOne, $postIdTwo, $postIdThree,$postIdFour, $postIdFive, $postIdSix]; ?>
<?php $numberPosts = isset($block_attributes['numberPosts']) ? $block_attributes['numberPosts'] : '3'; ?>

<?php 
$args = array(
    'post_type' => 'post', // This is the name of your post type - change this as required,
    'posts_per_page' => $numberPosts, // This is the amount of posts per page you want to show
    'ignore_sticky_posts' => 1,
    'post__not_in' => array($currentPostId),
    'orderby' => 'date',
    'order'   => 'DESC',
    'fields'   => 'ids',
);

if (!empty($mainCat[0])) {
    $args['category__in'] = $mainCat;
}

$loopforLatest = new WP_Query($args); // Get latest posts
$postIn = array_merge($postIn, $loopforLatest->posts); // merge latest ids with chosen posts

if ($postIdOne ||  $postIdTwo || $postIdThree || $postIdFour || $postIdFive || $postIdSix) {
    $args['post__in'] = $postIn;
    $args['orderby'] = 'post__in';
}

$loop = new WP_Query($args); //get all used posts
$loopCount = 0;

if ($loop->have_posts()) { ?>
    <section class="latest-posts type-<?php echo implode('', $mainCat); ?> ">
        <?php if (isset($blockHeading)) {
			echo "<h2>".$blockHeading."</h2>";
		} ?>
        <div class="latest-posts__container grid-x number-posts-<?php echo $numberPosts; ?>">
            <?php while ($loop->have_posts()) : $loop->the_post();

                $postId = get_the_ID();
                $args = array($mainCatImport);

                if ($postId) {
                    get_template_part('template-parts/large-teaser', '', $args);
                }
            endwhile; ?>
        </div>

        <?php echo $block_content; ?>

    </section>
<?php }
wp_reset_postdata();
?>