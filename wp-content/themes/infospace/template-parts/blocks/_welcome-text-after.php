<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>

<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($block_attributes); 
?>

<section class="welcome-back__after-text">
    <?php echo $block_content; ?>
</section>