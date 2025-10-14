var grey_background = {
  init: function () {
    this.greyBackground();
  },
  greyBackground: function () {
    /**
     * BLOCK: Grey background
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */
    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page
      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
      const { InnerBlocks } = wp.blockEditor;
      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="ed5963ba-af69-4d3a-9c9d-0a0be170c42e"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="12.3"
          viewBox="0 0 20 12.3"
        >
          <path
            d="M1,2A57.8,57.8,0,0,1,21,2V13.4a52.6,52.6,0,0,0-20,0Z"
            transform="translate(-1 -1.1)"
            fill="#eee"
          />
          <rect x="5.3" y="2.5" width="8.9" height="2" fill="#555d65" />
          <rect x="5.3" y="5.4" width="8.9" height="1.5" fill="#555d65" />
        </svg>
      );

      const TEMPLATE = [
        ["core/heading", { level: 2, placeholder: "Enter heading" }, []],
        ["core/paragraph", {}, []],
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
      registerBlockType("theme/grey-background", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Grey background"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.

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
              <div className="grey-background-container">
                <InnerBlocks
                  template={TEMPLATE}
                  allowedBlocks={[
                    "core/heading",
                    "core/paragraph",
                    "core/buttons",
                    "theme/image-text",
                  ]}
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

export default grey_background;
