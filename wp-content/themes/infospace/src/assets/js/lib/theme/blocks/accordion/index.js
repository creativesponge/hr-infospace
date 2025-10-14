const accordion_block = {
  init: function () {
    this.accordion();
  },
  accordion: function () {
    var allLinks = document.querySelectorAll(".accordion > dt > a");
    var allPanels = document.querySelectorAll(".accordion > dd");

    for (var i = 0; i < allLinks.length; i++) {
      allPanels[i].classList.remove("active");
      allPanels[i].style.height = "0px";
      allLinks[i].setAttribute('aria-expanded', "false");
      allLinks[i].addEventListener("click", function (e) {
        e.preventDefault();

        var $thisx = this,
          $target = $thisx.closest(".accordion"),
          $targetdd = $target.querySelector("dd"),
          $targetBox = $target.querySelector(".accordion-block-container");

        if (!$targetdd.classList.contains("active")) {
          // Not already open
          // Reset all
          for (var i = 0; i < allPanels.length; i++) {
            allPanels[i].classList.remove("active");
            allPanels[i].style.height = "0px";
          }
          for (var i = 0; i < allLinks.length; i++) {
            allLinks[i].classList.remove("openAccordian");
            allLinks[i].setAttribute('aria-expanded', "false");
          }
          // Open panel
          $thisx.classList.add("openAccordian");
          $thisx.setAttribute('aria-expanded', "true");
          $targetdd.classList.add("active");
          const height = $targetBox.getBoundingClientRect().height;
          $targetdd.style.height = `${height}px`;
        } else {
          // Already open so close all
          for (var i = 0; i < allPanels.length; i++) {
            allPanels[i].classList.remove("active");
            allPanels[i].style.height = "0px";
          }
          for (var i = 0; i < allLinks.length; i++) {
            allLinks[i].classList.remove("openAccordian");
            allLinks[i].setAttribute('aria-expanded', "false");
          }
        }

        return false;
      });
    }

    
  },
};

export default accordion_block;
