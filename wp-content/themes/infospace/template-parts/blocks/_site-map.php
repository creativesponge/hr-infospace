<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>

<div class="site-map">
    <?php 

    ?>
    <?php $block_attributes = get_query_var('attributes'); ?>

    <h1>Site map</h1>


    <?php
    echo $block_content;


    ?>
    <?php //startertheme_footer_nav(); ?>
    <?php //startertheme_top_bar_r(); 
    ?>
    <?php
    //startertheme_account_nav();
    ?>
    <?php //get_template_part('template-parts/mobile-top-bar'); 
    ?>
</div>