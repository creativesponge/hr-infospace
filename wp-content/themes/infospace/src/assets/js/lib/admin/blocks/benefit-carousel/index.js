var benefit_carousel = {
  init: function () {
    this.benefitCarousel();
  },
  benefitCarousel: function () {
    /**
     * BLOCK: Benefit carousel
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */
    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page
      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType} = wp.blocks; // Import registerBlockType() from wp.blocks
      const { InnerBlocks, PlainText } = wp.blockEditor;

      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="afbf634e-195b-4a38-9f28-1ef263af47bb"
          data-name="Layer 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 22 16"
        >
          <rect x="6" y="14" width="5" height="2" fill="#fff" />
          <polygon points="11 14 6 14 6 16 11 16 11 14 11 14" fill="#555d65" />
          <rect x="12" y="14" width="5" height="2" fill="#fff" />
          <polygon points="17 14 12 14 12 16 17 16 17 14 17 14" fill="#999" />
          <rect x="3" y="5" width="7" height="2" fill="#fff" />
          <polygon points="10 5 3 5 3 7 10 7 10 5 10 5" fill="#555d65" />
          <rect x="3" y="8" width="7" height="2" fill="#fff" />
          <polygon points="10 8 3 8 3 10 10 10 10 8 10 8" fill="#555d65" />
          <path
            d="M21,8v7H3V8H21m2-2H1V17H23V6Z"
            transform="translate(-1 -4)"
            fill="#555d65"
          />
          <rect x="13" y="1" width="8" height="8" fill="#fff" />
          <path
            d="M21,6v6H15V6h6m2-2H13V14H23V4Z"
            transform="translate(-1 -4)"
            fill="#555d65"
          />
        </svg>
      );

      const TEMPLATE = [
        ["theme/benefit-slide", {}, []],
        ["theme/benefit-slide", {}, []],
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
      registerBlockType("theme/benefit-carousel-list", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Benefit carousel"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          mainHeading: {
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
              <div className="benefit-carousel-container">
              <h2 className="image-text-carousel-heading">
                  <PlainText
                    onChange={(content) =>
                      setAttributes({ mainHeading: content })
                    }
                    value={attributes.mainHeading}
                    placeholder="Enter heading here"
                    className="image-text-carousel-heading-text"
                  />
                </h2>
                <InnerBlocks
                  template={TEMPLATE}
                  allowedBlocks={["theme/benefit-slide"]}
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

export default benefit_carousel;
