<?php
add_action('after_setup_theme', function() {
	remove_theme_support('core-block-patterns');
    add_action('after_setup_theme', 'removeCorePatterns');
});

register_block_pattern_category(
    'layout',
    array( 'label' => __( 'layout', 'my-plugin' ) )
);

function theme_register_block_patterns() {

if ( class_exists( 'WP_Block_Patterns_Registry' ) ) {

    register_block_pattern(
        'theme/listing-page',
        array(
            'title'       => __( 'Listing page', 'textdomain' ),
            'description' => _x( 'A top level listing page.', 'Block pattern description', 'textdomain' ),
            'content'     => "<!-- wp:theme/page-banner {\"attachmentId\":92,\"backgroundImage\":\"/wp-content/uploads/2021/02/Man-Walking-the-Coast-Footpath-Newquay-Cornwall-1.jpg\",\"backgroundImageSmall\":\"/wp-content/uploads/2021/02/Man-Walking-the-Coast-Footpath-Newquay-Cornwall-1.jpg\",\"backgroundImageMedium\":\"/wp-content/uploads/2021/02/Man-Walking-the-Coast-Footpath-Newquay-Cornwall-1-300x108.jpg\",\"backgroundImageLarge\":\"/wp-content/uploads/2021/02/Man-Walking-the-Coast-Footpath-Newquay-Cornwall-1-1024x369.jpg\"} /-->\n\n<!-- wp:theme/left-intro -->\n<section class=\"wp-block-theme-left-intro left-intro\"><!-- wp:heading {\"textAlign\":\"left\",\"level\":1,\"placeholder\":\"Enter main heading\"} -->\n<h1 class=\"has-text-align-left\">Landing page title</h1>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"align\":\"left\",\"placeholder\":\"Intro text goes here\"} -->\n<p class=\"has-text-align-left\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\n<!-- /wp:paragraph --></section>\n<!-- /wp:theme/left-intro -->\n\n<!-- wp:theme/blue-back-circles {\"backgroundColor\":false,\"bumpPosition\":true,\"topStyle\":true} -->\n<!-- wp:theme/image-text {\"attachmentId\":170,\"backgroundImage\":\"/wp-content/uploads/2021/02/johnathan.jpg\",\"imageAlignment\":true,\"imageSize\":false} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Content header</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:button {\"align\":\"left\"} -->\n<div class=\"wp-block-button alignleft\"><a class=\"wp-block-button__link\" href=\"/recover-with-us/generic-page/\">FIND OUT MORE</a></div>\n<!-- /wp:button -->\n<!-- /wp:theme/image-text -->\n\n<!-- wp:theme/image-text {\"attachmentId\":152,\"backgroundImage\":\"/wp-content/uploads/2021/02/large-circle-1.jpg\",\"imageAlignment\":false,\"imageSize\":false} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Content header</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:button {\"align\":\"left\"} -->\n<div class=\"wp-block-button alignleft\"><a class=\"wp-block-button__link\" href=\"#\">asdasdasd sad asd</a></div>\n<!-- /wp:button -->\n<!-- /wp:theme/image-text -->\n<!-- /wp:theme/blue-back-circles -->",
            'categories'  => array( 'layout' ),
        )
    );
    register_block_pattern(
        'theme/circles_with_blue_background',
        array(
            'title'       => __( 'Four circles with blue background', 'textdomain' ),
            'description' => _x( 'Four circles with blue background.', 'Four circles with blue background', 'textdomain' ),
            'content'     => "
            <!-- wp:theme/blue-back-circles {\"mainHeading\":\"A safe and comfortable environment\",\"strapLine\":\"Our 3 addiction treatment facilities are located just minutes from award winning beaches in the seaside town of Lowestoft, Suffolk. We are well serviced by excellent rail and bus transport links.\",\"backgroundColor\":false,\"bumpPosition\":true} -->\n<!-- wp:theme/small-circles-container {\"className\":\"small-circles-contianer\"} -->\n<section class=\"wp-block-theme-small-circles-container small-circles-container grid-x small-circles-contianer\"><!-- wp:theme/small-circle {\"attachmentId\":170,\"backgroundImage\":\"/wp-content/uploads/2021/02/johnathan-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":4,\"placeholder\":\"Enter heading\"} -->\n<h4>5 minutes from<br>award-winning beaches</h4>\n<!-- /wp:heading -->\n<!-- /wp:theme/small-circle -->\n\n<!-- wp:theme/small-circle {\"attachmentId\":152,\"backgroundImage\":\"/wp-content/uploads/2021/02/large-circle-1-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":4,\"placeholder\":\"Enter heading\"} -->\n<h4>5 minutes from<br>award-winning beaches</h4>\n<!-- /wp:heading -->\n<!-- /wp:theme/small-circle -->\n\n<!-- wp:theme/small-circle {\"attachmentId\":193,\"backgroundImage\":\"/wp-content/uploads/2021/02/sasha-1-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":4,\"placeholder\":\"Enter heading\"} -->\n<h4>5 minutes from<br>award-winning beaches</h4>\n<!-- /wp:heading -->\n<!-- /wp:theme/small-circle -->\n\n<!-- wp:theme/small-circle {\"attachmentId\":131,\"backgroundImage\":\"/wp-content/uploads/2021/02/Beach-Sunset-with-Dog-1251195061_5416x3944-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":4,\"placeholder\":\"Enter heading\"} -->\n<h4>5 minutes from<br>award-winning beaches</h4>\n<!-- /wp:heading -->\n<!-- /wp:theme/small-circle --></section>\n<!-- /wp:theme/small-circles-container -->\n\n<!-- wp:button -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link\">wqwrqwer</a></div>\n<!-- /wp:button -->\n<!-- /wp:theme/blue-back-circles -->",
            'categories'  => array( 'layout' ),
        )
    );
    register_block_pattern(
        'theme/blue_background_with_icon_list',
        array(
            'title'       => __( 'Blue background with icon list', 'textdomain' ),
            'description' => _x( 'Blue background with icon list.', 'Blue background with icon list', 'textdomain' ),
            'content'     => "
            <!-- wp:theme/blue-back-circles {\"mainHeading\":\"Heading goes here\"} -->\n<!-- wp:theme/icon-list-container -->\n<section class=\"wp-block-theme-icon-list-container icon-list-container\"><!-- wp:theme/icon-list-item {\"attachmentId\":19,\"backgroundImage\":\"/wp-content/uploads/2021/02/CQC-Regulated.png\"} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Professional<br>and confidential</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:theme/icon-list-item -->\n\n<!-- wp:theme/icon-list-item {\"attachmentId\":339,\"backgroundImage\":\"/wp-content/uploads/2021/02/Group-6506@2x.png\"} -->\n<!-- wp:heading {\"level\":3} -->\n<h3>Professional<br>and confidential</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:theme/icon-list-item -->\n\n<!-- wp:theme/icon-list-item {\"attachmentId\":11,\"backgroundImage\":\"/wp-content/uploads/2021/02/cropped-favicon.png\"} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Professional<br>and confidential</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:theme/icon-list-item -->\n\n<!-- wp:theme/icon-list-item {\"attachmentId\":341,\"backgroundImage\":\"/wp-content/uploads/2021/02/Group-6509@2x.png\"} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Professional<br>and confidential</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:theme/icon-list-item --></section>\n<!-- /wp:theme/icon-list-container -->\n<!-- /wp:theme/blue-back-circles -->",
            'categories'  => array( 'layout' ),
        )
    );
    register_block_pattern(
        'theme/dark_blue_background_with_three_circles',
        array(
            'title'       => __( 'Dark blue background with 3 circles', 'textdomain' ),
            'description' => _x( 'Dark blue background with 3 circles.', 'Dark blue background with 3 circles', 'textdomain' ),
            'content'     => "
            <!-- wp:theme/blue-back-circles {\"mainHeading\":\"Linking block title\",\"backgroundColor\":true} -->\n<!-- wp:theme/small-circles-container -->\n<section class=\"wp-block-theme-small-circles-container small-circles-container grid-x\"><!-- wp:theme/small-circle {\"attachmentId\":131,\"backgroundImage\":\"/wp-content/uploads/2021/02/Beach-Sunset-with-Dog-1251195061_5416x3944-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":3} -->\n<h3>How soon can I start recovery?</h3>\n<!-- /wp:heading -->\n\n<!-- wp:button {\"align\":\"center\"} -->\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\">Read more</a></div>\n<!-- /wp:button -->\n<!-- /wp:theme/small-circle -->\n\n<!-- wp:theme/small-circle {\"attachmentId\":152,\"backgroundImage\":\"/wp-content/uploads/2021/02/large-circle-1-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":3} -->\n<h3>How soon can I start recovery?</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:button {\"align\":\"center\"} -->\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\" href=\"#\">Read more</a></div>\n<!-- /wp:button -->\n<!-- /wp:theme/small-circle -->\n\n<!-- wp:theme/small-circle {\"attachmentId\":170,\"backgroundImage\":\"/wp-content/uploads/2021/02/johnathan-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":3} -->\n<h3>How soon can I start recovery?</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:button {\"align\":\"center\"} -->\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\" href=\"#\">REad more</a></div>\n<!-- /wp:button -->\n<!-- /wp:theme/small-circle --></section>\n<!-- /wp:theme/small-circles-container -->\n<!-- /wp:theme/blue-back-circles -->",
            'categories'  => array( 'layout' ),
        )
    );
    register_block_pattern(
        'theme/dark_blue_background_with_three_circles',
        array(
            'title'       => __( 'Small circle list with blue background', 'textdomain' ),
            'description' => _x( 'Small circle list with blue background.', 'Small circle list with blue background', 'textdomain' ),
            'content'     => "
            <!-- wp:theme/circle-list-container -->\n<section class=\"wp-block-theme-circle-list-container circle-list-container\"><!-- wp:heading {\"textAlign\":\"center\",\"placeholder\":\"Enter heading\"} -->\n<h2 class=\"has-text-align-center\">Heading goes here</h2>\n<!-- /wp:heading -->\n\n<!-- wp:theme/circle-list-item {\"attachmentId\":131,\"backgroundImage\":\"/wp-content/uploads/2021/02/Beach-Sunset-with-Dog-1251195061_5416x3944-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Subheading goes here</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:theme/circle-list-item -->\n\n<!-- wp:theme/circle-list-item {\"attachmentId\":152,\"backgroundImage\":\"/wp-content/uploads/2021/02/large-circle-1-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Subheading goes here</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:theme/circle-list-item -->\n\n<!-- wp:theme/circle-list-item {\"attachmentId\":193,\"backgroundImage\":\"/wp-content/uploads/2021/02/sasha-1-296x296.jpg\"} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Subheading goes here</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"placeholder\":\"Enter smaller text\"} -->\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:theme/circle-list-item -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"placeholder\":\"Enter strapline\"} -->\n<h3 class=\"has-text-align-center\">Find out more about recovery in our frequently asked questions and resources area.</h3>\n<!-- /wp:heading -->\n\n<!-- wp:button {\"align\":\"center\"} -->\n<div class=\"wp-block-button aligncenter\"><a class=\"wp-block-button__link\" href=\"#\">READ MORE</a></div>\n<!-- /wp:button --></section>\n<!-- /wp:theme/circle-list-container -->",
            'categories'  => array( 'layout' ),
        )
    );
    register_block_pattern(
        'theme/key_facts',
        array(
            'title'       => __( 'Key facts with blue background', 'textdomain' ),
            'description' => _x( 'Key facts with blue background.', 'Key facts with blue background', 'textdomain' ),
            'content'     => "
            <!-- wp:theme/blue-back-circles {\"mainHeading\":\"Key facts about cocaine addiction\"} -->\n<!-- wp:theme/key-facts-container -->\n<section class=\"wp-block-theme-key-facts-container key-facts-container\"><!-- wp:list -->\n<ul><li>Cocaine is a powerful stimulant that can be highly addictive due to the short but intense high that it creates, usually lasting between 5-30 minutes.</li><li>Cocaine and substance addiction is often characterised by changes in behaviour which can affect your relationships both at home and at work, whereby sourcing the drug becomes more important than spending time with loved ones, or pursuing activities you previously enjoyed.</li><li>It is widely accepted that there is no definitive cause of substance abuse, including cocaine. Moreover, it is more likely that genetic and environmental factors can increase the likelihood of you developing a cocaine addiction.</li><li>If you or a loved one is addicted to cocaine, some of the behavioural signs to look out for include rapid talking during conversations, where moving quickly from topic to topic is common, while items used in regular cocaine use such as mirrors, razor blades, tightly rolled bank notes, glass, metal or plastic straws can be signs of cocaine abuse.</li><li>If you or a loved one is addicted to cocaine, some of the behavioural signs to look out for include rapid talking during conversations, where moving quickly from topic to topic is common, while items used in regular cocaine use such as mirrors, razor blades, tightly rolled bank notes, glass, metal or plastic straws can be signs of cocaine abuse.</li></ul>\n<!-- /wp:list --></section>\n<!-- /wp:theme/key-facts-container -->\n<!-- /wp:theme/blue-back-circles -->",
            'categories'  => array( 'layout' ),
        )
    );
    register_block_pattern(
        'theme/blue_background_with_image_text',
        array(
            'title'       => __( 'Blue background with image and text', 'textdomain' ),
            'description' => _x( 'Blue background with image and text.', 'Blue background with image and text', 'textdomain' ),
            'content'     => "
            <!-- wp:theme/blue-back-circles {\"backgroundColor\":true} -->\n<!-- wp:theme/image-text {\"attachmentId\":170,\"backgroundImage\":\"/wp-content/uploads/2021/02/johnathan.jpg\",\"imageSize\":true} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Speak to us today to arrange your private tour of our centre</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>We offer EVERY client and their family members the opportunity to come and visit our Recovery Centre and Residences without obligation. By appointment, we will arrange for a guided viewing with a member of staff. This can be useful in helping clients and family members overcome the anxieties and fears they may have regarding addiction treatment. Contact Kelly or Julie in the Assessment and Admissions Team to arrange your visit.</p>\n<!-- /wp:paragraph -->\n<!-- /wp:theme/image-text -->\n<!-- /wp:theme/blue-back-circles -->",
            'categories'  => array( 'layout' ),
        )
    );
    register_block_pattern(
        'theme/single_image_text',
        array(
            'title'       => __( 'Image and text', 'textdomain' ),
            'description' => _x( 'Image and text.', 'Image and text', 'textdomain' ),
            'content'     => "
            <!-- wp:theme/image-text {\"attachmentId\":131,\"backgroundImage\":\"http://wordpress-175940-1798043.cloudwaysapps.com/wp-content/uploads/2021/02/Beach-Sunset-with-Dog-1251195061_5416x3944-scaled.jpg\",\"imageAlignment\":true,\"imageSize\":true} -->\n<!-- wp:heading {\"level\":3,\"placeholder\":\"Enter heading\"} -->\n<h3>Step one</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p><strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</strong></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:button {\"align\":\"left\"} -->\n<div class=\"wp-block-button alignleft\"><a class=\"wp-block-button__link\" href=\"#\">Optional CTA</a></div>\n<!-- /wp:button -->\n<!-- /wp:theme/image-text -->",
            'categories'  => array( 'layout' ),
        )
    );

}

}
add_action( 'init', 'theme_register_block_patterns' );