var testimonials = {
    init: function () {
      this.testimonials_list();
    },
    testimonials_list: function () {
      /**
       * BLOCK: Testimonials carousel
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
          id="becab902-dd61-4602-8011-8360d7f540cb"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="18.8"
          height="11.6"
          viewBox="0 0 18.8 11.6"
        >
          <rect
            x="5.4"
            y="4"
            width="10.6"
            height="2"
            transform="translate(-2.3 -2.6) rotate(-0.8)"
            fill="#555d65"
          />
          <rect width="0.7" height="2" fill="#555d65" />
          <rect x="1.2" width="0.7" height="2" fill="#555d65" />
          <rect x="16.9" y="7.6" width="0.7" height="2" fill="#555d65" />
          <rect x="18.1" y="7.6" width="0.7" height="2" fill="#555d65" />
          <rect x="1.5" y="4.2" width="14" height="2" fill="#555d65" />
          <rect x="1.5" y="7.2" width="14" height="2" fill="#555d65" />
          <rect x="4.5" y="10.1" width="7.9" height="1.5" fill="#555d65" />
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
        registerBlockType("theme/testimonials-carousel", {
          // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
  
          icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
          title: __("Testimonials carousel"), // Block title.
  
          category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  
          edit: function ({ className }) {
            return (
              <div className={className}>
                <div className="cell medium-6">
                  <div className="testomonials-container">
                    <div class="testomonials-message">
                      <p>
                        Displays a Testimonials list. These can be edited in "Testimonials"
                        on the left
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            );
          },
          save: function () {
            null;
          },
        });
      }
    },
  };
  
  export default testimonials;
  