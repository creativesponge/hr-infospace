<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content'); ?>
<?php //print_r($block_attributes); 
?>
<?php //$meta = theme_get_meta(); 
?>
<?php //print_r($meta); 
?>

<?php
/*
* Template Name: Login Page
*/

//session_start();

//get_header();

if (!isset($_SERVER['HTTP_REFERER']) && !isset($_SESSION['redirect_url'])) {
    // Referrer provided, store in session in the case of a failed login
    //$_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER'];
} elseif (!isset($_SESSION['redirect_url'])) {
    // No referrer or URL stored in session, redirect to home
    //$_SESSION['redirect_url'] = site_url();
}
// Check if user just logged out
$attributes['logged_out'] = isset($_REQUEST['logged_out']) && $_REQUEST['logged_out'] == true;

// Set up login form
//$args = array('redirect' => $_SESSION['redirect_url']);

// Check if user just updated password
$attributes['password_updated'] = isset($_REQUEST['password']) && $_REQUEST['password'] == 'changed';


// Error messages
$errors = array();
if (isset($_REQUEST['login'])) {
    $error_codes = explode(',', $_REQUEST['login']);

    foreach ($error_codes as $code) {
        $errors[] = get_error_message($code);
    }
}
$attributes['errors'] = $errors;

?>

<section class="globe-cta ">

    <div class="globe-cta__container">

        <div class="globe-cta__content">

            <div class="globe-cta__text">


                <?php echo $block_content; ?>
            </div>
            <?php get_template_part('template-parts/svgs/_globe') ?>
        </div>


        <div class="globe-cta__right">


        </div>
        <?php get_template_part('template-parts/svgs/_globe') ?>
    </div>


    </div>




</section>