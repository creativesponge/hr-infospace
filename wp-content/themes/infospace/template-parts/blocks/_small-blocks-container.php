<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_content); ?>
<?php //$meta = theme_get_meta(); ?>

<section class="small-blocks-container full-width">
    <div class="small-blocks-grid">
        <?php echo $block_content; ?>
    </div>
</section>
