var icon_list_item = {
  init: function () {
    this.iconListItem();
  },
  iconListItem: function () {
    /**
     * BLOCK: Icon list item
     *
     */
    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page

      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
      const { MediaUpload, InnerBlocks } = wp.blockEditor;
      const { Button } = wp.components;

      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="b4e78883-98ac-4958-a457-95e9cc7bc256"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="10.5"
          height="15.6"
          viewBox="0 0 10.5 15.6"
        >
          <rect x="0.8" y="13.6" width="8.9" height="2.08" fill="#555d65" />
          <rect x="0.8" y="11.3" width="8.9" height="1.5" fill="#555d65" />
          <circle cx="5.3" cy="5.3" r="5.3" fill="#555d65" />
        </svg>
      );

      const TEMPLATE = [
        ["core/heading", { level: 3, placeholder: "Enter heading" }, []],
        ["core/paragraph", { placeholder: "Enter smaller text" }, []],
        ["core/buttons", { align: "center", textAlign: "center" }, []],
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
      registerBlockType("theme/icon-list-item", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Icon list item"), // Block title.
        icon: MyIcon, // Block icon
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        parent: ["theme/icon-list"],
        attributes: {
          attachmentId: {
            type: "number",
          },
          backgroundImage: {
            type: "string",
            default: null,
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
          const onRemoveImage = () => {
            setAttributes({
              backgroundImage: undefined,
              attachmentId: undefined,
            });
          };

          const getImageButton = (openEvent) => {
            if (attributes.backgroundImage) {
              return (
                <div className="button-container">
                  <Button onClick={openEvent} className="button button-large">
                    Change image
                  </Button>
                  <Button
                    onClick={onRemoveImage}
                    className="button button-large"
                  >
                    Remove image
                  </Button>
                  <p class="size-square">
                    {" "}
                    (Ideal size is 90 x 90 pixels at 72dpi)
                  </p>
                </div>
              );
            } else {
              return (
                <div className="button-container">
                  <Button onClick={openEvent} className="button button-large">
                    Pick an image
                  </Button>
                  <p class="size-square">
                    {" "}
                    (Ideal size is 90 x 90 pixels at 72dpi)
                  </p>
                </div>
              );
            }
          };

          function onImageSelect(imageObject) {
            if (typeof imageObject.sizes.circlemed != "undefined") {
              setAttributes({
                backgroundImage: imageObject.sizes.circlemed.url,
                attachmentId: imageObject.id,
              });
            } else {
              setAttributes({
                backgroundImage: imageObject.sizes.full.url,
                attachmentId: imageObject.id,
              });
            }
          }

          return (
            <div className={className}>
              <div className="icon-list-item">
                <div className="icon-list-item__image">
                  <div
                    className="icon-list-item__image-container"
                    style={{
                      backgroundImage: `url(${attributes.backgroundImage})`,
                    }}
                  ></div>
                  <MediaUpload
                    onSelect={onImageSelect}
                    type="image"
                    value={attributes.backgroundImage}
                    render={({ open }) => getImageButton(open)}
                  />
                </div>
                <div className="icon-list-item-content">
                  <InnerBlocks template={TEMPLATE} />
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
          return <InnerBlocks.Content />;
        },
      });
    }
  },
};

export default icon_list_item;
