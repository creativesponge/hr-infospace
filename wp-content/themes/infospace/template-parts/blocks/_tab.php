<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); ?>
<?php //print_r($block_content); ?>

<?php
    $tabHeading = (array_key_exists('tabHeading', $block_attributes)) ? $block_attributes['tabHeading'] : '' ;
?>
<?php if ($tabHeading) { ?>
<div class="tabs__tab" role="tabpanel" data-tabs-heading="<?php echo $tabHeading; ?>" aria-hidden="true">

    <div class="tab__heading">
        <?php  echo "<h3>".$tabHeading."</h3>"; ?>
    </div>
    
    <?php echo $block_content; ?>
</div>
<?php }