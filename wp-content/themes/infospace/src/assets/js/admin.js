// Import custom modules and init

import stats from "./lib/admin/blocks/stats/index.js";
import stat from "./lib/admin/blocks/stat/index.js";
import image_text from "./lib/admin/blocks/image-text/index.js";
import accordion from "./lib/admin/blocks/accordion/index.js";
import contact_form from "./lib/admin/blocks/contact-form/index.js";
import video from "./lib/admin/blocks/video/index.js";
import google_map from "./lib/admin/blocks/google-map/index.js";
import tab from "./lib/admin/blocks/tab/index.js";
import tabs_container from "./lib/admin/blocks/tabs-container/index.js";
import testimonials from "./lib/admin/blocks/testimonials-carousel/index.js";
import teams from "./lib/admin/blocks/team/index.js";
import grey_background from "./lib/admin/blocks/grey-background/index.js";
import posts_list from "./lib/admin/blocks/posts-list/index.js";
import posts_list_filters from "./lib/admin/blocks/posts-list-filters/index.js";
import posts_list_filters_ajax from "./lib/admin/blocks/posts-list-filters-ajax/index.js";
import latest_posts from "./lib/admin/blocks/latest-posts/index.js";
import small_blocks_container from "./lib/admin/blocks/small-blocks-container/index.js";
import small_block from "./lib/admin/blocks/small-block/index.js";
import share_block from "./lib/admin/blocks/share-block/index.js";
import breadcrumbs from "./lib/admin/blocks/breadcrumbs/index.js";
import logo_list from "./lib/admin/blocks/logo-list/index.js";
import narrow_content from "./lib/admin/blocks/narrow-content/index.js";
import image_text_banner from "./lib/admin/blocks/image-text-banner/index.js";
import image_text_carousel from "./lib/admin/blocks/image-text-carousel/index.js";
import image_text_slide from "./lib/admin/blocks/image-text-slide/index.js";
import services_carousel from "./lib/admin/blocks/services-carousel/index.js";
import services_slide from "./lib/admin/blocks/services-slide/index.js";
import login_register from "./lib/admin/blocks/login-register/index.js";
import video_tour from "./lib/admin/blocks/video-tour/index.js";
import modules_list from "./lib/admin/blocks/modules-list/index.js";
import search_results from "./lib/admin/blocks/search-results/index.js";
import all_favourites from "./lib/admin/blocks/all-favourites/index.js";
import all_newsletters from "./lib/admin/blocks/all-newsletters/index.js";
import account_settings from "./lib/admin/blocks/account-settings/index.js";
import accept_terms from "./lib/admin/blocks/accept-terms/index.js";
import accept_privacy from "./lib/admin/blocks/accept-privacy/index.js";
import change_password from "./lib/admin/blocks/change-password/index.js";
import forgot_password from "./lib/admin/blocks/forgot-password/index.js";
import site_map from "./lib/admin/blocks/site-map/index.js";
import pagebannertitles from "./lib/admin/blocks/page-banner-titles/index.js";
import icon_list from "./lib/admin/blocks/icon-list/index.js";
import icon_list_item from "./lib/admin/blocks/icon-list-item/index.js";
import benefit_carousel from "./lib/admin/blocks/benefit-carousel/index.js";
import benefit_slide from "./lib/admin/blocks/benefit-slide/index.js";
import globe_cta from "./lib/admin/blocks/globe-cta/index.js";
import page_contact from "./lib/admin/blocks/page-contact/index.js";
import custom_login from "./lib/admin/blocks/custom-login/index.js";

stats.init();
stat.init();
image_text.init();
accordion.init();
contact_form.init();
video.init();
google_map.init();
tab.init();
tabs_container.init();
testimonials.init();
teams.init();
grey_background.init();
posts_list.init();
posts_list_filters.init();
posts_list_filters_ajax.init();
latest_posts.init();
small_blocks_container.init();
small_block.init();
share_block.init();
breadcrumbs.init();
logo_list.init();
narrow_content.init();

image_text_carousel.init();
image_text_slide.init();
image_text_banner.init();
services_carousel.init();
services_slide.init();
login_register.init();
video_tour.init();
modules_list.init();
search_results.init();
all_favourites.init();
all_newsletters.init();
account_settings.init();
accept_terms.init();
accept_privacy.init();
change_password.init();
forgot_password.init();
site_map.init();
pagebannertitles.init();
icon_list.init();
icon_list_item.init();
benefit_carousel.init();
benefit_slide.init();
globe_cta.init();
page_contact.init();
custom_login.init();

// Remove default block styles
wp.domReady(() => {
  if (document.body.classList.contains("block-editor-page")) {
    // image
    wp.blocks.unregisterBlockStyle("core/image", "rounded");
    wp.blocks.unregisterBlockStyle("core/image", "default");

    // button
    wp.blocks.unregisterBlockStyle("core/button", "fill");
    wp.blocks.unregisterBlockStyle("core/button", "outline");
  }

  // In #createuser form hide username field
  const createUserForm = document.querySelector(".user-role-main #createuser");
  if (createUserForm) {
    const firstRow = createUserForm.querySelector(".form-table tr");
    if (firstRow) {
      firstRow.style.display = "none";
    }
  }

  // in createUserForm set the value in the #user_login field to be "user" plus a unix timestamp
  if (createUserForm) {
    const userLoginField = createUserForm.querySelector("#user_login");
    if (userLoginField) {
      const timestamp = Math.floor(Date.now() / 1000);
      userLoginField.value = "user" + timestamp;
    }
  }

  // Get .alert-filters checkboxes and select a corresponding value in the #module_type select dropdown
  const alertsAdminPage = document.querySelector(".alerts-admin-page");
  if (!alertsAdminPage) {
    return;
  }

  const alertFilters = alertsAdminPage.querySelectorAll(".alert-filters div");
  const moduleTypeSelect = document.querySelector("#module_type");

  if (!moduleTypeSelect) {
    return;
  }

  alertFilters.forEach((checkboxDiv, index) => {
    const checkbox = checkboxDiv.querySelector("input[type='checkbox']");
    if (!checkbox) {
      return;
    }
    checkbox.addEventListener("change", () => {
      moduleTypeSelect.selectedIndex = checkbox.checked ? index + 1 : 0;
    });
  });


});
