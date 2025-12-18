
var benefit_carousel = {

  init: function () {
    this.benefitCarousels();
  },

  benefitCarousels: function () {
    

    // benefit carousel
    [].forEach.call(document.getElementsByClassName('benefit-carousel__carousel'),
      function(imageTextCarousel) {
        let imageTextSwiper;
        const enableSwiper = function() {
          imageTextSwiper = new Flickity(imageTextCarousel, {
            cellAlign: "left",
            contain: true,
            prevNextButtons: false,
            pageDots: false,
            threshold: 10,
            grabCursor: true,
            fade: true,
            autoPlay: 4000
          });
          // Generate page dots based on number of carousel cells
          var buttonCarousel = imageTextCarousel.parentElement.querySelector('.button-group--cells');
          if (buttonCarousel) {
            var cellCount = imageTextSwiper.cells.length;
            var pageDotsHTML = '';
            
            for (var i = 0; i < cellCount; i++) {
              var isSelected = i === 0 ? ' is-selected' : '';
              pageDotsHTML += '<button class="button' + isSelected + ' flickity-page-dot">' + (i + 1) + '</button>';
            }
            
            buttonCarousel.innerHTML = pageDotsHTML;
            
            // Add click handlers for page dots
            buttonCarousel.addEventListener('click', function(event) {
              if (event.target.matches('.flickity-page-dot')) {
                var dotIndex = Array.from(buttonCarousel.children).indexOf(event.target);
                imageTextSwiper.select(dotIndex);
              }
            });
            
            // Update page dots on carousel select
            imageTextSwiper.on('select', function() {
              var previousSelected = buttonCarousel.querySelector('.is-selected');
              var currentSelected = buttonCarousel.children[imageTextSwiper.selectedIndex];
              if (previousSelected) previousSelected.classList.remove('is-selected');
              if (currentSelected) currentSelected.classList.add('is-selected');
            });
          }

          // Get button elements for this carousel
          var cellsButtonGroup = imageTextCarousel.parentElement.querySelector('.button-group--cells');
          var previousButton = imageTextCarousel.parentElement.querySelector('.button--previous');
          var nextButton = imageTextCarousel.parentElement.querySelector('.button--next');
         
          if (cellsButtonGroup) {
            var cellsButtons = Array.from(cellsButtonGroup.children);

            // Update buttons on select
            imageTextSwiper.on('select', function() {
              var previousSelectedButton = cellsButtonGroup.querySelector('.is-selected');
              var selectedButton = cellsButtonGroup.children[imageTextSwiper.selectedIndex];
              if (previousSelectedButton) previousSelectedButton.classList.remove('is-selected');
              if (selectedButton) selectedButton.classList.add('is-selected');
            });

            // Cell select
            cellsButtonGroup.addEventListener('click', function(event) {
              if (!event.target.matches('.button')) {
                return;
              }
              var index = cellsButtons.indexOf(event.target);
              imageTextSwiper.select(index);
            });
          }

          // Previous button
          if (previousButton) {
            previousButton.addEventListener('click', function() {
              imageTextSwiper.previous();
            });
          }

          // Next button
          if (nextButton) {
            nextButton.addEventListener('click', function() {
              imageTextSwiper.next();
            });
          }
        };

        return enableSwiper();
      }
    );
  }
};

export default benefit_carousel;
