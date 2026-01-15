<?php

/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *

 */

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
$post_id = get_the_ID();
if ($post_id === 1728) {
	$_SESSION['current_module_id'] = null;
} else {
	$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
}
get_current_module_meta(null);
$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
//$current_user_favourites = isset($_SESSION['current_user_favourite_ids']) ? $_SESSION['current_user_favourite_id'] : '';

global $settings;
global $prefix;
global $resource_pages;
$siteEmail = isset($settings[$prefix . 'email']) ? $settings[$prefix . 'email'] : '';
$sitePhone = isset($settings[$prefix . 'phone']) ? $settings[$prefix . 'phone'] : '';
$moduleMeta = get_current_module_meta($current_module_id_global);
$moduleColour = isset($moduleMeta['module_color']) ? $moduleMeta['module_color'] : '';
$post_type = get_post_type();
if ((get_the_ID() === 1581 && $current_module_id_global === '') || ($post_type === 'post' && $current_module_id_global === '')) { // redirect for news page if not logged in
	wp_redirect(home_url('/'));
	exit;
}

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
	<style>
		a {
			color: <?php echo esc_html($moduleColour); ?>;
		}

		.add-to-favourites {
			background-color: <?php echo esc_html($moduleColour); ?>;
		}

		.resource-page__header .add-to-favourites {

			color: <?php echo esc_html($moduleColour); ?>;
		}

		.resource-page__header .add-to-favourites svg path {
			stroke: <?php echo esc_html($moduleColour); ?>;
		}

		.yoast-breadcrumbs a {
			color: <?php echo esc_html($moduleColour); ?>;
		}

		.arrow-link::before {
			background: <?php echo esc_html($moduleColour); ?>;
		}

		.arrow-link::after {
			border-color: <?php echo esc_html($moduleColour); ?>;
		}

		.module-panel__news-grid .cell:first-child {
			border-right-color: <?php echo esc_html($moduleColour); ?>;
		}

		.resource-module__news-teaser {
			border-bottom-color: <?php echo esc_html($moduleColour); ?>;
		}

		.dropdown.menu>li.is-active>a:after {
			background: <?php echo esc_html($moduleColour); ?>;
		}

		.header__menu>li a:hover:after {
			background: <?php echo esc_html($moduleColour); ?>;
		}

		.button.mobile-menu-toggle span {
			background: <?php echo esc_html($moduleColour); ?>;
		}
		.tab-list {
			border-color: <?php echo esc_html($moduleColour); ?>;
		}

		.tab-list__background {
			background: <?php echo esc_html($moduleColour); ?>;
		}

		.tab-list__outline {
			border-color: <?php echo esc_html($moduleColour); ?>;
		}

		.resource-page__attachments {
			border-top-color: <?php echo esc_html($moduleColour); ?>;
		}

		.pagination li a.current,
		.pagination li span.current,
		.pagination li.ellipsis.current {
			color: <?php echo esc_html($moduleColour); ?>;
		}


		.module-menu ul li.is-dropdown-submenu-parent button.drop-down__more {

			color: <?php echo esc_html($moduleColour); ?>;

		}

		.module-menu ul li.is-dropdown-submenu-parent button.drop-down__more:before {
			background: <?php echo esc_html($moduleColour); ?>;
		}

		.module-menu ul li.is-dropdown-submenu-parent button.drop-down__more:after {
			border-color: <?php echo esc_html($moduleColour); ?>;
		}

		.add-to-favourites.add-to-favourites--filled svg path {
			fill: <?php echo esc_html($moduleColour); ?>;
			stroke: <?php echo esc_html($moduleColour); ?>;
		}
	</style>
</head>

<body <?php body_class(); ?>>

	<a href="#contentskip" class="screen-reader-text" accesskey="s">Jump to content</a>
	<div class="off-canvas-wrapper">
		<div class="off-canvas" id="off-canvas" aria-hidden="true" data-off-canvas style="background-color:<?php echo esc_html($moduleColour); ?>;">
			<?php get_template_part('template-parts/mobile-top-bar'); ?>

		</div>

		<header class="header">
			<div class="header__container">
				<div class="header__logo">
					<a accesskey="1" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Home link">
						<?php get_template_part('template-parts/svgs/_logo') ?>
					</a>
				</div>
				<div class="header__nav">
					<nav class="site-navigation header__top-bar" role="navigation" id="off-canvas-menu">

						<?php if (is_user_logged_in() && ((get_post_type() == 'resource_page' || get_post_type() == 'post') && $current_module_id_global != null) || (in_array(get_the_ID(), $resource_pages) && $current_module_id_global != null)) : ?>
							<?php get_search_form(); ?>
						<?php endif; ?>

						<?php startertheme_top_bar_r(); ?>

					</nav>
					<?php if (is_user_logged_in() && $current_module_id_global !== '') : ?>
						<div class="header__mobile-toggle">
							<button type="button" class="button mobile-menu-toggle" data-toggle="off-canvas" aria-label="Mobile navigation" aria-controls="off-canvas">
								<i>Menu</i>
								<span></span>
								<span></span>
								<span></span>
							</button>
						</div>
					<?php endif; ?>
					<nav class="site-navigation account-nav" role="navigation" id="account-navigation">
						<div class="account-nav-right">

							<?php if (is_user_logged_in()) : ?>
								<?php startertheme_account_nav($moduleColour); ?>
							<?php else : ?>
								<ul id="menu-my-account-navigation" class="dropdown menu desktop-menu header__account-menu is-login" style="background-color:;" data-dropdown-menu="">
									<li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children menu-item-1938 is-dropdown-submenu-parent opens-left"><a href="/accounts/" aria-haspopup="true" aria-expanded="false"><span>My account</span><img width="14" height="16" src="/wp-content/uploads/2025/12/person.svg" class="menu-item-image" alt="" decoding="async"></a><button class="show-on-focus" aria-expanded="false"><span class="show-for-sr">show submenu for “My account”</span></button>
										<ul class="dropdown menu vertical is-dropdown-submenu" data-toggle="">
											<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-1820 is-active"><a href="/"><span>Home</span><img width="28" height="13" src="/wp-content/uploads/2025/10/home.svg" class="menu-item-image" alt="" decoding="async"></a></li>

											<li class="menu-item is-login"><a href="/wp-login.php">Log in<img width="18" height="14" src="/wp-content/uploads/2025/10/signout.svg" class="menu-item-image" alt="" decoding="async"></a></li>
										</ul>
									</li>
								</ul>
							<?php endif; ?>
						</div>
					</nav>
				</div>


			</div>

		</header>