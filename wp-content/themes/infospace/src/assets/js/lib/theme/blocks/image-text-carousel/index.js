var image_text_carousel = {

  init: function () {
        this.carousels();
  },

  carousels: function () {

    /*[].forEach.call(document.getElementsByClassName('mobile-only-carousel'),

          function(carousel, i) {

            const breakpoint = window.matchMedia( '(min-width:640px)' );
            let mobileSwiper;

            const breakpointChecker = function() {

               if ( breakpoint.matches === true ) {

                  if ( mobileSwiper !== undefined ) mobileSwiper.destroy( true, true );
                  return;

               } else if ( breakpoint.matches === false ) {
                  return enableSwiper();
               }

            };

            const enableSwiper = function() {

              mobileSwiper =   new Flickity(carousel, {

                cellAlign: "left",
                contain: true,
                prevNextButtons: false,
                threshold: 10,
                grabCursor: true,
                autoPlay: 4000,
              });

            };

        // keep an eye on viewport size changes
          breakpoint.addListener(breakpointChecker);
          // kickstart
          breakpointChecker();
        }


    ),*/

    //Image text carousel
    [].forEach.call(document.getElementsByClassName('image-text-carousel__carousel'),

      function(imageTextCarousel, i) {

        let imageTextSwiper;
        const enableSwiper = function() {

            imageTextSwiper =   new Flickity(imageTextCarousel, {

              cellAlign: "center",
              contain: true,
              prevNextButtons: false,
              threshold: 10,
              grabCursor: true,
              fade: true,
              autoPlay: 4000,

            });

        };

        return enableSwiper();
      }

    )

    //testimonial carousel
    /*, [].forEach.call(document.getElementsByClassName('testimonial-carousel'),

      function(testimonialCarousel, i) {

        let imageTextSwiper;
        const enableSwiper = function() {

            imageTextSwiper =   new Flickity(testimonialCarousel, {

              cellAlign: "center",
              contain: true,
              prevNextButtons: false,
              threshold: 10,
              grabCursor: true,
              fade: true,

            });

        };

        return enableSwiper();
      }

    )*/
  }
};

export default image_text_carousel;
