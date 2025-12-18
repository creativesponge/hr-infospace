<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_content); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php $blockHeading = (array_key_exists('mainHeading', $block_attributes)) ? $block_attributes['mainHeading'] : '';
$footerText = (array_key_exists('footerText', $block_attributes)) ? $block_attributes['footerText'] : '';
?>
<section class="small-blocks-container full-width">
    <?php if (isset($blockHeading)) {
        echo "<h2>" . $blockHeading . "</h2>";
    } ?>
    <div class="small-blocks-grid">
        <?php echo $block_content; ?>
    </div>
    <?php if (isset($footerText)) { ?>
        <div class="small-blocks__footer">
            <?php echo "<p>" . $footerText . "</p>"; ?>
        </div>
    <?php
    } ?>
</section>