<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>


<?php
$videoId = (array_key_exists('videoId', $block_attributes)) ? "https://player.vimeo.com/video/" . $block_attributes['videoId'] . "?title=0&byline=0&portrait=0&dnt=1" : '';
?>
<section class="video-block full-width">

	<?php echo $block_content; ?>
	<div class="video-block__video-display">

		<?php if (isset($videoId)) { ?>
			<div class="video-wrapper">
				<iframe class="video-intro__iframe" src="<?php echo $videoId ?>" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
			</div>
			<script src="https://player.vimeo.com/api/player.js"></script>
		<?php } ?>
	</div>

</section>