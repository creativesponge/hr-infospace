<?php $block_attributes = get_query_var('attributes'); ?>
<?php //print_r($block_attributes); ?>
<?php //$meta = theme_get_meta(); ?>
<?php //print_r($meta); ?>

<?php
$blockHeading = isset($block_attributes['mainHeading']) ? $block_attributes['mainHeading'] : "";
$blockStapline = isset($block_attributes['strapLine']) ? $block_attributes['strapLine'] : "";
$imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '';
$attachmentIdMob = (array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : '';

?>
<section class="page-banner-titles full-width">

		<div class="page-banner-titles__inner" >
			<div class="page-banner-titles__background">
			<?php if ($imageId) { 
				echo wp_get_attachment_image($imageId, 'fpxlarge', '', ["class" => "wp-image-$imageId"]);			
			} ?>
			</div>
			 <div class="page-banner-titles__headings">
				 <?php if (isset($blockHeading)) {
					 echo "<h3>".$blockHeading."</h3>";
				 } ?>
				 <?php if (isset($blockStapline)) {
					 echo "<p>".$blockStapline."</p>";
				 } ?>
			</div>

		</div>

</section>
