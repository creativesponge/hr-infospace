<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>

<?php
$alignmentClass = (isset($block_attributes['imageAlignment']) && $block_attributes['imageAlignment'] == 1) ? "image-text--reversed" : "";
$imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '';

?>
<section class="image-text <?php echo $alignmentClass ?>">
	<div class="image-text__colour">
		<div class="image-text__container">

			<div class="image-text__content">
				<div class="image-text__text">
					<?php echo $block_content; ?>
				</div>
			</div>
			<?php if ($imageId) { ?>
				<div class="image-text__image">
					<?php echo wp_get_attachment_image($imageId, 'imagetext', '', ["class" => "wp-image-$imageId"]); ?>
				</div>
			<?php } ?>

		</div>
	</div>
</section>