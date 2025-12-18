var small_blocks_container = {
  init: function () {
    this.smallblocksContainer();
  },
  smallblocksContainer: function () {
    /**
     * BLOCK: Small Blocks Container
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */

    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page
      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
      const { InnerBlocks, PlainText, RichText } = wp.blockEditor;

      /**
       * Custom SVG path
       */
      const MyIcon = () => (
        <svg
          id="a6a49eea-be80-4f7b-b0f0-86139954ce4f"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="22"
          height="11.3"
          viewBox="0 0 22 11.3"
        >
          <rect
            x="1"
            y="1"
            width="20"
            height="9.34"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <circle cx="4.7" cy="4.6" r="1.9" fill="#555d65" />
          <circle cx="8.9" cy="4.6" r="1.9" fill="#555d65" />
          <circle cx="13.2" cy="4.6" r="1.9" fill="#555d65" />
          <circle cx="17.3" cy="4.6" r="1.9" fill="#555d65" />
          <rect x="2.8" y="7.4" width="3.8" height="1.14" fill="#555d65" />
          <rect x="7" y="7.4" width="3.8" height="1.14" fill="#555d65" />
          <rect x="11.2" y="7.4" width="3.8" height="1.14" fill="#555d65" />
          <rect x="15.5" y="7.4" width="3.8" height="1.14" fill="#555d65" />
        </svg>
      );

      const TEMPLATE = [
        ["theme/small-block", {}, []],
        ["theme/small-block", {}, []],
        ["theme/small-block", {}, []],
        ["theme/small-block", {}, []],
      ];
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
      registerBlockType("theme/small-blocks-container", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Small blocks container"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          mainHeading: {
            type: "string",
          },
          footerText: {
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
          // Creates a <p class='wp-block-cgb-block-related-posts'></p>.
          return (
            <div className={className}>
              <h2 className="image-text-carousel-heading">
                  <PlainText
                    onChange={(content) =>
                      setAttributes({ mainHeading: content })
                    }
                    value={attributes.mainHeading}
                    placeholder="Enter heading here"
                    className="small-blocks-container__heading-text"
                  />
                </h2>
              <div className="small-blocks-container">
                <InnerBlocks
                  template={TEMPLATE}
                  allowedBlocks={["theme/small-block"]}
                />
              </div>
              <h3 className="small-blocks-container__footer">
                  <RichText
                    onChange={(content) =>
                      setAttributes({ footerText: content })
                    }
                    value={attributes.footerText}
                    placeholder="Enter footer text here"
                    className="small-blocks-container__footer-text"
                  />
                </h3>
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

export default small_blocks_container;
