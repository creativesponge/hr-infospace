<?php

/**
 * Register block templates
 **/

function theme_register_template()
{
  $post_type_object = get_post_type_object('page');
  $post_type_object->template = array(
    array('theme/page-banner-titles'),
  );

  $post_type_object = get_post_type_object('job');
  $post_type_object->template = array(
    array('theme/page-banner-titles'),
    //array('acf/narrow-content', array('data' => array('heading' => 'Test!')), array()),
    /*array('theme/purple-background', array(
      'align' => "center",
  ),array('theme/image-text', array(
        'imageAlignment' => '1'
        )
    ),
     array(
      array('core/heading'),
      array('core/paragraph', array(), array()),
      array('core/button', array(), array()),
    )),
    */
  );
 
}
add_action('init', 'theme_register_template');
