var contact_form = {
  init: function () {
    this.contact_form();
  },
  contact_form: function () {
    /**
     * BLOCK: Contact form
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */

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
      registerBlockType("theme/contact-form", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Contact form"), // Block title.
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
        /**
         * The edit function describes the structure of your block in the context of the editor.
         * This represents what the editor will render when the block is used.
         *
         * The "edit" property must be a valid function.
         *
         * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
         */
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
                    (Ideal size is 1136 x 1136 pixels at 72dpi)
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
                    (Ideal size is 1136 x 1136 pixels at 72dpi)
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
              <div className="contact-form-container">
                <div className="contact-form-inner-container">
                  <InnerBlocks template={TEMPLATE} />
                  <div className="contact-form-image-container">
                    <div
                      className="contact-form-image"
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
                <div class="contact-form-form">
                  <div class="contact-form-message">
                    <p>Displays a contact form.</p>
                  </div>
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
        save: function () {
          return <InnerBlocks.Content />;
        },
      });
    }
  },
};

export default contact_form;
