var icon_list = {
  init: function () {
    this.icon_list_equaliser();
  },

  icon_list_equaliser: function () {
    //If .module-teaser__target is clicked, go to the link
    const iconListHeadings = document.querySelectorAll(".icon-list-item h3");

    const equalizeHeights = () => {
        // Check if there are any headings before proceeding
        if (iconListHeadings.length === 0) {
          return;
        }

      // Reset heights first
      iconListHeadings.forEach((heading) => {
        heading.style.height = 'auto';
      });

      // Find the tallest heading
      let maxHeight = 0;
      iconListHeadings.forEach((h) => {
        const height = h.offsetHeight;
        if (height > maxHeight) {
          maxHeight = height;
        }
      });

      // Set all headings to the max height
      iconListHeadings.forEach((heading) => {
        heading.style.height = maxHeight + "px";
      });
    };

    // Run initially
    equalizeHeights();

    // Run on window resize
    window.addEventListener('resize', equalizeHeights);
  },
};

export default icon_list;
