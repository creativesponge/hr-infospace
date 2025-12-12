var site_map = {
  init: function () {
    this.siteMap();
  },
  siteMap: function () {
    /**
     * BLOCK: Site map
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
      const { Button, ToggleControl, PanelBody, PanelRow } = wp.components;

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

      registerBlockType("theme/site-map", {
        // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        title: __("Site map"), // Block title.
        icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: "theme-specific", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
        attributes: {},
        edit: function ({ attributes, className, setAttributes }) {
          return (
            <div className={className + " site-map-block"}>
              <div className="site-map">
                <div><p>Displays the sitemap</p></div>
                <InnerBlocks />
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

export default site_map;
