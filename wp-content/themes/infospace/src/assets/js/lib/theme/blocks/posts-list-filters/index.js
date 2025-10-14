var posts_list_filters = {
  init: function () {
    this.filter_dropdown_ftn();
  },

  filter_dropdown_ftn: function () {
    var filterList = document.getElementsByClassName("filter__list");
    //console.log(filterList);
    if (filterList.length > 0) {
      [].forEach.call(
        document.getElementsByClassName("filter__list"),
        function (item, index) {
          //$.each(filterList, function (index, item) {
          // Open/close menu

          var filterHeading = item.getElementsByClassName("filter__heading"),
            filterLinks = item.getElementsByClassName("nav-filter");

          filterHeading[0].addEventListener(
            "click",
            function (e) {
              e.preventDefault();
              if (filterHeading[0].classList.contains("open")) {
                filterHeading[0].classList.remove("open");
                filterLinks[0].classList.remove("open");
              } else {
                filterHeading[0].classList.add("open");
                filterLinks[0].classList.add("open");
              }
            },
            false
          );
        }
      );
    }

    // Scrioll to filters
    if (window.location.href.indexOf("cat") > -1) {
      let $filterTop = document.getElementById("list-top");

      if ($filterTop) {
  
        window.scroll(0, findPosition($filterTop));

        function findPosition(obj) {
          var currenttop = 0;
          if (obj.offsetParent) {
            do {
              currenttop += obj.offsetTop;
            } while ((obj = obj.offsetParent));
            return [currenttop - 140];
          }
        }
      }
    }
  },
};

export default posts_list_filters;
