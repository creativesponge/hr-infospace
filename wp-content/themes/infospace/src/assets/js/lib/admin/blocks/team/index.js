var team = {
  init: function () {
    this.team();
  },
  team: function () {
    /**
     * BLOCK: Team list
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
          id="b24ab30f-7570-47df-9082-83bd3257933a"
          data-name="a0e25ff0-08dc-4c8d-bb19-3a4ec433f28c"
          xmlns="http://www.w3.org/2000/svg"
          width="22"
          height="16"
          viewBox="0 0 22 16"
        >
          <rect
            x="1"
            y="1"
            width="20"
            height="14.01"
            fill="none"
            stroke="#555d65"
            stroke-miterlimit="10"
            stroke-width="2"
          />
          <rect x="9.3" y="3.2" width="8.9" height="1.5" fill="#555d65" />
          <rect x="9.3" y="5.7" width="8.9" height="1.5" fill="#555d65" />
          <rect x="9.3" y="8.6" width="8.9" height="1.5" fill="#555d65" />
          <rect x="9.3" y="11" width="8.9" height="1.5" fill="#555d65" />
          <circle cx="5.3" cy="5" r="2.1" fill="#555d65" />
          <circle cx="5.3" cy="10.7" r="2.1" fill="#555d65" />
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
      registerBlockType("theme/team", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.

        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        title: __("team list"), // Block title.

        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.

        edit: function ({ className }) {
          return (
            <div className={className}>
              <div class="team-container">
                <p>
                  Displays a teams list. These can be edited in "People" on the
                  left
                </p>
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

export default team;
