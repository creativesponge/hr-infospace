<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //print_r($block_content); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>

<?php
$mainHeading = (array_key_exists('mainHeading', $block_attributes)) ? $block_attributes['mainHeading'] : '';
$randId = uniqid('accord_');
$randId3 = uniqid('label_');
 ?>
<?php if ($mainHeading) { ?>
    <div class="accordion-block-item full-width">
        <dl class="accordion">
            <dt><a id="<?php echo $randId3 ?>" href="#" aria-controls="<?php echo $randId; ?>" aria-expanded="true" >
                    <?php echo $mainHeading; ?>
                </a>
            </dt>
            <dd id="<?php echo $randId; ?>" class="accordion-block-copy active" aria-labelledby="<?php echo $randId3 ?>">
                <div class="accordion-block-container">
                    <?php echo $block_content; ?>
                </div>
            </dd>
        </dl>
    </div>
<?php }