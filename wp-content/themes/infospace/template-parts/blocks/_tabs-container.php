<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); ?>
<?php //$meta = theme_get_meta(); ?>
<?php //print_r($meta); ?>

<?php
    $tabSectionHeading = (array_key_exists('tabsSectionHeading', $block_attributes)) ? $block_attributes['tabsSectionHeading'] : '' ;
?>

<section class="tabs-container">

    <div>
        <?php if ($tabSectionHeading) {
            echo "<h2 class='tabs__title'>".$tabSectionHeading."</h2>";
        } ?>
    </div>
    <ul class="tabs__list" role="tablist">
      
    </ul>
    <div class="tabs-container__content">
        <?php echo $block_content; ?>
    </div>

</section>