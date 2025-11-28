<?php 
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
$current_user_favourites = isset($_SESSION['current_user_favourite_ids']) ? $_SESSION['current_user_favourite_ids'] : '';
////echo "Current Post ID: " . get_the_ID() . "<br>";
//echo "<pre>";
//var_dump($current_user_favourites);
//echo "</pre>";
$button_class = '';
$button_text = 'Add to \'my favourites\'';
if (is_array($current_user_favourites) && in_array(get_the_ID(), $current_user_favourites)) {
	$button_class .= ' add-to-favourites--filled';
	$button_text = 'Remove from \'my favourites\'';
}
?>
<button class="add-to-favourites<?php echo esc_attr($button_class); ?>" data-id="<?php echo esc_attr(get_the_ID()); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>" data-type="<?php echo esc_attr(get_post_type()); ?>">
	<?php get_template_part('template-parts/svgs/_favourite') ?><span><?php echo esc_html($button_text); ?></span>
</button>