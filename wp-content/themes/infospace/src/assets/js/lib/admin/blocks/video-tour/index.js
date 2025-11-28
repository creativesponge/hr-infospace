var video_tour = {
  init: function () {
    this.videoBlock();
  },
  videoBlock: function () {
    /**
     * BLOCK: Video
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
          id="a37d55aa-839f-4dd8-8ac1-09f0c17b98b7"
          data-name="Layer 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 22 15"
        >
          <rect x="13" y="4" width="5" height="1" fill="#fff" />
          <polygon points="19 3 12 3 12 6 19 6 19 3 19 3" fill="#555d65" />
          <rect x="12" y="7" width="7" height="2" fill="#fff" />
          <polygon points="19 7 12 7 12 9 19 9 19 7 19 7" fill="#555d65" />
          <rect x="12" y="10" width="7" height="2" fill="#fff" />
          <polygon
            points="19 10 12 10 12 12 19 12 19 10 19 10"
            fill="#555d65"
          />
          <rect x="4" y="4" width="5" height="4" fill="#fff" />
          <path
            d="M9,8v2H6V8H9m2-2H4v6h7V6Z"
            transform="translate(-1 -3)"
            fill="#555d65"
          />
          <path
            d="M21,5V16H3V5H21m2-2H1V18H23V3Z"
            transform="translate(-1 -3)"
            fill="#555d65"
          />
        </svg>
      );

      const TEMPLATE = [
        [
          "core/heading",
          { level: 2, placeholder: "Enter heading", textAlign: "center" },
          [],
        ],
        [
          "core/buttons",
          { align: "center", layout: { justifyContent: "center" } },
          [],
        ],
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
      registerBlockType("theme/video-tour", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Video tour"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          videoId: {
            type: "string",
          },
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
              <div className="video-tour-container">
                <h1 className="video-tour-heading">
                  <PlainText
                    onChange={(content) =>
                      setAttributes({ mainHeading: content })
                    }
                    value={attributes.mainHeading}
                    placeholder="Enter heading here"
                    className="page-banner-titles-heading-text"
                  />
                </h1>
                <p className="responsive-video">
                  <PlainText
                    onChange={(content) => setAttributes({ videoId: content })}
                    value={attributes.videoId}
                    placeholder="Enter Vimeo video ID here"
                    className="responsive-video-text"
                  />
                </p>
                <div className="video-tour-footer">
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

export default video_tour;
