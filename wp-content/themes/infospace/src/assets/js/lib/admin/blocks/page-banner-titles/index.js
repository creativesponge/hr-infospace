var page_banner_titles = {
  init: function () {
    this.page_banner_titles_ftn();
  },
  page_banner_titles_ftn: function () {
    /**
     * BLOCK: Page banner
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */

    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page

      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

      const { InnerBlocks, MediaUpload, InspectorControls, PlainText } =
        wp.blockEditor;
      const { Button, PanelBody, PanelRow } = wp.components;

      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="bc90d660-5b2a-4dc8-b1fb-3bc11e807f64"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="22"
          height="13.4"
          viewBox="0 0 22 13.4"
        >
          <rect
            x="1"
            y="1"
            width="20"
            height="11.4"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="6.5" y="8.4" width="8.9" height="2" fill="#555d65" />
          <rect x="7.5" y="5.9" width="7" height="1.5" fill="#555d65" />
        </svg>
      );

      const TEMPLATE = [["core/buttons", {}, []]];
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
      registerBlockType("theme/page-banner-titles", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Page banner with titles"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          attachmentIdMob: {
            type: "number",
          },
          backgroundImageMob: {
            type: "string",
            default: null,
          },
          attachmentId: {
            type: "number",
          },
          backgroundImage: {
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
          backgroundImageXlarge: {
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
          mainHeading: {
            type: "string",
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

          //Background image
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
                    (Ideal size is 1400 x 700 pixels at 72dpi)
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
                    (Ideal size is 1400 x 700 pixels at 72dpi)
                  </p>
                </div>
              );
            }
          };

          function onImageSelect(imageObject) {
            if (typeof imageObject.sizes.fpsmall != "undefined") {
              setAttributes({
                backgroundImage: imageObject.sizes.fpsmall.url,
                attachmentId: imageObject.id,
              });
            } else {
              setAttributes({
                backgroundImage: imageObject.sizes.full.url,
                attachmentId: imageObject.id,
              });
            }

            if (typeof imageObject.sizes.fpmedium != "undefined") {
              setAttributes({
                backgroundImageMedium: imageObject.sizes.fpmedium.url,
              });
            } else {
              setAttributes({
                backgroundImageMedium: imageObject.sizes.full.url,
              });
            }
            if (typeof imageObject.sizes.fplarge != "undefined") {
              setAttributes({
                backgroundImageLarge: imageObject.sizes.fplarge.url,
              });
            } else {
              setAttributes({
                backgroundImageLarge: imageObject.sizes.full.url,
              });
            }
            if (typeof imageObject.sizes.fpxlarge != "undefined") {
              setAttributes({
                backgroundImageXlarge: imageObject.sizes.fpxlarge.url,
              });
            } else {
              setAttributes({
                backgroundImageXlarge: imageObject.sizes.full.url,
              });
            }
          }

          return (
            <div className={className}>
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

              <MediaUpload
                onSelect={onImageSelect}
                type="image"
                value={attributes.backgroundImage}
                render={({ open }) => getImageButton(open)}
              />

              <div className="banner-text">
                <h1 className="page-banner-titles-heading">
                  <PlainText
                    onChange={(content) =>
                      setAttributes({ mainHeading: content })
                    }
                    value={attributes.mainHeading}
                    placeholder="Enter heading here"
                    className="page-banner-titles-heading-text"
                  />
                </h1>
                <div className="narrow-content-container">
                  <InnerBlocks template={TEMPLATE} />
                </div>
              </div>
              <div className="banner-page-title__image" style={{ backgroundImage: `url(${attributes.backgroundImage})` }}>

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

export default page_banner_titles;
