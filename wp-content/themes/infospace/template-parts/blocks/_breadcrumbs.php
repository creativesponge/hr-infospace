<?php $postype = get_post_type(); ?>
<div class="yoast-breadcrumbs full-width">
    <?php if ($postype == 'post') { ?>
        <span><a href="/news/">News</a> &gt; <span class="breadcrumb_last" aria-current="page"><?php the_title(); ?></span></span>
    <?php } else if ($postype == 'vacancy') { ?>
        <span><a href="<?php echo get_the_permalink(685); ?>"><?php echo get_the_title(685); ?></a> &gt; <span class="breadcrumb_last" aria-current="page"><?php the_title(); ?></span></span>
    <?php } else { ?>
        <?php echo do_shortcode('[wpseo_breadcrumb]'); ?>
    <?php } ?>
</div>