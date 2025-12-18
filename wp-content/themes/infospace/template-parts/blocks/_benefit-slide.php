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
$attachmentIdMob = (array_key_exists('attachmentIdMob', $block_attributes)) ? $block_attributes['attachmentIdMob'] : $imageId;
$pickedColour = (array_key_exists('pickedColour', $block_attributes)) ? $block_attributes['pickedColour'] : '';
?>
<section class="benefit-slide">

    <div class="benefit-slide__container" style="background-color: <?php echo esc_attr($pickedColour); ?>;">
        <?php if ($imageId) { ?>
            <div class="benefit-slide__image">
                 <?php echo wp_get_attachment_image($imageId, 'benefit', '', ["class" => "wp-image-$imageId"]); ?>
            </div>
        <?php } ?>
        <div class="benefit-slide__content">
            <div class="benefit-slide__text">
                <?php echo $block_content; ?>
            </div>
        </div>


    </div>

</section>