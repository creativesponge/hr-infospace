var testimonails_carousel = {

  init: function () {
        this.testimonials_carousels();
  },

  testimonials_carousels: function () {

    //Image text carousel
    [].forEach.call(document.getElementsByClassName('testimonials__carousel'),

      function(testimonialsCarousel, i) {

        let testimonialsSwiper;
        const enableSwiper = function() {

            testimonialsSwiper =   new Flickity(testimonialsCarousel, {

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
  }
};

export default testimonails_carousel;
