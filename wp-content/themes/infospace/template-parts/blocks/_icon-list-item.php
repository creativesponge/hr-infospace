<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_content); ?>
<?php //$meta = theme_get_meta(); ?>
<?php //print_r($meta); ?>
<?php 
    $imageId = (array_key_exists('attachmentId', $block_attributes)) ? $block_attributes['attachmentId'] : '' ;
?>


<div class="icon-list-item">

<?php if ($imageId) { ?>
    <div class="icon-list-item__image">
        <?php echo wp_get_attachment_image( $imageId, 'smallsquare' ); ?>
    </div>
 <?php } ?>

    <div class="icon-list-item__content">
        <?php echo $block_content; ?>
    </div>

</div>
