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

?>
<section class="services-slide">

    <div class="services-slide__container">
        <?php if ($imageId) { ?>
            <div class="services-slide__image show-for-large ">
                <?php echo wp_get_attachment_image($imageId, 'service', '', ["class" => "wp-image-$imageId"]); ?>
                 <?php get_template_part('template-parts/svgs/_service-mask-desktop') ?>
            </div>
            <div class="services-slide__image show-for-medium hide-for-large">
                <?php echo wp_get_attachment_image($imageId, 'servicetab', '', ["class" => "wp-image-$imageId"]); ?>
                 <?php get_template_part('template-parts/svgs/_service-mask-tablet') ?>
            </div>
            
            <div class="services-slide__image hide-for-medium ">
                <?php if ($attachmentIdMob) {
                    echo wp_get_attachment_image($attachmentIdMob, 'servicemob', '', ["class" => "wp-image-$attachmentIdMob"]);
                } ?>
                <?php get_template_part('template-parts/svgs/_service-mask-mobile') ?>
            </div>
        <?php } ?>
        <div class="services-slide__content">
            <div class="services-slide__text">
                <?php echo $block_content; ?>
            </div>
        </div>


    </div>

</section>