var utils = {
  //init: function () {
  //this.ajaxFormSubmission();
  //this.ajaxpostsFilter();
  //this.fixedmenu();
  //this.bodyPadding();
  //},

  urlencodeFormData: function (formdata) {
    var s = "";

    function encode(s) {
      return encodeURIComponent(s).replace(/%20/g, "+");
    }

    for (var pair of formdata.entries()) {
      if (typeof pair[1] == "string") {
        s += (s ? "&" : "") + encode(pair[0]) + "=" + encode(pair[1]);
      }
    }
    return s;
  },

  ajaxFormSubmission: function () {
    var forms = document.querySelectorAll('form[action="ajax"]');
    [].forEach.call(forms, function (form, i) {
      form.onsubmit = function (e) {
        e.preventDefault();

        if (!form.classList.contains("submitting")) {
          form.classList.add("submitting");
          var //seriX = $(form).serialize(),
            //data = seriX.replace(/%20/g, "+"),
            //data = new FormData(form),
            data = utils.urlencodeFormData(new FormData(form)),
            xhr = new XMLHttpRequest();

          xhr.open("POST", ajax_object.ajax_url, true);
          xhr.setRequestHeader(
            "Content-type",
            "application/x-www-form-urlencoded; charset=UTF-8"
          );
          xhr.onload = function () {
            form.classList.remove("submitting");

            if (xhr.readyState == 4 && xhr.status == 200) {
              var response = JSON.parse(xhr.responseText);
              console.log(response);
              if (response.success) {
                form.classList.add("submitted");
                form.reset();
              } else {
                // There was an issue sending the email, tell they need to check it's valid
                form.classList.add("error");
              }
            } else {
              // There was an issue contacting the server, try again
              form.classList.add("error");
            }
          };
          xhr.send(data);
        }
      };
    });
  },

  ajaxPostsFilter: function () {
    /**
     * Retrieve posts
     */
    const container = document.getElementById("posts-container-async");
    if (!container) {
      return;
    }
    function get_news_posts(params) {
      const container = document.getElementById("posts-container-async");

      if (!container) {
        return;
      }

      const content = container.querySelector(".content"),
        status = container.querySelector(".status");

      status.innerHTML =
        "<div class='lds-ring'><div></div><div></div><div></div><div></div></div>";

      fetch(newsCats.news_ajax_url, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: new URLSearchParams({
          action: "posts_do_filter_posts",
          newsnonce: newsCats.newsnonce,
          params: JSON.stringify(params),
          //params: params,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === 200) {
            // remove pagination
            const pagination = container.querySelector(".pagination");
            if (pagination) pagination.remove();

            // Append new
            content.insertAdjacentHTML("beforeend", data.content);
            if (data.found_posts == 1) {
              document.querySelector(".filters-data__results").textContent =
                data.found_posts + " RESULT FOUND";
            } else {
              document.querySelector(".filters-data__results").textContent =
                data.found_posts + " RESULTS FOUND";
            }
          } else if (data.status === 201) {
            if (data.message == "No events found") {
              content.insertAdjacentHTML(
                "beforeend",
                '<h6 class="no-results"><div class="grid-container "><span><p>There are currently no events to show</p></span></div></h6>'
              );
            } else {
              content.insertAdjacentHTML("beforeend", data.message);
              document.querySelector(".filters-data__results").textContent = "";
            }
          } else {
            status.insertAdjacentHTML("beforeend", data.message);
          }
        })
        .catch((error) => {
          status.insertAdjacentHTML("beforeend", error.message);
        })
        .finally(() => {
          status.textContent = "";

          // Add class to items for animation
          setTimeout(() => {
            const gridItems = document.querySelectorAll(".list-box");
            gridItems.forEach((item) => item.classList.add("loaded"));
          }, 3000);
        });
    }

    /**
     * Retrieve filters
     */
    function getActiveFilters() {
      const activeLinks = document.querySelectorAll(
        ".nav-filter--cats .active a"
      );
      const activeLinksArray = [];
      activeLinks.forEach((link) => {
        const filter = link.dataset.filter;
        const term = link.dataset.term;
        activeLinksArray.push({ filter, term });
      });

      return activeLinksArray;
    }

    function getSortFilters() {
      const sortLink = document.querySelector(".nav-filter--sort .active a");
      return sortLink ? sortLink.dataset.order : null;
    }

    const postsContainer = document.getElementById("posts-container-async");
    if (postsContainer) {
      postsContainer.addEventListener("click", (event) => {
        const target = event.target;
        if (target.matches("a[data-filter], .pagination button")) {
          event.preventDefault();

          const filterList = target.closest(".nav-filter"),
            allFilterList = document.querySelectorAll(".nav-filter"),
            container = document.getElementById("posts-container-async"),
            qty = container.dataset.paged,
            content = container.querySelector(".content"),
            formField = document.getElementById("posts-search"),
            search = formField.value,
            allBtns = container.querySelectorAll(".filter__heading");

          if (target.dataset.type == "link") {
            if (filterList.querySelector(".active")) {
              filterList.querySelector(".active").classList.remove("active");
            }

            allBtns.forEach((btn) => {
              btn.classList.remove("open");
            });
            target.parentElement.classList.add("active");
            content.innerHTML = "";

            const page = target.dataset.page,
              screenWidth = window.innerWidth,
              allFilterLink = document.querySelector(".show-all");
            const filterListElement = target.closest(".filter__list");
            const thisButton = filterListElement
              ? filterListElement.querySelector(".filter__heading")
              : null;
            const thisName = target.textContent;
            const thisButtonLabel = thisButton
              ? thisButton.dataset.label
              : null;
            const matchingBtns = thisButtonLabel
              ? container.querySelectorAll(`[data-label="${thisButtonLabel}"]`)
              : [];

            if (thisButton && thisButtonLabel && matchingBtns.length > 0) {
              matchingBtns.forEach((btn) => {
                btn.textContent = thisName;
              });
            }

            matchingBtns.forEach((btn) => {
              btn.textContent = thisName;
            });

            if (target.classList.contains("show-all")) {
              allFilterList.forEach((list) => {
                const activeItem = list.querySelector(".active");
                if (activeItem) activeItem.classList.remove("active");
              });
              if (allFilterLink && allFilterLink.parentElement) {
                allFilterLink.parentElement.classList.add("active");
              }
              allBtns.forEach((btn) => {
                btn.textContent = btn.dataset.label;
                btn.classList.remove("open");
              });
            } else if (screenWidth <= 640) {
              window.scrollTo({
                top:
                  document.getElementById("posts-list-results").offsetTop - 200,
                behavior: "smooth",
              });
            }
          } else {
            const page = target.dataset.page;
          }

          const params = {
            page: target.dataset.page,
            cats: getActiveFilters(),
            qty: qty,
            search: search,
            order: getSortFilters(),
          };

          get_news_posts(params);
        }
      });
    }

    const postsForm = document.getElementById("postsform");
    if (postsForm) {
      postsForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const container = document.getElementById("posts-container-async"),
          content = container.querySelector(".content"),
          formField = document.getElementById("posts-search"),
          formFieldVal = formField.value,
          filterList = document.querySelector(".nav-filter"),
          allFilterLink = document.querySelector(".show-all");

        const params = {
          page: 1,
          cats: getActiveFilters(),
          qty: container.dataset.paged,
          search: formFieldVal,
          order: getSortFilters(),
        };

        content.innerHTML = "";
        filterList.querySelector(".active").classList.remove("active");
        allFilterLink.parentElement.classList.add("active");
        document.querySelector(".filters-data__results").textContent = "";

        get_news_posts(params);
      });
    }

    const allTermsElement = document.querySelector(
      '#posts-container-async a[data-term="all-terms"]'
    );

    let pageType = "post";
    if (allTermsElement) {
      pageType = allTermsElement.dataset.filter;
    }

    if (
      utils.getCookie("typeclient") &&
      utils.getCookie("typeclient") == pageType
    ) {
      const loadedTax = utils.getCookie("typeclient"),
        loadedTerm = utils.getCookie("filterclient") || "all-terms",
        loadedQty = document.getElementById("posts-container-async").dataset
          .paged,
        filterList = document.querySelector(".nav-filter");

      if (loadedTerm != "all-terms") {
        filterList.querySelector(".active").classList.remove("active");
        document
          .querySelector(`#posts-container-async a[data-term="${loadedTerm}"]`)
          .parentElement.classList.add("active");
      }

      const params = {
        page: "1",
        tax: loadedTax,
        cats: getActiveFilters(),
        term: loadedTerm,
        qty: loadedQty,
        order: getSortFilters(),
      };

      get_news_posts(params);
    } else {
      document
        .querySelector('#posts-container-async a[data-term="all-terms"]')
        .click();
    }

    /*document
      .querySelectorAll(".desktop-menu a, .mobile-menu a")
      .forEach((link) => {
        link.addEventListener("click", () => {
          document.cookie = "typeclient=''";
          document.cookie = "filterclient=''";
        });
      });*/
  },

  ajaxLinkStats: function () {
    const links = document.querySelectorAll("a[data-link-id]");

    links.forEach((link) => {
      link.addEventListener("click", (e) => {
        const linkId = e.currentTarget.dataset.linkId;
        const linkUrl = e.currentTarget.href;
        const pageTitle = document.title;

        // Send AJAX request to log link click
        fetch(ajaxVars.links_ajax_url + "?action=log_link_click", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            link_id: linkId,
            link_url: linkUrl,
            page_title: pageTitle,
            nonce: ajaxVars.nonce,
          }),
        }).catch((error) => {
          console.error("Error logging link click:", error);
        });
      });
    });
  },

  ajaxDownloadStats: function () {
    const downloads = document.querySelectorAll("a[data-download-id]");

    downloads.forEach((download) => {
      download.addEventListener("click", (e) => {
        const downloadId = e.currentTarget.dataset.downloadId;
        const downloadUrl = e.currentTarget.href;
        const fileName = e.currentTarget.dataset.downloadName || document.title;

        if (
          e.target.tagName.toLowerCase() === "svg" ||
          e.target.closest("svg")
        ) {
          return;
        }

        // Send AJAX request to log download click
        fetch(
          ajaxVarsDownloads.downloads_ajax_url + "?action=log_download_click",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              download_id: downloadId,
              download_url: downloadUrl,
              file_name: fileName,
              nonce: ajaxVarsDownloads.downloadsnonce,
            }),
          }
        ).catch((error) => {
          console.error("Error logging download click:", error);
        });
      });
    });
  },

  ajaxNewsletterStats: function () {
    const newsletters = document.querySelectorAll("a[data-newsletter-id]");

    newsletters.forEach((newsletter) => {
      newsletter.addEventListener("click", (e) => {
        const newsletterId = e.currentTarget.dataset.newsletterId;
        const newsletterUrl = e.currentTarget.href;
        const fileName = e.currentTarget.dataset.newsletterName || document.title;

        /*if (
          e.target.tagName.toLowerCase() === "svg" ||
          e.target.closest("svg")
        ) {
          return;
        }*/

        // Send AJAX request to log download click
        fetch(
          ajaxVarsNewsletters.newsletters_ajax_url + "?action=log_newsletter_click",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              newsletter_id: newsletterId,
              newsletter_url: newsletterUrl,
              file_name: fileName,
              nonce: ajaxVarsNewsletters.newslettersnonce,
            }),
          }
        ).catch((error) => {
          console.error("Error logging download click:", error);
        });
      });
    });
  },

  bodyPadding: function () {
    setBodyPadding();

    function setBodyPadding() {
      var header = document.querySelectorAll(".header"),
        adminBar = document.querySelectorAll("div#wpadminbar"),
        paddingTop = header.length ? header[0].clientHeight : 0,
        marginTop = adminBar.length ? adminBar[0].clientHeight : 0,
        mainheader = header[0];
      //mobilenav = document.querySelectorAll(".off-canvas");
      // mobilemenu = mobilenav[0],
      //  mobilemenuHeight = paddingTop + marginTop;

      document.body.style.paddingTop = paddingTop + "px";
      mainheader.style.marginTop = marginTop + "px";
      // mobilemenu.style.marginTop = mobilemenuHeight + "px";
    }
    window.addEventListener("resize", setBodyPadding);
  },

  fixedmenu: function () {
    window.onscroll = function () {
      fixedbanner();
    };

    fixedbanner();

    function fixedbanner() {
      //Fixed banner
      var $header = document.querySelectorAll(".header"),
        $offcanvas = document.querySelectorAll(".off-canvas");
      if (document.documentElement.scrollTop > 1) {
        $header[0].classList.add("fixed");
        $offcanvas[0].classList.add("fixed");
      } else {
        $header[0].classList.remove("fixed");
        $offcanvas[0].classList.remove("fixed");
      }
    }
  },

  mobileMenuToggle: function () {
    var offCanvas = document.querySelectorAll(".off-canvas"),
      expand = document.querySelectorAll(".mobile-menu-toggle"),
      closeBtn = document.querySelectorAll(".off-canvas__close");

    // Add an overlay over the content
    var overlay = document.createElement("div");
    var overlayPosition = "is-overlay-fixed ";
    overlay.setAttribute("class", "js-off-canvas-overlay " + overlayPosition);
    offCanvas[0].insertAdjacentElement("afterend", overlay);

    var toggle = document.querySelectorAll(
      ".mobile-menu-toggle, .js-off-canvas-overlay"
    );

    for (var i = 0; i < toggle.length; i++) {
      toggle[i].addEventListener("click", function (e) {
        e.preventDefault();

        expand[0].classList.toggle("expanded");
        if (!document.body.classList.contains("menu-expanded")) {
          /*expand[0].setAttribute("aria-expanded", "false");
          expand[0].classList.remove("menu-expanded");
          document.body.classList.remove("menu-expanded");
          document.documentElement.classList.remove("menu-expanded");
          offCanvas[0].classList.remove("is-open");
          offCanvas[0].setAttribute("aria-hidden", "true");
          overlay.classList.remove("is-visible");
        } else {*/
          expand[0].classList.add("menu-expanded");
          expand[0].setAttribute("aria-expanded", "true");
          document.body.classList.add("menu-expanded");
          document.documentElement.classList.add("menu-expanded");
          offCanvas[0].classList.add("is-open");
          offCanvas[0].setAttribute("aria-hidden", "false");
          overlay.classList.add("is-visible");
        }
      });
      closeBtn[0].addEventListener("click", function (e) {
        e.preventDefault();

        expand[0].classList.remove("expanded");
        expand[0].setAttribute("aria-expanded", "false");
        expand[0].classList.remove("menu-expanded");
        document.body.classList.remove("menu-expanded");
        document.documentElement.classList.remove("menu-expanded");
        offCanvas[0].classList.remove("is-open");
        offCanvas[0].setAttribute("aria-hidden", "true");
        overlay.classList.remove("is-visible");
      });
    }
  },

  accordionMenu: function () {
    var subMenus = document.querySelectorAll(".mobile-menu ul.nested");

    for (var i = 0; i < subMenus.length; i++) {
      var parentLink = subMenus[i].closest("li");
      // Add classes
      parentLink.classList.add("has-submenu-toggle");
      parentLink.classList.add("is-accordion-submenu-parent");
      let r = (Math.random() + 1).toString(36).substring(2) + "-acc-menu";
      let r2 = (Math.random() + 1).toString(36).substring(2) + "-acc-menu-link";

      // Create the button
      var newButton = document.createElement("button");
      var newButtonCss = "submenu-toggle";
      newButton.setAttribute("class", newButtonCss);
      newButton.setAttribute("aria-expanded", "false");
      newButton.setAttribute("aria-controls", r);
      newButton.setAttribute("id", r2);

      subMenus[i].insertAdjacentElement("beforeBegin", newButton);

      var parentLinkButton = parentLink.querySelectorAll(".submenu-toggle");

      //Initial set up
      subMenus[i].classList.remove("active");
      subMenus[i].setAttribute("id", r);
      subMenus[i].setAttribute("aria-hidden", "true");
      subMenus[i].setAttribute("labelled-by", r2);

      parentLinkButton[0].addEventListener("click", function (e) {
        e.preventDefault();

        var $thisx = this,
          subMenu = $thisx.nextElementSibling,
          parentLi = $thisx.parentElement;

        if (!subMenu.classList.contains("active")) {
          // Not already open
          // Open panel
          parentLi.classList.add("is-open");
          subMenu.classList.add("active");
          subMenu.setAttribute("aria-hidden", "false");
          $thisx.setAttribute("aria-expanded", "true");
        } else {
          // Already open so close all
          parentLi.classList.remove("is-open");
          subMenu.classList.remove("active");
          subMenu.setAttribute("aria-hidden", "true");
          $thisx.setAttribute("aria-expanded", "false");
        }

        return false;
      });
    }
  },

  accountDropDownMenu: function () {
    var menuItems = document.querySelectorAll(
      ".site-navigation li.menu-item-has-children"
    );

    Array.prototype.forEach.call(menuItems, function (el, i) {
      let activatingA = el.querySelector("a"),
        subMenu = el.querySelector("ul"),
        timerLeave,
        timerEnter;

      // Add a button
      var btn =
        '<button class="show-on-focus"><span class="show-for-sr">show submenu for “' +
        activatingA.text +
        "”</span></button>";

      activatingA.insertAdjacentHTML("afterend", btn);
      activatingA.setAttribute("aria-haspopup", "true");

      subMenu.classList.add("is-dropdown-submenu");
      el.classList.remove("is-active");
      el.classList.add("is-dropdown-submenu-parent");
      el.classList.add("opens-left");

      if (whatInput.ask() === "touch") {
        // Touch
        el.addEventListener("click", function (event) {
          let thisItem = this;
          if (!thisItem.classList.contains("is-active")) {
            event.preventDefault();
            thisItem.classList.add("is-active");
            thisItem.querySelector("a").setAttribute("aria-expanded", "true");
            thisItem
              .querySelector("button")
              .setAttribute("aria-expanded", "true");
            subMenu.classList.add("js-dropdown-active");
          }
        });
      } else {
        // Mouse
        el.addEventListener("mouseenter", function (event) {
          clearTimeout(timerLeave);
          let thisItem = this;
          timerEnter = setTimeout(function (event) {
            thisItem.classList.add("is-active");
            thisItem.querySelector("a").setAttribute("aria-expanded", "true");
            thisItem
              .querySelector("button")
              .setAttribute("aria-expanded", "true");
            subMenu.classList.add("js-dropdown-active");
          }, 250);
        });

        el.addEventListener("mouseleave", function (event) {
          let thisItem = this,
            subactive = thisItem.querySelector(".js-dropdown-active");

          timerLeave = setTimeout(function (event) {
            thisItem.querySelector("a").setAttribute("aria-expanded", "false");
            thisItem
              .querySelector("button")
              .setAttribute("aria-expanded", "false");
            thisItem.classList.remove("is-active");

            if (subactive != null) {
              subactive.classList.remove("js-dropdown-active");
            }
          }, 250);
          clearTimeout(timerEnter);
        });
      }
      //Keyboard
      el.querySelector("button").addEventListener("click", function (event) {
        if (!this.parentNode.classList.contains("is-active")) {
          this.parentNode.classList.add("is-active");
          this.parentNode
            .querySelector("a")
            .setAttribute("aria-expanded", "true");
          this.parentNode
            .querySelector("button")
            .setAttribute("aria-expanded", "true");
          subMenu.classList.add("js-dropdown-active");
        } else {
          this.parentNode.classList.remove("is-active");
          this.parentNode
            .querySelector("a")
            .setAttribute("aria-expanded", "false");
          this.parentNode
            .querySelector("button")
            .setAttribute("aria-expanded", "false");
          subMenu.classList.remove("js-dropdown-active");
        }
        event.preventDefault();
      });
    });
  },

  dropDownMenu: function () {
    // Top level
    var menuItems = document.querySelectorAll(
      ".module-menu ul.module-menu__toplevel > li"
    );

    Array.prototype.forEach.call(menuItems, function (el, i) {
      // Only process top-level menu items (direct children of .module-menu)
      // Only select direct child <a> and <ul> elements (top-level items)
      let activatingA = el.querySelector("a"),
        subMenu = el.querySelector("ul.module-menu__submenu"),
        parentMenu = el.closest("ul.module-menu__toplevel"),
        timerLeave,
        timerEnter;

      // Skip if no submenu
      if (!activatingA || !subMenu) return;

      // Add a button
      var btn =
        '<button class="drop-down__more">More <span class="show-for-sr">“' +
        activatingA.text +
        "”</span></button>";

      activatingA.insertAdjacentHTML("afterend", btn);
      activatingA.setAttribute("aria-haspopup", "true");

      subMenu.classList.add("is-dropdown-submenu");
      el.classList.remove("is-active");
      el.classList.add("is-dropdown-submenu-parent");
      el.classList.add("opens-left");

      /*if (whatInput.ask() === "touch") {
        // Touch
        el.addEventListener("click", function (event) {
          let thisItem = this;
          if (!thisItem.classList.contains("is-active")) {
            event.preventDefault();
            thisItem.classList.add("is-active");
            thisItem.querySelector("a").setAttribute("aria-expanded", "true");
            thisItem
              .querySelector("button")
              .setAttribute("aria-expanded", "true");
            subMenu.classList.add("js-dropdown-active");
          }
        });
      } else {*/
      // Mouse
      el.querySelector("button").addEventListener("click", function (event) {
        event.preventDefault();
        clearTimeout(timerLeave);
        let thisItem = this;

        // Move the submenu under the closest parent .module-menu
        const moduleMenu = thisItem.closest(".module-menu");
        if (moduleMenu && subMenu) {
          // Only create and append the submenu wrapper if it doesn't already exist
          if (!moduleMenu.querySelector(".submenu--active")) {
            const wrapperDiv = document.createElement("div");
            wrapperDiv.classList.add("submenu--active");

            const headerDiv = document.createElement("div");
            headerDiv.classList.add("module-menu__header");

            const backButton = document.createElement("button");
            backButton.classList.add("module-menu__back");
            backButton.textContent = "Back";
            headerDiv.appendChild(backButton);

            const titleLink = document.createElement("a");
            titleLink.textContent = activatingA.textContent;
            titleLink.href = activatingA.href;
            headerDiv.appendChild(titleLink);

            wrapperDiv.appendChild(headerDiv);

            // Instead of cloning, move the submenu node so event listeners remain
            wrapperDiv.appendChild(subMenu);

            moduleMenu.appendChild(wrapperDiv);
            parentMenu.classList.add("module-menu__toplevel--hidden");

            backButton.addEventListener("click", function () {
              moduleMenu.removeChild(wrapperDiv);
              subMenu.classList.remove("js-dropdown-active");
              parentMenu.classList.remove("module-menu__toplevel--hidden");
            });
          }
        }
        timerEnter = setTimeout(function () {
          thisItem.classList.add("is-active");
          thisItem.querySelector("a").setAttribute("aria-expanded", "true");
          thisItem
            .querySelector("button")
            .setAttribute("aria-expanded", "true");
          subMenu.classList.add("js-dropdown-active");
        }, 250);
      });

      /*el.addEventListener("click", function (event) {
          let thisItem = this,
            subactive = thisItem.querySelector(".js-dropdown-active");
event.preventDefault();
          timerLeave = setTimeout(function (event) {
            thisItem.querySelector("a").setAttribute("aria-expanded", "false");
            thisItem
              .querySelector("button")
              .setAttribute("aria-expanded", "false");
            thisItem.classList.remove("is-active");

            if (subactive != null) {
              subactive.classList.remove("js-dropdown-active");
            }
          }, 250);
          clearTimeout(timerEnter);
        });
      }
      //Keyboard
      el.querySelector("button").addEventListener("click", function (event) {
        if (!this.parentNode.classList.contains("is-active")) {
          this.parentNode.classList.add("is-active");
          this.parentNode
            .querySelector("a")
            .setAttribute("aria-expanded", "true");
          this.parentNode
            .querySelector("button")
            .setAttribute("aria-expanded", "true");
          subMenu.classList.add("js-dropdown-active");
        } else {
          this.parentNode.classList.remove("is-active");
          this.parentNode
            .querySelector("a")
            .setAttribute("aria-expanded", "false");
          this.parentNode
            .querySelector("button")
            .setAttribute("aria-expanded", "false");
          subMenu.classList.remove("js-dropdown-active");
        }
        event.preventDefault();
      });*/
    });

    // Third level and beyond
    var subMenuItems = document.querySelectorAll(
      ".module-menu ul.module-menu__submenu li"
    );

    Array.prototype.forEach.call(subMenuItems, function (el, i) {
      let activatingA = el.querySelector("a"),
        subMenu = el.querySelector("ul.module-menu__submenu");

      if (!activatingA || !subMenu) return;

      // Add a button
      const btnDropdown = document.createElement("button");
      // Attach event listener directly to the button just created

      btnDropdown.classList.add("third-level-button");

      const span = document.createElement("span");
      span.className = "show-for-sr";
      span.textContent = "show submenu for “" + activatingA.text + "”";
      btnDropdown.appendChild(span);

      activatingA.insertAdjacentElement("afterend", btnDropdown);

      //(btnDropdown);

      btnDropdown.addEventListener("click", function (event) {
        event.preventDefault();
        // console.log("clicked");
        if (!this.parentNode.classList.contains("is-active")) {
          this.parentNode.classList.add("is-active");
          this.parentNode
            .querySelector("a")
            .setAttribute("aria-expanded", "true");
          this.parentNode
            .querySelector("button")
            .setAttribute("aria-expanded", "true");
          subMenu.classList.add("js-dropdown-active");
        } else {
          this.parentNode.classList.remove("is-active");
          this.parentNode
            .querySelector("a")
            .setAttribute("aria-expanded", "false");

          this.parentNode
            .querySelector("button")
            .setAttribute("aria-expanded", "false");
          subMenu.classList.remove("js-dropdown-active");
        }
      });
      activatingA.setAttribute("aria-haspopup", "true");

      subMenu.classList.add("is-dropdown-submenu");
      el.classList.remove("is-active");
      el.classList.add("is-dropdown-submenu-parent");
      el.classList.add("opens-left");
    });
  },

  resourceTabs: function () {
    // Tabs
    [].forEach.call(
      document.getElementsByClassName("tabbed-content"),

      function (tabs_group, i) {
        if (!tabs_group) return;

        let totalItems = tabs_group.querySelectorAll(
          ".tabbed-content__panel"
        ).length;

        let tabsList = tabs_group.getElementsByClassName(
          "tabbed-content__list"
        )[0];
        let tabspanels = tabs_group.getElementsByClassName(
          "tabbed-content__panel"
        );

        if (!tabsList || !tabspanels || tabspanels.length === 0) return;

        // Add an empty span with the classname tabbed-content__background
        let bgSpan = document.createElement("span");
        bgSpan.setAttribute("class", "tab-list__background");
        tabsList.insertAdjacentElement("afterbegin", bgSpan);

        //Add span for the outlines
        let outlineSpan = document.createElement("span");
        outlineSpan.setAttribute("class", "tab-list__outline");
        tabsList.insertAdjacentElement("afterbegin", outlineSpan);

        // Build the List items from the panel headings
        [].forEach.call(tabspanels, function (item, index) {
          if (!item) return;

          let tabHeading = item.querySelector("h3");
          if (!tabHeading) return;

          let listItem = document.createElement("li");
          let listLink = document.createElement("a");

          // Build the list
          listItem.setAttribute("role", "presentation");

          listLink.setAttribute("href", "#panel" + index);
          listLink.setAttribute("role", "tab");
          listLink.setAttribute("aria-controls", "panel" + index);
          listLink.setAttribute("aria-selected", "false");
          listLink.setAttribute("tab-index", "-1");

          // Select the first tab as selected
          if (index == 0) {
            listItem.classList.add("active");
            listLink.setAttribute("aria-selected", "true");
            listLink.setAttribute("tab-index", "0");
          }

          listLink.appendChild(document.createTextNode(tabHeading.textContent));
          listItem.appendChild(listLink);
          tabsList.appendChild(listItem);

          // Set up panels
          item.classList.remove("active");
          item.setAttribute("id", "panel" + index);

          let panelsArray = Array.prototype.slice.call(tabspanels); // convert to array

          listLink.addEventListener(
            "click",
            function (e) {
              let navList = tabs_group.querySelectorAll(
                ".tabbed-content__list li"
              );
              let linkItem = tabs_group.querySelectorAll(
                ".tabbed-content__list li a"
              );

              e.preventDefault();
              [].forEach.call(panelsArray, function (el) {
                if (el) {
                  el.classList.remove("active");
                }
              });
              [].forEach.call(navList, function (el) {
                if (el) {
                  el.classList.remove("active");
                }
              });
              [].forEach.call(linkItem, function (el) {
                if (el) {
                  el.setAttribute("aria-selected", "false");
                }
              });

              if (panelsArray[index]) {
                panelsArray[index].classList.add("active");
              }

              if (listItem) {
                listItem.classList.add("active");

                /*
                bgSpan.style.width = this.offsetWidth + "px";
                bgSpan.style.left = this.offsetLeft + "px";

                if (index == 2) {
                  outlineSpan.style.borderLeftWidth = "1px";
                  outlineSpan.style.borderTopLeftRadius = "30px";
                  outlineSpan.style.borderBottomLeftRadius = "30px";
                  outlineSpan.style.borderRightWidth = "0";
                  outlineSpan.style.borderTopRightRadius = "0";
                  outlineSpan.style.borderBottomRightRadius = "0";
                }
                if (index == 1) {
                  setTimeout(() => {
                    outlineSpan.style.borderLeftWidth = "0px";
                    outlineSpan.style.borderRightWidth = "0px";
                  }, 250);
                }
                if (index == 0 && totalItems > 2) {
                  outlineSpan.style.borderLeftWidth = "0";
                  outlineSpan.style.borderTopLeftRadius = "0";
                  outlineSpan.style.borderBottomLeftRadius = "0";
                  outlineSpan.style.borderRightWidth = "1px";
                  outlineSpan.style.borderTopRightRadius = "30px";
                  outlineSpan.style.borderBottomRightRadius = "30px";
                }
                  */
                setTabStyling();
              }

              if (listLink) {
                listLink.setAttribute("aria-selected", "true");
              }
            },
            false
          );
        });

        // Set up the first panel as active
        if (tabspanels[0]) {
          tabspanels[0].classList.add("active");
        }

        // Function to set tab background and outline positioning
        const setTabStyling = () => {
          let activeTab = tabsList.querySelector("li.active a");
          if (!activeTab) return;
          let index =
            Array.from(tabsList.children).indexOf(activeTab.parentNode) - 2;

          let nextTab = tabsList.querySelector("li:nth-child(4) a");

          let totalItems = tabsList.querySelectorAll("li").length;

          if (nextTab) {
            outlineSpan.style.width = nextTab.offsetWidth + "px";
            outlineSpan.style.left = nextTab.offsetLeft + "px";
          }
          bgSpan.style.width = activeTab.offsetWidth + "px";
          bgSpan.style.left = activeTab.offsetLeft + "px";

          if (index == 2) {
            outlineSpan.style.borderLeftWidth = "1px";
            outlineSpan.style.borderTopLeftRadius = "30px";
            outlineSpan.style.borderBottomLeftRadius = "30px";
            outlineSpan.style.borderRightWidth = "0";
            outlineSpan.style.borderTopRightRadius = "0";
            outlineSpan.style.borderBottomRightRadius = "0";
          }
          if (index == 1) {
            setTimeout(() => {
              outlineSpan.style.borderLeftWidth = "0px";
              outlineSpan.style.borderRightWidth = "0px";
            }, 250);
          }

          if (index == 0 && totalItems > 2) {
            outlineSpan.style.borderLeftWidth = "0";
            outlineSpan.style.borderTopLeftRadius = "0";
            outlineSpan.style.borderBottomLeftRadius = "0";
            outlineSpan.style.borderRightWidth = "1px";
            outlineSpan.style.borderTopRightRadius = "30px";
            outlineSpan.style.borderBottomRightRadius = "30px";
          } else if (index == 0) {
            // only 2 items
            // let nextTabWidth = nextTab.offsetWidth;
            //let activeTabWidth = activeTab.offsetWidth;
            outlineSpan.style.borderLeftWidth = "0";
            outlineSpan.style.borderRightWidth = "0";
            outlineSpan.style.borderTopWidth = "0";
            outlineSpan.style.borderBottomWidth = "0";
            outlineSpan.style.borderTopLeftRadius = "0";
            outlineSpan.style.borderBottomLeftRadius = "0";
            //outlineSpan.style.borderTopRightRadius = "0";
            //outlineSpan.style.borderBottomRightRadius = "0";
            //outlineSpan.style.width = nextTabWidth + "px";
            //outlineSpan.style.left = activeTabWidth + "px";
          }
        };

        // Re-run on browser resize
        window.addEventListener("resize", setTabStyling);
        /*
        // inital positioning of backgrounds
        // Get the width of the active tab and set the background width
        let activeTab = tabsList.querySelector("li.active a");
        let activeTabWidth = 0;
        if (activeTab) {
          activeTabWidth = activeTab.offsetWidth;
          bgSpan.style.width = activeTabWidth + "px";
        }

        // Get the width of the tab after the active tab and set the outline width
        let nextTab = tabsList.querySelector("li.active + li a");

        if (
          nextTab &&
          typeof activeTabWidth !== "undefined" &&
          totalItems > 2
        ) {
          let nextTabWidth = nextTab.offsetWidth;
          outlineSpan.style.borderLeftWidth = "0";
          outlineSpan.style.borderRightWidth = "1px";
          outlineSpan.style.borderTopLeftRadius = "0";
          outlineSpan.style.borderBottomLeftRadius = "0";
          outlineSpan.style.width = nextTabWidth + "px";
          outlineSpan.style.left = activeTabWidth + "px";
        }
          */

        // Initial setup
        setTabStyling();
      }
    );
  },

  tabLists: function () {
    // News Tab Lists
    [].forEach.call(
      document.querySelectorAll(".news-tabs .tab-list"),

      function (tabs_group) {
        if (!tabs_group) return;
        let activeItems = tabs_group.querySelectorAll("li.active");
        // Only proceed if screen width is under 1024px
        function checkScreenAndInitialize() {
          // Remove existing event listeners by cloning and replacing nodes
          activeItems.forEach(function (item) {
            if (item && item.parentNode) {
              let newItem = item.cloneNode(true);
              item.parentNode.replaceChild(newItem, item);
            }
          });

          // Update the reference to the new nodes
          activeItems = tabs_group.querySelectorAll("li.active");
          if (window.innerWidth < 1024) {
            activeItems.forEach(function (item) {
              item.addEventListener("click", function (e) {
                e.preventDefault();

                tabs_group.classList.toggle("tab-list--open");
              });
            });
          } else {
            //set up the background width to match the active tab
            let activeTab = tabs_group.querySelector("li.active a");
            let bgSpan = tabs_group.querySelector(".tab-list__outline");
            if (activeTab && bgSpan) {
              let activeTabIndex = Array.from(tabs_group.children).indexOf(
                activeTab.parentNode
              );
              let activeTabWidth = activeTab.offsetWidth;

              if (activeTabIndex === 1) {
                let nextTab = tabs_group.children[2];
                let nextTabWidth = nextTab ? nextTab.offsetWidth : 0;
                bgSpan.style.width = nextTabWidth + "px";
                bgSpan.style.left = activeTabWidth + "px";
                bgSpan.style.left = activeTabWidth + "px";
                bgSpan.style.borderLeftWidth = "0";
                bgSpan.style.borderRightWidth = "1px";
                bgSpan.style.borderTopLeftRadius = "0";
                bgSpan.style.borderBottomLeftRadius = "0";
              } else if (activeTabIndex === 3) {
                let prevTab = tabs_group.children[2];
                let firstTab = tabs_group.children[1];
                let prevTabWidth = prevTab ? prevTab.offsetWidth : 0;
                let firstTabWidth = firstTab ? firstTab.offsetWidth : 0;
                bgSpan.style.width = prevTabWidth + "px";
                bgSpan.style.left = firstTabWidth + "px";
                bgSpan.style.borderRightWidth = "0";
                bgSpan.style.borderLeftWidth = "1px";
                bgSpan.style.borderTopRightRadius = "0";
                bgSpan.style.borderBottomRightRadius = "0";
              }
            }

            // Remove event listeners by cloning and replacing nodes
            activeItems.forEach(function (item) {
              if (item && item.parentNode) {
                let newItem = item.cloneNode(true);
                item.parentNode.replaceChild(newItem, item);
              }
            });

            // Ensure the tab list is closed
            tabs_group.classList.remove("tab-list--open");
          }
        }

        // Check on initial load
        checkScreenAndInitialize();

        // Check on window resize
        window.addEventListener("resize", checkScreenAndInitialize);
      }
    );

    // Module Tab Lists
    [].forEach.call(
      document.querySelectorAll(".module-tabs .tab-list"),

      function (tabs_group) {
        if (!tabs_group) return;
        let activeItems = tabs_group.querySelectorAll("li.active");
        // Only proceed if screen width is under 1024px
        function checkScreenAndInitialize() {
          // Remove existing event listeners by cloning and replacing nodes
          activeItems.forEach(function (item) {
            if (item && item.parentNode) {
              let newItem = item.cloneNode(true);
              item.parentNode.replaceChild(newItem, item);
            }
          });

          // Update the reference to the new nodes
          activeItems = tabs_group.querySelectorAll("li.active");
          if (window.innerWidth < 640) {
            activeItems.forEach(function (item) {
              item.addEventListener("click", function (e) {
                e.preventDefault();

                tabs_group.classList.toggle("tab-list--open");
              });
            });
          } else {
            //set up the background width to match the active tab
            let activeTab = tabs_group.querySelector("li.active a");
            let bgSpan = tabs_group.querySelector(".tab-list__outline");
            if (activeTab && bgSpan) {
              let activeTabIndex = Array.from(tabs_group.children).indexOf(
                activeTab.parentNode
              );
              let activeTabWidth = activeTab.offsetWidth;

              if (activeTabIndex === 1) {
                let nextTab = tabs_group.children[2];
                let nextTabWidth = nextTab ? nextTab.offsetWidth : 0;
                bgSpan.style.width = nextTabWidth + "px";
                bgSpan.style.left = activeTabWidth + "px";
                bgSpan.style.left = activeTabWidth + "px";
                bgSpan.style.borderLeftWidth = "0";
                bgSpan.style.borderRightWidth = "1px";
                bgSpan.style.borderTopLeftRadius = "0";
                bgSpan.style.borderBottomLeftRadius = "0";
              } else if (activeTabIndex === 3) {
                let prevTab = tabs_group.children[2];
                let firstTab = tabs_group.children[1];
                let prevTabWidth = prevTab ? prevTab.offsetWidth : 0;
                let firstTabWidth = firstTab ? firstTab.offsetWidth : 0;
                bgSpan.style.width = prevTabWidth + "px";
                bgSpan.style.left = firstTabWidth + "px";
                bgSpan.style.borderRightWidth = "0";
                bgSpan.style.borderLeftWidth = "1px";
                bgSpan.style.borderTopRightRadius = "0";
                bgSpan.style.borderBottomRightRadius = "0";
              }
            }

            // Remove event listeners by cloning and replacing nodes
            activeItems.forEach(function (item) {
              if (item && item.parentNode) {
                let newItem = item.cloneNode(true);
                item.parentNode.replaceChild(newItem, item);
              }
            });

            // Ensure the tab list is closed
            tabs_group.classList.remove("tab-list--open");
          }
        }

        // Check on initial load
        checkScreenAndInitialize();

        // Check on window resize
        window.addEventListener("resize", checkScreenAndInitialize);
      }
    );
  },

  quickLinksToggle: function () {
    // console.log("quick links toggle init");
    var quickLinks = document.querySelectorAll(".quick-links"),
      toggleBtn = document.querySelectorAll(".quick-links__toggle");

    for (var i = 0; i < toggleBtn.length; i++) {
      toggleBtn[i].addEventListener("click", function (e) {
        e.preventDefault();

        var $this = this,
          parent = $this.closest(".quick-links");

        if (!parent.classList.contains("quick-links--expanded")) {
          // Open panel
          parent.classList.add("quick-links--expanded");
          $this.setAttribute("aria-expanded", "true");
        } else {
          // Close panel
          parent.classList.remove("quick-links--expanded");
          $this.setAttribute("aria-expanded", "false");
        }

        return false;
      });
    }
  },

  mobileCarousel: function () {
    [].forEach.call(
      document.getElementsByClassName("mobile-only-carousel"),

      function (carousel, i) {
        const breakpoint = window.matchMedia("(min-width:640px)");
        let mobileSwiper;

        const breakpointChecker = function () {
          if (breakpoint.matches === true) {
            const teasers = carousel.querySelectorAll(
              ".resource-module__news-teaser h3"
            );
            const descriptions = carousel.querySelectorAll(
              ".resource-module__news-teaser p.news-excerpt"
            );

            if (mobileSwiper !== undefined) mobileSwiper.destroy(true, true);
            teasers.forEach((h2) => (h2.style.height = "auto"));
            descriptions.forEach((p) => (p.style.height = "auto"));
            return;
          } else if (breakpoint.matches === false) {
            return enableSwiper();
          }
        };

        const enableSwiper = function () {
          // After carousel is initiated, equalize h2 heights
          if (carousel) {
            const teasers = carousel.querySelectorAll(
              ".resource-module__news-teaser h3"
            );
            const descriptions = carousel.querySelectorAll(
              ".resource-module__news-teaser p.news-excerpt"
            );

            if (teasers.length > 0) {
              // Reset heights first
              teasers.forEach((h2) => (h2.style.height = "auto"));

              // Find the tallest h2
              let maxHeight = 0;
              teasers.forEach((h2) => {
                const height = h2.offsetHeight;
                if (height > maxHeight) {
                  maxHeight = height;
                }
              });

              // Apply max height to all h2 elements
              teasers.forEach((h2) => {
                h2.style.height = maxHeight + "px";
              });
            }

            if (descriptions.length > 0) {
              // Reset heights first
              descriptions.forEach((p) => (p.style.height = "auto"));

              // Find the tallest p
              let maxHeight = 0;
              descriptions.forEach((p) => {
                const height = p.offsetHeight;
                if (height > maxHeight) {
                  maxHeight = height;
                }
              });

              // Apply max height to all p elements
              descriptions.forEach((p) => {
                p.style.height = maxHeight + "px";
              });
            }
          }

          mobileSwiper = new Flickity(carousel, {
            cellAlign: "left",
            contain: true,
            prevNextButtons: false,
            pageDots: false,
            threshold: 10,
            grabCursor: true,
            //autoPlay: 4000,
          });
        };

        // keep an eye on viewport size changes
        breakpoint.addEventListener("change", breakpointChecker);
        // kickstart
        breakpointChecker();
      }
    );
  },

  target_clicks: function () {

    //If .module-teaser__target is clicked, go to the link
    const moduleTeaserTargets = document.querySelectorAll('.x-large-teaser__target, .large-teaser__target, .module-panel__news-grid-featured-post,.resource-module__news-teaser');

    moduleTeaserTargets.forEach(function (moduleTeaserTarget) { 
      moduleTeaserTarget.addEventListener('click', function (e) {
        const buttonLink = moduleTeaserTarget.querySelector('.arrow-link');
        const link = buttonLink ? buttonLink.getAttribute('href') : null;
        if (link) {
          window.location.href = link;
        }
      });
    });
   
  },

  // Ajax autocomplete search
  autoCompletateSearch: function () {
    var searchForm = document.getElementsByClassName("searchform");

    [].forEach.call(searchForm, function (form, index) {
      var keywordElements = form.getElementsByClassName("searchform__keyword");
      var datafetchElements = form.getElementsByClassName(
        "searchform__datafetch"
      );

      if (keywordElements[0]) {
        keywordElements[0].addEventListener("keyup", function () {
          var xhr = new XMLHttpRequest();
          xhr.open("POST", autoComplete.autocomplete_ajax_url);
          xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
          );

          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              if (
                datafetchElements[0] &&
                xhr.responseText != "\nInvalid" &&
                xhr.responseText != "Invalid" &&
                xhr.responseText != "\n" &&
                xhr.responseText != "" &&
                xhr.responseText != null &&
                xhr.responseText != undefined
              ) {
                form.classList.add("searchform--active");
                datafetchElements[0].innerHTML = xhr.responseText;
              } else {
                form.classList.remove("searchform--active");
              }
            }
          };

          var keyword = this.value;
          var data =
            "action=data_fetch&keyword=" +
            encodeURIComponent(keyword) +
            "&data_fetch_nonce=" +
            autoComplete.data_fetch_nonce;
          xhr.send(data);
        });
      }
    });
  },

  // Ajax favourites functions
  ajaxFavourites: function () {
    const favouriteButtons = document.querySelectorAll(".add-to-favourites");

    favouriteButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault();

        const postId = this.dataset.id;
        const postType = this.dataset.type;
        const postTitle = this.dataset.name;

        if (!postId || !postType || !postTitle) {
          console.error("Missing required data attributes");
          return;
        }

        const favouriteFormData = new FormData();
        favouriteFormData.append("action", "toggle_favourite");
        favouriteFormData.append("post_id", postId);
        favouriteFormData.append("post_type", postType);
        favouriteFormData.append("post_title", postTitle);
        favouriteFormData.append(
          "favourite_nonce",
          favouriteData.favourite_nonce
        );

        fetch(favouriteData.favourites_ajax_url, {
          method: "POST",
          body: favouriteFormData,
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
              throw new Error("Invalid response format - not JSON");
            }
            return response.json();
          })
          .then((data) => {
            if (data.success) {
              if (data.data.action === "added") {
                // Update all instances of .add-to-favourites on the page
                const allFavouriteButtons = document.querySelectorAll(
                  '.add-to-favourites[data-id="' + postId + '"]'
                );
                allFavouriteButtons.forEach((btn) => {
                  btn.classList.add("add-to-favourites--filled");
                  const span = btn.querySelector("span");
                  const btnSvg = btn.querySelector("svg");
                  if (span) span.textContent = "Remove from 'my favourites'";
                  // Animate button to pulse larger then smaller
                  btnSvg.style.transform = "scale(1.6)";
                  setTimeout(() => {
                    btnSvg.style.transform = "scale(1)";
                  }, 200);
                });
              } else {
                const allFavouriteButtons = document.querySelectorAll(
                  '.add-to-favourites[data-id="' + postId + '"]'
                );
                allFavouriteButtons.forEach((btn) => {
                  btn.classList.remove("add-to-favourites--filled");
                  const span = btn.querySelector("span");
                  const btnSvg = btn.querySelector("svg");
                  if (span) span.textContent = "Add to 'my favourites'";
                  // Animate button to pulse larger then smaller

                  btnSvg.style.transform = "scale(1.6)";
                  setTimeout(() => {
                    btnSvg.style.transform = "scale(1)";
                  }, 200);
                });
              }
            } else {
              console.error("Error:", data.data);
            }
          })
          .catch((error) => {
            console.error("Fetch error:", error);
          });
      });
    });
  },

  keyToggle: function () {
    // Toggle the key
    const keyMobileElements = document.querySelectorAll(
      ".search-results__key-mobile h3"
    );
    keyMobileElements.forEach(function (h3) {
      h3.addEventListener("click", function () {
        this.classList.toggle("open");
      });
    });
  },

  openPopups: function () {
    const loginButtons = document.querySelectorAll(
      ".wp-block-button.is-login, .account-nav-right .is-login"
    );
    const registerButtons = document.querySelectorAll(
      ".wp-block-button.is-register"
    );
    const loginPopup = document.getElementById("login-form-pop-up");
    const registerPopup = document.getElementById("register-form-pop-up");

    if (loginButtons.length > 0 && loginPopup) {
      loginButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
          e.preventDefault();
          loginPopup.classList.add("form-pop-up--open");
          setTimeout(() => {
            loginPopup.classList.add("form-pop-up--open-delay");
          }, 10);
        });
      });
    }

    if (registerButtons.length > 0 && registerPopup) {
      registerButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
          e.preventDefault();
          registerPopup.classList.add("form-pop-up--open");
          setTimeout(() => {
            registerPopup.classList.add("form-pop-up--open-delay");
          }, 10);
        });
      });
    }

    // close popup when clicking the close button or outside the form
    if (loginPopup && registerPopup) {
      const closeButtons = [
        ...loginPopup.querySelectorAll(
          ".form-pop-up__close, .form-pop-up__overlay"
        ),
        ...registerPopup.querySelectorAll(
          ".form-pop-up__close, .form-pop-up__overlay"
        ),
      ];
      closeButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
          e.preventDefault();
          loginPopup.classList.remove("form-pop-up--open-delay");
          registerPopup.classList.remove("form-pop-up--open-delay");
          setTimeout(() => {
            loginPopup.classList.remove("form-pop-up--open");
            registerPopup.classList.remove("form-pop-up--open");
          }, 250);
        });
      });
    }
  },
  // For ajax loading
  getCookie: function (cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  },
};

export default utils;
