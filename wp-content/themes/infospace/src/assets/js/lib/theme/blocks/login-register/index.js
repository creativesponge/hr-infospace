var login_register = {
  init: function () {
    this.login_register_actions();
  },

  login_register_actions: function () {
    // Tabs
    [].forEach.call(
      document.getElementsByClassName("login-register"),

      function (tabs_group, i) {
        let tabsList = tabs_group.querySelector(".login-register__nav");
        let tabsListButtons = tabsList.querySelectorAll("button");
        let tabspanels = tabs_group.getElementsByClassName("login-register__panel");
 tabspanels[1].classList.remove("active");
        // Build the List items from the panel headings
        [].forEach.call(tabspanels, function (item, index) {
         
          // Set up panels
          item.setAttribute("id", "panel" + index);

          let panelsArray = Array.prototype.slice.call(tabspanels); // convert to array
          
          tabsListButtons[index].addEventListener(
            "click",
            function (e) {
             
              e.preventDefault();
              [].forEach.call(panelsArray, function (el) {
                el.classList.remove("active");
                //el.setAttribute('aria-hidden', 'true');
              });
              //[].forEach.call(navList, function (el) {
                //el.classList.remove("active");
              //});
              //[].forEach.call(linkItem, function (el) {
                //el.setAttribute("aria-selected", "false");
             // });
              panelsArray[index].classList.add("active");
              //listItem.classList.add("active");
              //listItem.classList.add("active");
              //listLink.setAttribute("aria-selected", "true");

              //panelsArray[index].removeAttribute('aria-hidden');
            },
            false
          );
        });

        // Set up the first panel as active
        tabspanels[0].classList.add("active");
        //tabspanels[0].removeAttribute('aria-hidden');
      }
    );
  },
};

export default login_register;
