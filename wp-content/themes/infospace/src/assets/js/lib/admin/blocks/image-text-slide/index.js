var image_text_slide = {
  init: function () {
    this.imageTextSlide();
  },
  imageTextSlide: function () {
    /**
     * BLOCK: Image text slide
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */

    //  Import CSS.

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
          id="a7c1ed77-9d0a-4c08-b411-c38ec1207c06"
          data-name="Layer 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 22 13"
        >
          <rect x="3" y="5" width="7" height="2" fill="#fff" />
          <polygon points="10 5 3 5 3 7 10 7 10 5 10 5" fill="#555d65" />
          <rect x="3" y="8" width="7" height="2" fill="#fff" />
          <polygon points="10 8 3 8 3 10 10 10 10 8 10 8" fill="#555d65" />
          <path
            d="M21,9v7H3V9H21m2-2H1V18H23V7Z"
            transform="translate(-1 -5)"
            fill="#555d65"
          />
          <rect x="13" y="1" width="8" height="8" fill="#fff" />
          <path
            d="M21,7v6H15V7h6m2-2H13V15H23V5Z"
            transform="translate(-1 -5)"
            fill="#555d65"
          />
        </svg>
      );

      const TEMPLATE = [
        ["core/heading", { level: 3, placeholder: "Enter heading" }, []],
        ["core/paragraph", {}, []],
        ["core/buttons", { align: "left", textAlign: "left" }, []],
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
      registerBlockType("theme/image-text-slide", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Image text slide"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        parent: ["theme/image-text-carousel-list"],
        attributes: {
          attachmentId: {
            type: "number",
          },
          backgroundImage: {
            type: "string",
            default: null,
          },
          backgroundImageSmall: {
            type: "string",
            default: null,
          },
          backgroundImageMedium: {
            type: "string",
            default: null,
          },
          backgroundImageLarge: {
            type: "string",
            default: null,
          },
        },
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
                    Change background image
                  </Button>
                  <Button
                    onClick={onRemoveImage}
                    className="button button-large"
                  >
                    Remove background image
                  </Button>
                  <p class="size-square">
                    {" "}
                    (Ideal size is 500 x 500 pixels at 72dpi)
                  </p>
                </div>
              );
            } else {
              return (
                <div className="button-container">
                  <Button onClick={openEvent} className="button button-large">
                    Pick a background image
                  </Button>
                  <p class="size-square">
                    {" "}
                    (Ideal size is 500 x 500 pixels at 72dpi)
                  </p>
                </div>
              );
            }
          };

          function onImageSelect(imageObject) {
            if (typeof imageObject.sizes.parentimage != "undefined") {
              setAttributes({
                backgroundImage: imageObject.sizes.parentimage.url,
                attachmentId: imageObject.id,
              });
            } else {
              setAttributes({
                backgroundImage: imageObject.sizes.full.url,
                attachmentId: imageObject.id,
              });
            }
            if (typeof imageObject.sizes.small != "undefined") {
              setAttributes({
                backgroundImageSmall: imageObject.sizes.small.url,
              });
            } else {
              setAttributes({
                backgroundImageSmall: imageObject.sizes.full.url,
              });
            }
            if (typeof imageObject.sizes.medium != "undefined") {
              setAttributes({
                backgroundImageMedium: imageObject.sizes.medium.url,
              });
            } else {
              setAttributes({
                backgroundImageMedium: imageObject.sizes.full.url,
              });
            }
            if (typeof imageObject.sizes.large != "undefined") {
              setAttributes({
                backgroundImageLarge: imageObject.sizes.large.url,
              });
            } else {
              setAttributes({
                backgroundImageLarge: imageObject.sizes.full.url,
              });
            }
          }

          return (
            <div className={className}>
              <div className="image-text">
                <InnerBlocks template={TEMPLATE} />
              </div>

              <div
                className="image-container"
                style={{
                  backgroundImage: `url(${attributes.backgroundImage})`,
                }}
              >
                <MediaUpload
                  onSelect={onImageSelect}
                  type="image"
                  value={attributes.backgroundImage}
                  render={({ open }) => getImageButton(open)}
                />
              </div>
            </div>
          );
        },

        save: function ({ attributes }) {
          return <InnerBlocks.Content />;
        },
      });
    }
  },
};

export default image_text_slide;
