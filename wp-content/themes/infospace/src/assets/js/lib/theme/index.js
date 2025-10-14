import utils from '../utils/index.js';

import image_text_carousel from './blocks/image-text-carousel/index.js';
import google_map from './blocks/google-map/index.js';
import tabs_container from './blocks/tabs/index.js';
import accordion_block from './blocks/accordion/index.js';
import testimonails_carousel from './blocks/testimonials/index.js';
import teams from './blocks/teams/index.js';
import posts_list_filters from './blocks/posts-list-filters/index.js';

var sitespecific = {
    themename: function() {
        // Execute these functions on all pages

        utils.ajaxFormSubmission();
        utils.ajaxPostsFilter();
        utils.ajaxLinkStats();
        utils.ajaxDownloadStats();
        
        // Run this if using a fixed header
        utils.bodyPadding();
        utils.fixedmenu();
        utils.mobileMenuToggle();
        utils.accordionMenu();
        utils.dropDownMenu();
        image_text_carousel.init();
        google_map.init();
        tabs_container.init();
        accordion_block.init();
        testimonails_carousel.init();
        teams.init();
        posts_list_filters.init();
        
        // Ready to rock!
        document.body.classList.add('ready');
    }

};

export default sitespecific;
