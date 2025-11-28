<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>


<section class="image-text-carousel-list">

    <div class="image-text-carousel__carousel">
        <?php echo $block_content; ?>
    </div>

</section>