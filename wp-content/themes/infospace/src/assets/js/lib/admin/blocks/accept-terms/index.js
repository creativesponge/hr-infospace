var accept_terms = {
  init: function () {
    this.accept_terms();
  },
  accept_terms: function () {
    /**
     * BLOCK: Accept terms
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
          id="b258f051-34b2-4cb4-8fc1-acaf9d66218a"
          data-name="Layer 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 22 8"
        >
          <rect x="12" width="10" height="2" fill="#fff" />
          <polygon points="22 0 12 0 12 2 22 2 22 0 22 0" fill="#555d65" />
          <rect width="10" height="2" fill="#fff" />
          <polygon points="10 0 0 0 0 2 10 2 10 0 10 0" fill="#555d65" />
          <rect x="12" y="3" width="10" height="2" fill="#fff" />
          <polygon points="22 3 12 3 12 5 22 5 22 3 22 3" fill="#555d65" />
          <rect y="3" width="10" height="2" fill="#fff" />
          <polygon points="10 3 0 3 0 5 10 5 10 3 10 3" fill="#555d65" />
          <rect y="6" width="7" height="2" fill="#fff" />
          <polygon points="7 6 0 6 0 8 7 8 7 6 7 6" fill="#555d65" />
        </svg>
      );

      const TEMPLATE = [
        ["core/heading", { level: 3, placeholder: "Enter heading" }, []],
        ["core/paragraph", { placeholder: "Enter content" }, []],
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
      registerBlockType("theme/accept-terms", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Accept terms"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {},
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
              <p>Enter terms and conditions wording below</p>
              <div className="accept-terms-container">
                <InnerBlocks />
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

export default accept_terms;
