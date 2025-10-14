var logo_list = {
    init: function () {
      this.logoList();
    },
    logoList: function () {
      /**
       * BLOCK: Logo list
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
            id="uuid-8a0ce466-34f4-4c04-a516-9f04f9a98f7d"
            xmlns="http://www.w3.org/2000/svg"
            width="22"
            height="17.4"
            viewBox="0 0 22 17.4"
          >
            <rect
              x=".8"
              y=".8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
            <rect
              x="8.4"
              y=".8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
            <rect
              x="16"
              y=".8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
            <rect
              x=".8"
              y="6.8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
            <rect
              x="8.4"
              y="6.8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
            <rect
              x="16"
              y="6.8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
            <rect
              x=".8"
              y="12.8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
            <rect
              x="8.4"
              y="12.8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
            <rect
              x="16"
              y="12.8"
              width="5.3"
              height="3.8"
              fill="#fff"
              stroke="#1e1e1e"
              stroke-miterlimit="10"
              stroke-width="1.5"
            />
          </svg>
        );
  
        const TEMPLATE = [
          ["core/image", { align: "center", textAlign: "center" }, []],
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
        registerBlockType("theme/logo-list", {
          // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
          title: __("Logo list"), // Block title.
          icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
          category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
          attributes: {
           
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
                <div className="logo-list-container">
                  <InnerBlocks
                    template={TEMPLATE}
                    allowedBlocks={["core/image", "core/paragraph"]}
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
  
  export default logo_list;
  