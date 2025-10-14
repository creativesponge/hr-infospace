var latest_posts = {
  init: function () {
    this.latest_posts_fnc();
  },
  latest_posts_fnc: function () {
    /**
     * BLOCK: Latest posts
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */

    //  Import CSS.
    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page
      
      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
      const { InnerBlocks, PlainText, URLInput, InspectorControls } =
        wp.blockEditor;
      const { SelectControl, ToggleControl, PanelBody, PanelRow } =
        wp.components;
      const withSelect = wp.data.withSelect;

      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="e47c0853-7056-4ef1-98d9-5c7d8c9e0c72"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="22"
          height="10.3"
          viewBox="0 0 22 10.3"
        >
          <rect
            x="1"
            y="1"
            width="4.6"
            height="3.33"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect y="8.8" width="6.6" height="1.5" fill="#555d65" />
          <rect y="6" width="6.6" height="1.96" fill="#555d65" />
          <rect
            x="8.7"
            y="1"
            width="4.6"
            height="3.33"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="7.7" y="8.8" width="6.6" height="1.5" fill="#555d65" />
          <rect x="7.7" y="6" width="6.6" height="1.96" fill="#555d65" />
          <rect
            x="16.4"
            y="1"
            width="4.6"
            height="3.33"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="15.4" y="8.8" width="6.6" height="1.5" fill="#555d65" />
          <rect x="15.4" y="6" width="6.6" height="1.96" fill="#555d65" />
        </svg>
      );
      /**
       * Register: aa Gutenberg Block.
       *
       * Registers a new block provided a unique name and an object defining its
       * behavior. Once registered, the block is made editor as an option to any
       * editor interface where blocks are implemented.
       *
       * @link https://wordpress.org/gutenberg/handbook/block-api/
       * @param  {string}   name     Block name.
       * @param  {Object}   settings Block settings.
       * @return {?WPBlock}          The block, if it has been successfully
       *                             registered; otherwise `undefined`.
       */
      registerBlockType("theme/latest-posts", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.

        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        title: __("Latest posts"), // Block title.

        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          selectedCategory: {
            type: "string",
            default: 0,
          },
          backgroundColor: {
            type: "boolean",
          },
          numberPosts: {
            type: "string",
            default: 3,
          },
          mainHeading: {
            type: "string",
          },
          urlone: {
            type: "string",
          },
          urltwo: {
            type: "string",
          },
          urlthree: {
            type: "string",
          },
          urlfour: {
            type: "string",
          },
          urlfive: {
            type: "string",
          },
          urlsix: {
            type: "string",
          },
        },
        edit: withSelect(function (select) {
          return {
            posts: select("core").getEntityRecords("taxonomy", "category", {
              per_page: -1,
            }),
          };
        })(function (props) {
          // Background
          const set_colour = (state) => {
            props.setAttributes({
              backgroundColor: state,
            });
          };

          let backgroundClass =
            props.attributes.backgroundColor == true ? "grey-back" : "";

          // Categories
          const setStory = (state) => {
            props.setAttributes({
              selectedCategory: state,
            });
          };

          if (!props.posts) {
            return "Loading...";
          }

          if (props.posts.length === 0) {
            return "No posts";
          }

          let options = [{ value: 0, label: __("Select a Category") }];

          for (var i = 0; i < props.posts.length; i++) {
            options.push({
              value: props.posts[i].id,
              label: props.posts[i].name,
            });
          }

          let numberPostsOptions = [
            {
              value: 3,
              label: __("Number to show"),
            },
            {
              value: 2,
              label: __("2"),
            },
            {
              value: 3,
              label: __("3"),
            },
            {
              value: 4,
              label: __("4"),
            },
            {
              value: 6,
              label: __("6"),
            },
          ];

          // Number posts to show
          const setNumberPosts = (state) => {
            props.setAttributes({
              numberPosts: state,
            });
          };
          let numberPostsClass = "show-" + props.attributes.numberPosts;

          return (
            <div
              className={
                props.className + " " + backgroundClass + " " + numberPostsClass
              }
            >
              <InspectorControls>
                <PanelBody title={__("Choose style", "themename")}>
                  <PanelRow>
                    <ToggleControl
                      id="grey-background"
                      label={__("Add grey background", "themename")}
                      checked={props.attributes.backgroundColor}
                      onChange={set_colour}
                    />
                  </PanelRow>
                  <PanelRow>
                    <SelectControl
                      value={props.attributes.numberPosts}
                      label={__("Number of posts to show", "themename")}
                      options={numberPostsOptions}
                      onChange={setNumberPosts}
                    />
                  </PanelRow>
                </PanelBody>
                <PanelBody title={__("Choose stories", "themename")}>
                  <PanelRow>
                    <URLInput
                      value={props.attributes.urlone}
                      label={__("Url for position 1", "themename")}
                      onChange={(url, post) =>
                        props.setAttributes({
                          urlone: url,
                          text: (post && post.title) || "Click here",
                        })
                      }
                    />
                  </PanelRow>
                  <PanelRow>
                    <URLInput
                      value={props.attributes.urltwo}
                      label={__("Url for position 2", "themename")}
                      onChange={(url, post) =>
                        props.setAttributes({
                          urltwo: url,
                          text: (post && post.title) || "Click here",
                        })
                      }
                    />
                  </PanelRow>
                  <PanelRow>
                    <URLInput
                      value={props.attributes.urlthree}
                      label={__("Url for position 3", "themename")}
                      onChange={(url, post) =>
                        props.setAttributes({
                          urlthree: url,
                          text: (post && post.title) || "Click here",
                        })
                      }
                    />
                  </PanelRow>
                  <PanelRow>
                    <URLInput
                      value={props.attributes.urlfour}
                      label={__("Url for position 4", "themename")}
                      onChange={(url, post) =>
                        props.setAttributes({
                          urlfour: url,
                          text: (post && post.title) || "Click here",
                        })
                      }
                    />
                  </PanelRow>
                  <PanelRow>
                    <URLInput
                      value={props.attributes.urlfive}
                      label={__("Url for position 5", "themename")}
                      onChange={(url, post) =>
                        props.setAttributes({
                          urlfive: url,
                          text: (post && post.title) || "Click here",
                        })
                      }
                    />
                  </PanelRow>
                  <PanelRow>
                    <URLInput
                      value={props.attributes.urlsix}
                      label={__("Url for position 6", "themename")}
                      onChange={(url, post) =>
                        props.setAttributes({
                          urlsix: url,
                          text: (post && post.title) || "Click here",
                        })
                      }
                    />
                  </PanelRow>
                </PanelBody>
              </InspectorControls>

              <div className="cell medium-6">
                <h2 className="latest-posts-heading">
                  <PlainText
                    onChange={(content) =>
                      props.setAttributes({ mainHeading: content })
                    }
                    value={props.attributes.mainHeading}
                    placeholder="Enter heading here"
                    className="latest-posts-heading-text"
                  />
                </h2>
                <div className="latest-posts-container">
                  <div class="latest-posts-message">
                    <h2>Displays stories.</h2>
                    <SelectControl
                      value={props.attributes.selectedCategory}
                      label={__(
                        "Choose a category to show stories from or choose stories from the right column"
                      )}
                      options={options}
                      onChange={setStory}
                    />
                  </div>
                </div>
                <InnerBlocks allowedBlocks={["core/buttons"]} />
              </div>
            </div>
          );
        }),

        save: function ({ attributes }) {
          return <InnerBlocks.Content />;
        },
      });
    }
  },
};

export default latest_posts;
