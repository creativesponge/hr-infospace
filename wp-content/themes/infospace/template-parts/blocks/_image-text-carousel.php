<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>

<?php
$blockHeading = (array_key_exists('mainHeading', $block_attributes)) ? $block_attributes['mainHeading'] : '' ;

$strapLine = (array_key_exists('strapLine', $block_attributes)) ? $block_attributes['strapLine'] : '' ;

?>
<section class="image-text-carousel-list">
    <div class="grid-container">

        <div class="">
        <?php if (isset($blockHeading)) {
			echo "<h3>".$blockHeading."</h3>";
		} ?>
        <?php if (isset($strapLine)) {
	 		echo "<h4>".$strapLine."</h4>";
	 	 } ?>
        </div>

    </div>

    <div class="image-text-carousel__carousel">
        <?php echo $block_content; ?>
    </div>

</section>