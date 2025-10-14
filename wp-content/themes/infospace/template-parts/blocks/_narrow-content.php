<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>

<?php 
$contentWidthClass = (isset($block_attributes['contentWidth']) && $block_attributes['contentWidth'] == 1) ? " very-narrow" : "";
?> 

<div class="narrow-content<?php echo $contentWidthClass; ?>">
        <?php echo $block_content; ?>
</div>