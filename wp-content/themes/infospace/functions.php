<?php

/**
 *
 * Functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development

 */
/** Global variables */
global $_SESSION;
$prefix = 'theme_fields';
$namespace = 'theme_name';
$current_module_slug_global = '';
$site_url = get_site_url();

 $resource_pages = array(1786, 1581, 1585);

if ($site_url == 'https://hr-infospace:8890' || $site_url == 'https://localhost:3000') {
    // dev
    $finance_page = 1737;
    $hr_page = 1735;
    $hsafety_page = 1739;
} else {
    // live
    $finance_page = 5548;
    $hr_page = 5516;
    $hsafety_page = 5401;
}


/** Various clean up functions */
require_once('library/cleanup.php');

/** Required for Foundation to work properly */
require_once('library/foundation.php');

/** Format comments */
require_once('library/class-comments.php');

/** Register all navigation menus */
require_once('library/navigation.php');

/** Add menu walkers for top-bar and off-canvas */
require_once('library/class-top-bar-walker.php');
require_once('library/class-mobile-walker.php');
require_once('library/class-account-walker.php');

/** Create widget areas in sidebar and footer */
//require_once( 'library/widget-areas.php' );

/** Enqueue scripts */
require_once('library/enqueue-scripts.php');

/** Add theme support */
require_once('library/theme-support.php');

/** Change WP's sticky post class */
require_once('library/sticky-posts.php');

/** Configure responsive image sizes */
require_once('library/responsive-images.php');

/** Gutenberg editor support */
require_once('library/gutenberg.php');

/** Theme helper */
require_once('library/helpers/helpers.php');
/** include CMB2 fields */

require_once('library/helpers/custom-post-types.php');

/** include CMB2 fields */
require_once('library/helpers/custom-meta-fields.php');

/** include new Roles */
require_once('library/helpers/custom-roles.php');

/** Ajax */
require_once dirname(__FILE__) . '/library/_ajax.php';

/** Blocks */
require_once dirname(__FILE__) . '/library/_blocks.php';
require_once dirname(__FILE__) . '/library/blocks/_accordion.php';
require_once dirname(__FILE__) . '/library/blocks/_page-banner-titles.php';
require_once dirname(__FILE__) . '/library/blocks/_image-text.php';
require_once dirname(__FILE__) . '/library/blocks/_contact-form.php';
require_once dirname(__FILE__) . '/library/blocks/_video.php';
require_once dirname(__FILE__) . '/library/blocks/_google-map.php';
require_once dirname(__FILE__) . '/library/blocks/_tab.php';
require_once dirname(__FILE__) . '/library/blocks/_tabs-container.php';
require_once dirname(__FILE__) . '/library/blocks/_testimonials-carousel.php';
require_once dirname(__FILE__) . '/library/blocks/_teams.php';
require_once dirname(__FILE__) . '/library/blocks/_grey-background.php';
require_once dirname(__FILE__) . '/library/blocks/_posts-list.php';
require_once dirname(__FILE__) . '/library/blocks/_posts-list-filters-ajax.php';
require_once dirname(__FILE__) . '/library/blocks/_latest-posts.php';
require_once dirname(__FILE__) . '/library/blocks/_share-block.php';
require_once dirname(__FILE__) . '/library/blocks/_small-block.php';
require_once dirname(__FILE__) . '/library/blocks/_small-blocks-container.php';
require_once dirname(__FILE__) . '/library/blocks/_stats-list.php';
require_once dirname(__FILE__) . '/library/blocks/_stats-item.php';
require_once dirname(__FILE__) . '/library/blocks/_breadcrumbs.php';
require_once dirname(__FILE__) . '/library/blocks/_logo-list.php';
require_once dirname(__FILE__) . '/library/blocks/_narrow-content.php';

require_once dirname(__FILE__) . '/library/blocks/_image-text-carousel.php';
require_once dirname(__FILE__) . '/library/blocks/_image-text-banner.php';
require_once dirname(__FILE__) . '/library/blocks/_image-text-slide.php';
require_once dirname(__FILE__) . '/library/blocks/_services-carousel.php';
require_once dirname(__FILE__) . '/library/blocks/_services-slide.php';
require_once dirname(__FILE__) . '/library/blocks/_login-register.php';
require_once dirname(__FILE__) . '/library/blocks/_video-tour.php';
require_once dirname(__FILE__) . '/library/blocks/_modules-list.php';
require_once dirname(__FILE__) . '/library/blocks/_posts-list-filters.php';
require_once dirname(__FILE__) . '/library/blocks/_search-results.php';
require_once dirname(__FILE__) . '/library/blocks/_all-favourites.php';
require_once dirname(__FILE__) . '/library/blocks/_all-newsletters.php';
require_once dirname(__FILE__) . '/library/blocks/_account-settings.php';
require_once dirname(__FILE__) . '/library/blocks/_accept-terms.php';
require_once dirname(__FILE__) . '/library/blocks/_accept-privacy.php';
require_once dirname(__FILE__) . '/library/blocks/_forgot-password.php';
require_once dirname(__FILE__) . '/library/blocks/_site-map.php';
require_once dirname(__FILE__) . '/library/blocks/_icon-list.php';
require_once dirname(__FILE__) . '/library/blocks/_icon-list-item.php';
require_once dirname(__FILE__) . '/library/blocks/_benefit-carousel.php';
require_once dirname(__FILE__) . '/library/blocks/_benefit-slide.php';
require_once dirname(__FILE__) . '/library/blocks/_globe-cta.php';
require_once dirname(__FILE__) . '/library/blocks/_page-contact.php';
require_once dirname(__FILE__) . '/library/blocks/_custom-login.php';


/** Infospace functions */
require_once dirname(__FILE__) . '/library/import-existing-database.php';
require_once dirname(__FILE__) . '/library/cron-functions.php';
require_once dirname(__FILE__) . '/library/page-permissions.php';
require_once dirname(__FILE__) . '/library/logging-functions.php';
require_once dirname(__FILE__) . '/library/module-filters.php';
require_once dirname(__FILE__) . '/library/autocomplete.php';
require_once dirname(__FILE__) . '/library/favourite.php';
require_once dirname(__FILE__) . '/library/handle_user_registration.php';
require_once dirname(__FILE__) . '/library/login-process.php';


/** Stats */
require_once dirname(__FILE__) . '/library/stats/stats-pages-list.php';
require_once dirname(__FILE__) . '/library/stats/stats-downloads.php';
require_once dirname(__FILE__) . '/library/stats/stats-page-views.php';
require_once dirname(__FILE__) . '/library/stats/stats-newsletters.php';
require_once dirname(__FILE__) . '/library/stats/stats-logins.php';


/** Alerts */
require_once dirname(__FILE__) . '/library/alerts/alerts.php';

/** If your site requires protocol relative url's for theme assets, uncomment the line below */
require_once('library/class-protocol-relative-theme-assets.php');
