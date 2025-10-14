var posts_list = {
  init: function () {
    this.posts_list_fnc();
  },
  posts_list_fnc: function () {
    /**
     * BLOCK: Posts list
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */

    //  Import CSS.
    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page
      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
      const { InspectorControls } = wp.blockEditor;
      const { SelectControl, PanelBody, PanelRow } = wp.components;
      const withSelect = wp.data.withSelect;

      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="f205da02-07bc-4773-a7cd-1e9aa2df7fbc"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="22.1"
          height="21.6"
          viewBox="0 0 22.1 21.6"
        >
          <rect x="0.1" y="8.2" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="1"
            y="1"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="8" y="8.2" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="8.9"
            y="1"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="15.8" y="8.2" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="16.7"
            y="1"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="0.1" y="19.7" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="1"
            y="12.5"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="8" y="19.7" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="8.9"
            y="12.5"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="15.8" y="19.7" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="16.7"
            y="12.5"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
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
      registerBlockType("theme/posts-list", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.

        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        title: __("Posts list"), // Block title.

        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          selectedCategory: {
            type: "string",
            default: 0,
          },
         
          numberPosts: {
            type: "string",
            default: 9,
          },
        },
        edit: withSelect(function (select) {
          return {
            posts: select("core").getEntityRecords("taxonomy", "category", {
              per_page: -1,
            }),
          };
        })(function (props) {
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
              value: 9,
              label: __("Number to show"),
            },
            {
              value: 6,
              label: __("6"),
            },
            {
              value: 9,
              label: __("9"),
            },
            {
              value: 12,
              label: __("12"),
            },
          ];

          const setNumberPosts = (state) => {
            props.setAttributes({
              numberPosts: state,
            });
          };
          

          return (
            <div className={props.className}>
              <div className="cell medium-6">
                <InspectorControls>
                  <PanelBody title={__("Choose style", "themename")}>
                    <PanelRow>
                      <SelectControl
                        value={props.attributes.numberPosts}
                        label={__("Number of posts to show", "themename")}
                        options={numberPostsOptions}
                        onChange={setNumberPosts}
                      />
                    </PanelRow>
                  </PanelBody>
                  
                </InspectorControls>
                <div className="posts-list-container">
                  <div class="posts-list-message">
                    <h2>Displays a grid of posts.</h2>
                    <SelectControl
                      value={props.attributes.selectedCategory}
                      label={__(
                        "Choose a category to show"
                      )}
                      options={options}
                      onChange={setStory}
                    />
                    
                  </div>
                </div>
              </div>
            </div>
          );
        }),

        save: function ({ attributes }) {
          null;
        },
      });
    }
  },
};

export default posts_list;
