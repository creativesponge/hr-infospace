var logo_list = {
  init: function () {
    this.logoList();
  },

  logoList: function () {
    //Logo list carousel
    [].forEach.call(
      document.getElementsByClassName("logo-list__content"),

      function (logoListCarousel, i) {
        let logoListSwiper;
        const enableSwiper = function () {
          logoListSwiper = new Flickity(logoListCarousel, {
            cellAlign: "center",
            contain: true,
            pageDots: false,
            threshold: 10,
            grabCursor: true,
            fade: true,
            autoPlay: 4000,
            wrapAround: true,
            prevNextButtons: false,
          });
        };
        // fix for lazyloading images breaking the carousel in safari
        setTimeout(() => {
          if (logoListSwiper) {
            logoListSwiper.destroy();
          }
          enableSwiper();
        }, 100);

        return enableSwiper();
      }
    );
  },
};

export default logo_list;
