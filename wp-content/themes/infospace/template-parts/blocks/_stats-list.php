<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>

<section class="stats-list">
    <div class="mobile-only-carousel">
        <?php echo $block_content; ?>
    </div>
</section>