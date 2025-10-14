var teams = {
  init: function () {
    this.team_pop_up();
  },

  team_pop_up: function () {
    // Team pop ups
    [].forEach.call(
      document.getElementsByClassName("team"),

      function (team_group, i) {
        let person = team_group.querySelectorAll(".person");
        let peopleDetails = team_group.getElementsByClassName("person__popup");
        let peopleDetailsArray = Array.prototype.slice.call(peopleDetails); // convert to array
        // Build the List items from the panel headings
        [].forEach.call(person, function (item, index) {
          let personButton = item.querySelector(".openButton");
          let closeButton = item.querySelector(".botton__close");

          if (personButton != null) {
            personButton.addEventListener(
              "click",
              function (e) {
                e.preventDefault();
                peopleDetailsArray[index].classList.add("active");
                this.setAttribute("aria-selected", "true");
                peopleDetailsArray[index].removeAttribute("aria-hidden");
              },
              false
            );

            closeButton.addEventListener(
              "click",
              function (e) {
                e.preventDefault();
                peopleDetailsArray[index].classList.remove("active");
                peopleDetailsArray[index].setAttribute("aria-hidden", "true");
                personButton.setAttribute("aria-selected", "false");
              },
              false
            );
          }
        });
      }
    );
  },
};

export default teams;
