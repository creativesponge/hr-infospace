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
$args = array(
    'label_username' => __('Email'),
);

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

<section class="login-register ">
    <div class="login-register__nav">
        <button class="login-register__nav-login">
            Login
        </button>
        <button class="login-register__nav-reg">
            Register
        </button>
    </div>
    <div class="login-register__container">

        <div class="login-register__content login-register__panel active">

            <div class="login-register__text">


                <h3>Already have an account?</h3>


                <p>
                    <?php if ($attributes['password_updated']) : ?>
                        Your password has been changed. You can sign in now.
                    <?php elseif ($attributes['logged_out']) : ?>
                        You have signed out. Would you like to sign in again?
                    <?php endif; ?>
                </p>
                <!-- Show errors if there are any -->
                <?php if (count($attributes['errors']) > 0) : ?>
                    <?php foreach ($attributes['errors'] as $error) : ?>
                        <p class="login-error">
                            <?php echo $error; ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php wp_login_form($args); ?>
                 <a href="<?php echo site_url('/forgot-password/'); ?>" class="forgot-password-link">Forgotten your password?</a>

            </div>
            <?php get_template_part('template-parts/svgs/_globe') ?>
        </div>


        <div class="login-register__register login-register__panel active">
            <?php echo $block_content; ?>
            <?php get_template_part('template-parts/svgs/_globe') ?>
        </div>
    </div>


    </div>




</section>