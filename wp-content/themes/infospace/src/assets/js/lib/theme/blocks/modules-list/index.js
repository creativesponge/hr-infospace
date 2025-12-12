var modules_list = {

  init: function () {
        this.modules_list_clicks();
  },

  modules_list_clicks: function () {

    //If .module-teaser__target is clicked, go to the link
    const moduleTeaserTargets = document.querySelectorAll('.module-teaser__target');

    moduleTeaserTargets.forEach(function (moduleTeaserTarget) {
      moduleTeaserTarget.addEventListener('click', function (e) {
        const buttonLink = moduleTeaserTarget.querySelector('.button-link');
        const link = buttonLink ? buttonLink.getAttribute('href') : null;
        if (link) {
          window.location.href = link;
        }
      });
    });
   
  }
};

export default modules_list;
