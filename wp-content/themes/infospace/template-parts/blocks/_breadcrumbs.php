<?php $postype = get_post_type(); ?>
<?php $current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
$moduleMeta = get_current_module_meta($current_module_id_global);

$moduleCrummbs = '';
if (isset($moduleMeta['attached_resources']) && !empty($moduleMeta['attached_resources'])) {
    $moduleId = $moduleMeta['attached_resources'];
    $moduleName = get_the_title($moduleId);
    $moduleLink = get_the_permalink($moduleId);
    $moduleCrummbs = '<a href="' . esc_url($moduleLink) . '">' . esc_html($moduleName) . '</a> &gt; ';
}
?>

<div class="yoast-breadcrumbs">
    <?php if ($postype == 'post') { ?>
        <span><a href="/">Home</a> &gt; <?php echo $moduleCrummbs; ?><a href="/news/">News</a> &gt; <span class="breadcrumb_last" aria-current="page"><?php the_title(); ?></span></span>
    <?php } else { ?>
        <?php echo do_shortcode('[wpseo_breadcrumb]'); ?>
    <?php } ?>
</div>