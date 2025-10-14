// Import whatInput
import whatInput from 'what-input';
window.whatInput = whatInput;

//import $ from 'jquery';
//window.$ = $;

import Flickity from 'flickity';
window.Flickity = Flickity;

import sitespecific from './lib/theme/index.js';
//import Foundation from 'foundation-sites';

// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
//import foundationPieces from './lib/foundation-explicit-pieces.js';

window.addEventListener("load", function(e) {
  
  sitespecific.themename();
  //foundationPieces.foundationPiecesFtns();
 //$(document).foundation();
  // window.foundationPieces = Foundation;
});
