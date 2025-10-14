var share_block = {
    init: function () {
      this.share_block();
    },
    share_block: function () {
      /**
       * BLOCK: Share block
       *
       * Registering a basic block with Gutenberg.
       * Simple block, renders and saves the same content without any interactivity.
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
            id="fce3c85e-eb35-4006-a3a7-01d787b9973b"
            data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
            xmlns="http://www.w3.org/2000/svg"
            width="14.3"
            height="2.1"
            viewBox="0 0 14.3 2.1"
          >
            <rect x="6" width="2.3" height="2.07" fill="#555d65" />
            <rect x="9" width="2.3" height="2.07" fill="#555d65" />
            <rect x="12.1" width="2.3" height="2.07" fill="#555d65" />
            <rect y="0.2" width="4.6" height="1.71" fill="#555d65" />
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
        registerBlockType("theme/share-block", {
          // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
          title: __("Share block"), // Block title.
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
            
            return (
              <div className={className}>
       
                  <div className="share-block-container">
                    <h2 className="share-block-heading">
                      <PlainText
                        onChange={(content) =>
                          setAttributes({ mainHeading: content })
                        }
                        value={attributes.mainHeading}
                        placeholder="Enter heading here"
                        className="share-block-heading-text"
                      />
                    </h2>

                    <div classNmae="share-block-container ">
                      <h2>Displays the share block</h2>
                    </div>
                    
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
            return (
                <InnerBlocks.Content />
            );
          },
        });
      }
    },
  };
  
  export default share_block;
  