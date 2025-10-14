// Import custom modules and init

import pagebannertitles from "./lib/admin/blocks/page-banner-titles/index.js";
import stats from "./lib/admin/blocks/stats/index.js";
import stat from "./lib/admin/blocks/stat/index.js";
import image_text from "./lib/admin/blocks/image-text/index.js";
import image_text_carousel from "./lib/admin/blocks/image-text-carousel/index.js";
import image_text_slide from "./lib/admin/blocks/image-text-slide/index.js";
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

pagebannertitles.init();
stats.init();
stat.init();
image_text.init();
image_text_carousel.init();
image_text_slide.init();
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
});
