var tabs_container = {
  init: function () {
    this.tabs_block();
  },
  tabs_block: function () {
    /**
     * BLOCK: Tabs
     *
     */
    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page

      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
      const { InnerBlocks, PlainText } = wp.blockEditor;

      /**
       * Custom SVG path
       */
      const MyIcon = () => (
        <svg
          id="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          data-name="Layer 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 22 16"
        >
          <rect x="1" y="1" width="20" height="1" fill="#fff" />
          <polygon points="22 0 0 0 0 3 22 3 22 0 22 0" fill="#555d65" />
          <path
            d="M21,8V18H3V8H21m2-2H1V20H23V6Z"
            transform="translate(-1 -4)"
            fill="#555d65"
          />
        </svg>
      );

      const TEMPLATE = [
        ["theme/tab-blocks-item", {}, []],
        ["theme/tab-blocks-item", {}, []],
        ["theme/tab-blocks-item", {}, []],
      ];
      /**
       * Register: a Gutenberg Block.
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
      registerBlockType("theme/tabs-blocks-item", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("tabs"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          tabsSectionHeading: {
            type: "string",
          },
        },

        /**
         * The edit function describes the structure of your block in the context of the editor.
         * This represents what the editor will render when the block is used.
         *
         * The "edit" property must be a valid function.
         *
         * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
         */
        edit: function ({ attributes, className, setAttributes }) {
          return (
            <div className={className}>
              <h3 className="tabs-heading">
                <PlainText
                  onChange={(content) =>
                    setAttributes({ tabsSectionHeading: content })
                  }
                  value={attributes.tabsSectionHeading}
                  placeholder="Enter the heading for the tabs section"
                  className="tabs-heading-text"
                />
              </h3>

              <div className="tabs-blocks-container">
                <InnerBlocks
                  template={TEMPLATE}
                  allowedBlocks={["theme/tab-blocks-item"]}
                />
              </div>
            </div>
          );
        },

        /**
         * The save function defines the way in which the different attributes should be combined
         * into the final markup, which is then serialized by Gutenberg into post_content.
         *
         * The "save" property must be specified and must be a valid function.
         *
         * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
         */
        save: function ({ attributes }) {
          return <InnerBlocks.Content />;
        },
      });
    }
  },
};

export default tabs_container;
