<?php $block_attributes = get_query_var('attributes'); ?>
<?php //$block_content = get_query_var('content'); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($block_attributes);

$blockHeading = (array_key_exists('mainHeading', $block_attributes)) ? $block_attributes['mainHeading'] : 'SHARE';
?>

<section class="share-block">
    <h2><?php echo $blockHeading ?></h2>
    <div class="socials">
        <?php get_template_part('template-parts/share') ?>
    </div>
</section>