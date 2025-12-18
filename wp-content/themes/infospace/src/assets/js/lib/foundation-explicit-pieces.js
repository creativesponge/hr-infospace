import { Foundation } from "foundation-sites/js/foundation.core.js";
//import { rtl, GetYoDigits, transitionend } from 'foundation-sites/js/foundation.core.utils.js';
//import { Box } from 'foundation-sites/js/foundation.util.box.js'
//import { onImagesLoaded } from 'foundation-sites/js/foundation.util.imageLoader.js';
//import { Keyboard } from 'foundation-sites/js/foundation.util.keyboard.js';
//import { MediaQuery } from 'foundation-sites/js/foundation.util.mediaQuery.js';
//import { Motion, Move } from 'foundation-sites/js/foundation.util.motion.js';
//import { Nest } from 'foundation-sites/js/foundation.util.nest.js';
//import { Timer } from 'foundation-sites/js/foundation.util.timer.js';
//import { Touch } from 'foundation-sites/js/foundation.util.touch.js';
//import { Triggers } from 'foundation-sites/js/foundation.util.triggers.js';
//import { Abide } from 'foundation-sites/js/foundation.abide.js';
//import { Accordion } from 'foundation-sites/js/foundation.accordion.js';
//import { AccordionMenu } from "foundation-sites/js/foundation.accordionMenu.js";
//import { Drilldown } from 'foundation-sites/js/foundation.drilldown.js';
//import { Dropdown } from 'foundation-sites/js/foundation.dropdown.js';
//import { DropdownMenu } from "foundation-sites/js/foundation.dropdownMenu.js";
import { Equalizer } from 'foundation-sites/js/foundation.equalizer.js';
//import { Interchange } from "foundation-sites/js/foundation.interchange.js";
//import { Magellan } from 'foundation-sites/js/foundation.magellan.js';
//import { OffCanvas } from "foundation-sites/js/foundation.offcanvas.js";
//import { Orbit } from 'foundation-sites/js/foundation.orbit.js';
//import { ResponsiveMenu } from 'foundation-sites/js/foundation.responsiveMenu.js';
//import { ResponsiveToggle } from 'foundation-sites/js/foundation.responsiveToggle.js';
//import { Reveal } from 'foundation-sites/js/foundation.reveal.js';
//import { Slider } from 'foundation-sites/js/foundation.slider.js';
//import { SmoothScroll } from 'foundation-sites/js/foundation.smoothScroll.js';
//import { Sticky } from 'foundation-sites/js/foundation.sticky.js';
//import { Tabs } from 'foundation-sites/js/foundation.tabs.js';
//import { Toggler } from 'foundation-sites/js/foundation.toggler.js';
//import { Tooltip } from 'foundation-sites/js/foundation.tooltip.js';
//import { ResponsiveAccordionTabs } from 'foundation-sites/js/foundation.responsiveAccordionTabs.js';
import $ from 'jquery';

var foundationPieces = {
  foundationPiecesFtns: function () {
   
    Foundation.addToJquery($);

    // Add Foundation Utils to Foundation global namespace for backwards
    // compatibility.

    //Foundation.rtl = rtl;
    //Foundation.GetYoDigits = GetYoDigits;
    //Foundation.transitionend = transitionend;

    //Foundation.Box = Box;
    //Foundation.onImagesLoaded = onImagesLoaded;
    //Foundation.Keyboard = Keyboard;
    //Foundation.MediaQuery = MediaQuery;
    //Foundation.Motion = Motion;
    //Foundation.Move = Move;
    //Foundation.Nest = Nest;
    //Foundation.Timer = Timer;

    // Touch and Triggers previously were almost purely sede effect driven,
    // so no need to add it to Foundation, just init them.

    //Touch.init($);

    //Triggers.init($, Foundation);

    //Foundation.plugin(Abide, 'Abide');

    //Foundation.plugin(Accordion, 'Accordion');

    //Foundation.plugin(AccordionMenu, "AccordionMenu");

    //Foundation.plugin(Drilldown, 'Drilldown');

    //Foundation.plugin(Dropdown, 'Dropdown');

    //Foundation.plugin(DropdownMenu, "DropdownMenu");

    Foundation.plugin(Equalizer, 'Equalizer');

    //Foundation.plugin(Interchange, "Interchange");

    //Foundation.plugin(Magellan, 'Magellan');

    //Foundation.plugin(OffCanvas, "OffCanvas");

    //Foundation.plugin(Orbit, 'Orbit');

    //Foundation.plugin(ResponsiveMenu, 'ResponsiveMenu');

    //Foundation.plugin(ResponsiveToggle, 'ResponsiveToggle');

    //Foundation.plugin(Reveal, 'Reveal');

    //Foundation.plugin(Slider, 'Slider');

    //Foundation.plugin(SmoothScroll, 'SmoothScroll');

    //Foundation.plugin(Sticky, 'Sticky');

    //Foundation.plugin(Tabs, 'Tabs');

    //Foundation.plugin(Toggler, 'Toggler');

    //Foundation.plugin(Tooltip, 'Tooltip');

    //Foundation.plugin(ResponsiveAccordionTabs, 'ResponsiveAccordionTabs');
  },
};
export default foundationPieces;
