<?php

/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *

 */
global $settings;
global  $prefix;

$siteEmail = isset($settings[$prefix . 'email']) ? $settings[$prefix . 'email'] : '';
$sitePhone = isset($settings[$prefix . 'phone']) ? $settings[$prefix . 'phone'] : '';

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<a href="#contentskip" class="screen-reader-text" accesskey="s">Jump to content</a>
	<div class="off-canvas-wrapper">
		<div class="off-canvas" id="off-canvas" aria-hidden="true" data-off-canvas>
			<?php get_template_part('template-parts/mobile-top-bar'); ?>
			<?php if ($siteEmail) { ?>
				<a href="mailto:<?php echo $siteEmail ?>"><?php echo $siteEmail ?></a><br />
			<?php } ?>
			<?php if ($sitePhone) { ?>
				<a href="tel:<?php echo preg_replace('/\s+/', '', $sitePhone); ?>"><?php echo $sitePhone ?></a>
			<?php } ?>
		</div>

		<header class="header">
			<div class="grid-x grid-padding-x">
				<div class="cell auto logo">
					<a accesskey="1" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Home link">
						<?php get_template_part('template-parts/svgs/_logo') ?>
					</a>
				</div>
				<div class="cell auto">
					<div class="cell medium-3 show-for-large header__contacts">
						<?php if ($siteEmail) { ?>
							<a href="mailto:<?php echo $siteEmail ?>"><?php echo $siteEmail ?></a><br />
						<?php } ?>
						<?php if ($sitePhone) { ?>
							<a href="tel:<?php echo preg_replace('/\s+/', '', $sitePhone); ?>"><?php echo $sitePhone ?></a>
						<?php } ?>
					</div>
					<nav class="cell auto site-navigation top-bar" role="navigation" id="off-canvas-menu">
						<div class="top-bar-right">
							<?php startertheme_top_bar_r(); ?>
						</div>
					</nav>
				</div>

				<div class="cell auto hide-for-medium">
					<button type="button" class="button mobile-menu-toggle" data-toggle="off-canvas" aria-label="Mobile navigation" aria-controls="off-canvas">
						<i class="show-for-sr">Menu</i>
						<span></span>
						<span></span>
						<span></span>
					</button>
				</div>
			</div>

		</header>