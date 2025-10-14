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

    if (allTermsElement) {
      const pageType = allTermsElement.dataset.filter;
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

    document
      .querySelectorAll(".desktop-menu a, .mobile-menu a")
      .forEach((link) => {
        link.addEventListener("click", () => {
          document.cookie = "typeclient=''";
          document.cookie = "filterclient=''";
        });
      });
  },

  ajaxLinkStats: function () {
    const links = document.querySelectorAll("a[data-link-id]");

    links.forEach((link) => {
      link.addEventListener("click", (e) => {
        const linkId = e.currentTarget.dataset.linkId;
        const linkUrl = e.currentTarget.href;
        const pageTitle = document.title;

        // Send AJAX request to log link click
        fetch(ajaxVars.links_ajax_url + '?action=log_link_click', {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            link_id: linkId,
            link_url: linkUrl,
            page_title: pageTitle,
            nonce: ajaxVars.nonce
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
        const fileName = e.currentTarget.textContent.trim() || document.title;
      
        // Send AJAX request to log download click
        fetch(ajaxVarsDownloads.downloads_ajax_url + '?action=log_download_click', {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            download_id: downloadId,
            download_url: downloadUrl,
            file_name: fileName,
            nonce: ajaxVarsDownloads.downloadsnonce
          }),
        }).catch((error) => {
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
        mainheader = header[0],
        mobilenav = document.querySelectorAll(".off-canvas"),
        mobilemenu = mobilenav[0],
        mobilemenuHeight = paddingTop + marginTop;

      document.body.style.paddingTop = paddingTop + "px";
      mainheader.style.marginTop = marginTop + "px";
      mobilemenu.style.marginTop = mobilemenuHeight + "px";
    }
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
      expand = document.querySelectorAll(".mobile-menu-toggle");

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
        if (document.body.classList.contains("menu-expanded")) {
          expand[0].setAttribute("aria-expanded", "false");
          expand[0].classList.remove("menu-expanded");
          document.body.classList.remove("menu-expanded");
          document.documentElement.classList.remove("menu-expanded");
          offCanvas[0].classList.remove("is-open");
          offCanvas[0].setAttribute("aria-hidden", "true");
          overlay.classList.remove("is-visible");
        } else {
          expand[0].classList.add("menu-expanded");
          expand[0].setAttribute("aria-expanded", "true");
          document.body.classList.add("menu-expanded");
          document.documentElement.classList.add("menu-expanded");
          offCanvas[0].classList.add("is-open");
          offCanvas[0].setAttribute("aria-hidden", "false");
          overlay.classList.add("is-visible");
        }
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

  dropDownMenu: function () {
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
