<?php

$module = get_query_var('module');
if (!$module || !isset($module->ID)) {
    return;
}

$module_id = $module->ID;
$meta = theme_get_meta($module_id);

// Check if meta exists
if (!$meta) {
    return;
}

$module_desc = isset($meta->module_description) ? '<p>' . esc_html($meta->module_description) . '</p>' : '';
$module_thumb = isset($meta->module_thumbnail) ? $meta->module_thumbnail : '';
$module_color = isset($meta->module_color) ? esc_attr($meta->module_color) : '';
$attached_resource = isset($meta->module_attached_resources) ? $meta->module_attached_resources : '';
$mobileImage = isset($meta->listing_image_mobile_id) ? $meta->listing_image_mobile_id : '';
if (!$attached_resource) {
    return;
}

// Check if attached resource post exists
if (!get_post($attached_resource)) {
    return;
}

// Check if module post exists
if (!get_post($module_id)) {
    return;
}

?>

<div class="small-12 medium-4 module-teaser module-<?php echo esc_attr($module_id); ?>">
    <div class="module-teaser__target" style="background-color: <?php echo $module_color; ?>;">
        <div class="module-teaser__image">
            <a href="<?php echo esc_url(get_permalink($attached_resource)); ?>">
                <?php
                $thumbnail = get_the_post_thumbnail($module_id, 'service', ['class' => 'show-for-medium']);
                $thumbnailMob = isset($mobileImage) && $mobileImage ? wp_get_attachment_image($mobileImage, 'modulemob', ['class' => 'hide-for-medium']) : get_the_post_thumbnail($module_id, 'service', ['class' => 'hide-for-medium']);
                echo $thumbnail ? $thumbnail : '';
                echo $thumbnailMob ? $thumbnailMob : '';
                set_query_var('module_color', $module_color);
                ?>
                <?php get_template_part('template-parts/svgs/_module-mask'); ?>
                <?php get_template_part('template-parts/svgs/_module-mask2'); ?>
                <?php get_template_part('template-parts/svgs/_module-mask3'); ?>
                <?php get_template_part('template-parts/svgs/_module-mask-tablet'); ?>
                <?php get_template_part('template-parts/svgs/_module-mask-mobile'); ?>
            </a>
        </div>

        <div class="module-teaser__content">
            <h2><a href="<?php echo esc_url(get_permalink($attached_resource)); ?>"><?php echo esc_html(get_the_title($module_id)); ?></a></h2>
            <?php echo $module_desc; ?>
            <a href="<?php echo esc_url(get_permalink($attached_resource)); ?>" class="button-link" style="color: <?php echo $module_color; ?>;">
                <?php _e('Enter', 'hrinfospace'); ?>
            </a>
        </div>
    </div>
</div>