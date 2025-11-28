var image_text_banner = {
  init: function () {
    this.imageText();
  },
  imageText: function () {
    /**
     * BLOCK: Image text
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */

    //  Import CSS.

    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page
      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

      const { MediaUpload, InspectorControls, InnerBlocks } = wp.blockEditor;
      const { Button, PanelBody, PanelRow } = wp.components;

    
      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="uuid-fb1c3213-d082-428f-bfea-89667c3a1e64"
          xmlns="http://www.w3.org/2000/svg"
          width="22"
          height="11.4"
          viewBox="0 0 22 11.4"
        >
          <rect width="22" height="11.3" fill="#d3d3d3" />
          <rect
            x="11"
            y=".9"
            width="10"
            height="9.8"
            fill="#fff"
            stroke="#1e1e1e"
            stroke-miterlimit="10"
            stroke-width="1.5"
          />
          <rect x="1" y="2.1" width="7.9" height="2" fill="#1e1e1e" />
          <rect x="1" y="5.6" width="7.9" height="1.4" fill="#1e1e1e" />
          <rect x="1" y="8.5" width="7.9" height="1.4" fill="#1e1e1e" />
        </svg>
      );

      const TEMPLATE = [
        ["core/heading", { level: 3, placeholder: "Enter heading" }, []],
        ["core/paragraph", {}, []],
        ["core/buttons", { align: "left", textAlign: "left" }, []],
      ];

      /*
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

      registerBlockType("theme/image-text-banner", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Text and image banner"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          attachmentId: {
            type: "number",
          },
          backgroundImage: {
            type: "string",
            default: null,
          },
          attachmentIdMob: {
            type: "number",
          },
          backgroundImageMob: {
            type: "string",
            default: null,
          },
        },
        edit: function ({ attributes, className, setAttributes }) {
          // Mobile image
          const onRemoveImageMob = () => {
            setAttributes({
              backgroundImageMob: undefined,
            });
          };

          const getImageButtonMob = (openEvent) => {
            if (attributes.backgroundImageMob) {
              return (
                <div className="button-container">
                  <Button onClick={openEvent} className="button button-large">
                    Change mobile background image
                  </Button>
                  <Button
                    onClick={onRemoveImageMob}
                    className="button button-large"
                  >
                    Remove mobile background image
                  </Button>
                  <p class="size-square">
                    {" "}
                    (Ideal size is 640 x 910 pixels at 72dpi)
                  </p>
                </div>
              );
            } else {
              return (
                <div className="button-container">
                  <Button onClick={openEvent} className="button button-large">
                    Pick a mobile background image
                  </Button>
                  <p class="size-square">
                    {" "}
                    (Ideal size is 640 x 910 pixels at 72dpi)
                  </p>
                </div>
              );
            }
          };

          function onImageSelectMob(imageObject) {
            setAttributes({
              backgroundImageMob: imageObject.sizes.full.url,
              attachmentIdMob: imageObject.id,
            });
          }

          //Desktop image
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
                    (Ideal size is 1873 x 1873 pixels at 72dpi)
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
                    (Ideal size is 1873 x 1873 pixels at 72dpi)
                  </p>
                </div>
              );
            }
          };

          function onImageSelect(imageObject) {
            setAttributes({
              backgroundImage: imageObject.sizes.full.url,
              attachmentId: imageObject.id,
            });
          }

          return (
            <div className={className }>
            

              <div className="image-text-banner">
                 <InspectorControls>
                <PanelBody title={__("Mobile", "themename")}>
                  <div
                    class="wp-block-theme-banner-carousel__mobile-preview"
                    style={{
                      backgroundImage: `url(${attributes.backgroundImageMob})`,
                    }}
                  ></div>
                  <PanelRow>
                    <MediaUpload
                      onSelect={onImageSelectMob}
                      type="image"
                      value={attributes.backgroundImageMob}
                      render={({ open }) => getImageButtonMob(open)}
                    />
                  </PanelRow>
                </PanelBody>
              </InspectorControls>
                <InnerBlocks template={TEMPLATE} />
                <div className="image-text-banner-image-container">
                  <div
                    className="image-text-banner-image"
                    style={{
                      backgroundImage: `url(${attributes.backgroundImage})`,
                    }}
                  >
                    <span></span>
                  </div>

                  <MediaUpload
                    onSelect={onImageSelect}
                    type="image"
                    value={attributes.backgroundImage}
                    render={({ open }) => getImageButton(open)}
                  />
                </div>
              </div>
            </div>
          );
        },

        save: function ({ attributes }) {
          return <InnerBlocks.Content />;
        },
      });
    }
  }
};

export default image_text_banner;
