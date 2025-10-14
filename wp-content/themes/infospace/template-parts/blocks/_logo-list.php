<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php $layoutClass = (isset($block_attributes['gridLayout']) && $block_attributes['gridLayout'] == 1) ? " logo-list--five-across" : ""; ?>

<section class="logo-list full-width<?php echo $layoutClass ?>">

    <div class="logo-list__content">
        <?php echo $block_content; ?>
    </div>
 
</section>