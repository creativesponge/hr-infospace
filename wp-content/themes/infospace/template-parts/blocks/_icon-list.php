<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_content); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php
$blockHeading = (array_key_exists('mainHeading', $block_attributes)) ? $block_attributes['mainHeading'] : '';
?>
<section class="icon-list full-width">

    <div class="icon-list__container">

        <?php if ($blockHeading) { ?>
            <h2><?php echo $blockHeading ?></h2>
        <?php } ?>
        <div class="icon-list__grid">
            <?php echo $block_content; ?>
        </div>
    </div>
</section>