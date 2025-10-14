var narrow_content = {
  init: function () {
    this.narrow_content_ftn();
  },
  narrow_content_ftn: function () {
    /**
     * BLOCK: Narrow content
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */
    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page
      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

      const { InnerBlocks, InspectorControls } = wp.blockEditor;
      const { ToggleControl, PanelBody, PanelRow } = wp.components;

      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="uuid-26632871-21a4-4faf-9856-b8f7aaccbfeb"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="9.6"
          height="12.1"
          viewBox="0 0 9.6 12.1"
        >
          <rect width="6.9" height="1.8" fill="#555d65" />
          <rect y="3" width="9.6" height="1.6" fill="#555d65" />
          <rect y="5.5" width="9.6" height="1.6" fill="#555d65" />
          <rect y="8" width="9.6" height="1.6" fill="#555d65" />
          <rect y="10.5" width="9.6" height="1.6" fill="#555d65" />
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
      registerBlockType("theme/narrow-content", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Narrow content"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          contentWidth: {
            type: "boolean",
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
          const setWidth = (state) => {
            setAttributes({
              contentWidth: state,
            });
          };

          let widthClass = attributes.contentWidth == true ? " narrow" : "";

          return (
            <div className={className + widthClass}>
              <InspectorControls>
                <PanelBody title={__("Choose style", "themename")}>
                  <PanelRow>
                    <ToggleControl
                      id="narrow-content-toggle"
                      label={__("Very narrow", "themename")}
                      checked={attributes.contentWidth}
                      onChange={setWidth}
                    />
                  </PanelRow>
                </PanelBody>
              </InspectorControls>

              <div className="narrow-content-container">
                <InnerBlocks template={TEMPLATE} />
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

export default narrow_content;
