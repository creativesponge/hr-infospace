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
        let tabspanels = tabs_group.getElementsByClassName(
          "login-register__panel",
        );
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
              });

              panelsArray[index].classList.add("active");
            },
            false,
          );
        });

        // Set up the first panel as active
        tabspanels[0].classList.add("active");
      },
    );
  },
};

export default login_register;
