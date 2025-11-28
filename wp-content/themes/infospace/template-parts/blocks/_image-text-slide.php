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

?>
<section class="image-text-slide">

    <div class="image-text-slide__container">
        <?php if ($imageId) { ?>
            <div class="image-text-slide__image">
                <?php echo wp_get_attachment_image($imageId, 'imagetext', '', ["class" => "wp-image-$imageId"]); ?>
            </div>
        <?php } ?>
        <div class="image-text-slide__content">
            <div class="image-text-slide__text">
                <?php echo $block_content; ?>
            </div>
        </div>


    </div>

</section>