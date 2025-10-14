var tabs_container = {

  init: function () {
        this.tabs__actions();
        this.tabs__mobile_carousel();
  },

  tabs__actions: function () {

    // Tabs
    [].forEach.call(document.getElementsByClassName('tabs-container'),

      function(tabs_group, i) {
        
        let tabsList = tabs_group.querySelector('ul');
        let tabspanels = tabs_group.getElementsByClassName('tabs__tab');

        // Build the List items from the panel headings
        [].forEach.call(tabspanels, function(item, index) {
       
            let tabHeading = item.querySelector(".tab__heading h3");
            let listItem = document.createElement('li');
            let listLink = document.createElement('a');

            // Build the list
            listItem.setAttribute('role', 'presentation');

            listLink.setAttribute('href', '#panel'+index );
            listLink.setAttribute('role', 'tab' );
            listLink.setAttribute('aria-controls', 'panel'+index );
            listLink.setAttribute('aria-selected', 'false' );
            listLink.setAttribute('tab-index', '-1' );

            // Select the first tab as selected
            if (index == 0) { 
                listItem.classList.add("active");
                listLink.setAttribute('aria-selected', 'true' );
                listLink.setAttribute('tab-index', '0' );
            }

            listLink.appendChild(document.createTextNode(tabHeading.textContent));
            listItem.appendChild(listLink);
            tabsList.appendChild(listItem);

            // Set up panels
            item.setAttribute('id', 'panel'+index);

            let panelsArray = Array.prototype.slice.call( tabspanels ); // convert to array

            listLink.addEventListener("click", 
                function(e) {
                    let navList = document.querySelectorAll('.tabs__list li');
                    let linkItem = document.querySelectorAll('.tabs__list li a');
                    
                    e.preventDefault();
                    [].forEach.call(panelsArray, function(el) {
                        el.classList.remove("active");
                        //el.setAttribute('aria-hidden', 'true');
                    });
                    [].forEach.call(navList, function(el) {
                      el.classList.remove("active");
                    });
                    [].forEach.call(linkItem, function(el) {
                      el.setAttribute('aria-selected', 'false');
                    });
                    panelsArray[index].classList.add("active");
                    //listItem.classList.add("active");
                    listItem.classList.add("active");
                    listLink.setAttribute('aria-selected', 'true');
                    
                    //panelsArray[index].removeAttribute('aria-hidden');
                } , false
            );

        });

        // Set up the first panel as active
        tabspanels[0].classList.add("active");
        //tabspanels[0].removeAttribute('aria-hidden');

      }

    )

  },
  tabs__mobile_carousel: function () {
    //Image text carousel
    [].forEach.call(document.getElementsByClassName('tabs-container__content'),

      function(tabsCarousel, i) {

        let tabsCArousel;
        const enableTabsCarousel = function() {

            tabsCArousel =   new Flickity(tabsCarousel, {
              cellAlign: "center",
              contain: true,
              prevNextButtons: false,
              threshold: 10,
              grabCursor: true,
              autoPlay: 4000,
              watchCSS: true
            });

        };

        return enableTabsCarousel();
      }

    )
  }
};

export default tabs_container;
