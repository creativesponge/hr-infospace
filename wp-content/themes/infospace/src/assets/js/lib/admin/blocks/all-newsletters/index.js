var all_newsletters = {
  init: function () {
    this.all_newsletters_fnc();
  },
  all_newsletters_fnc: function () {
    /**
     * BLOCK: All newsletters
     *
     * Registering a basic block with Gutenberg.
     * Simple block, renders and saves the same content without any interactivity.
     */

    //  Import CSS.
    if (document.body.classList.contains("block-editor-page")) {
      // check if is a gutenberg page
      const { __ } = wp.i18n; // Import __() from wp.i18n
      const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

      /**
       * Custom SVG path
       */

      const MyIcon = () => (
        <svg
          id="f205da02-07bc-4773-a7cd-1e9aa2df7fbc"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="22.1"
          height="21.6"
          viewBox="0 0 22.1 21.6"
        >
          <rect x="0.1" y="8.2" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="1"
            y="1"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="8" y="8.2" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="8.9"
            y="1"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="15.8" y="8.2" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="16.7"
            y="1"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="0.1" y="19.7" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="1"
            y="12.5"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="8" y="19.7" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="8.9"
            y="12.5"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="15.8" y="19.7" width="6.3" height="1.93" fill="#555d65" />
          <rect
            x="16.7"
            y="12.5"
            width="4.4"
            height="5.06"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
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
      registerBlockType("theme/all-newsletters", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.

        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        title: __("All newsletters"), // Block title.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {
          
        },
        edit: function ({ attributes, className, setAttributes }) {
            // Creates a <p class='wp-block-cgb-block-related-posts'></p>.
            
  
  
            return (
              <div className={className}>
                <div className="breadcrumbs">
                  Shows all favourites page
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
            return null;
          },
      });
    }
  },
};

export default all_newsletters;
