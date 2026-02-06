<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>

<?php
$imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '';
$attachmentIdMob = (array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : $imageId;

?>
<section class="image-text-banner full-width">

	<div class="image-text-banner__container">

		<div class="image-text-banner__content">
			<div class="image-text-banner__text">
           
				<?php echo $block_content; ?>
			</div>
		</div>
		<?php if ($imageId) { ?>
			<div class="image-text-banner__image">
				<?php if ($imageId) { 
				echo wp_get_attachment_image($imageId, 'fplarge', '', ["class" => "show-for-medium", "fetchpriority" => "high"]); 		
					
			} ?>
			<?php if ($attachmentIdMob) { 
				echo wp_get_attachment_image($attachmentIdMob, 'fplarge', '', ["class" => "hide-for-medium", "fetchpriority" => "high"]);			
			} ?>
				
			</div>
		<?php } ?>

	</div>

</section>