<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php $blockHeading = (array_key_exists('mainHeading', $block_attributes)) ? $block_attributes['mainHeading'] : '';

?>


<section class="benefit-carousel-list full-width">
<?php if (isset($blockHeading)) {
        echo "<h2>" . $blockHeading . "</h2>";
    } ?>
    <div class="benefit-carousel__carousel">
        <?php echo $block_content; ?>
    </div>
<div class="button-row">
  <button class="button button--previous">
    <svg class="flickity-button-icon" viewBox="0 0 100 100"><title>Previous</title><path d="M 10, 50
    L 60, 100
    L 70, 90
    L 30, 50
    L 70, 10
    L 60, 0
    Z" class="arrow" transform="translate(100, 100) rotate(180)"></path></svg></button>
  <div class="button-group button-group--cells flickity-page-dots">
    
  </div>
  <button class="button button--next "><svg class="flickity-button-icon" viewBox="0 0 100 100"><title>Next</title><path d="M 10, 50
    L 60, 100
    L 70, 90
    L 30, 50
    L 70, 10
    L 60, 0
    Z" class="arrow" transform="translate(100, 100) rotate(180)"></path></svg></button>
</div>
</section>