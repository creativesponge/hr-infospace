<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>

<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>

<?php
$blockHeading = isset($block_attributes['mainHeading']) ? $block_attributes['mainHeading'] : "";
$blockStapline = isset($block_attributes['strapLine']) ? $block_attributes['strapLine'] : "";
$imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '';
$attachmentIdMob = (array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : $imageId;

?>
<section class="page-banner-titles full-width">

	<div class="page-banner-titles__inner">
		<div class="page-banner-titles__background">
			<?php if ($imageId) {
				echo wp_get_attachment_image($imageId, 'fpxlarge', '', ["class" => "show-for-medium wp-image-$imageId"]);
			} ?>
			<?php if ($attachmentIdMob) {
				echo wp_get_attachment_image($attachmentIdMob, 'fpxlarge', '', ["class" => "hide-for-medium wp-image-$attachmentIdMob"]);
			} ?>
			<?php get_template_part('template-parts/svgs/_banner-mask2'); 
			?>
		</div>

		<div class="page-banner-titles__headings">
			<?php if (isset($blockHeading)) {
				echo "<h1>" . $blockHeading . "</h1>";
			} ?>


			<?php echo $block_content; ?>
		</div>

	</div>

</section>