var google_map = {
    init: function() {
        this.googleMap();
    },
    googleMap: function() {

      /**
       * BLOCK: Google map
       *
       * Registering a basic block with Gutenberg.
       * Simple block, renders and saves the same content without any interactivity.
       */

      //  Import CSS.

      if ( document.body.classList.contains( 'block-editor-page' )) { // check if is a gutenberg page

        const { __ } = wp.i18n; // Import __() from wp.i18n
        const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
        const { PlainText } = wp.blockEditor;
  
        /**
         * Custom SVG path
        */
        const MyIcon = () => (
          <svg id="a8d82dbc-fb00-43e8-8754-41adc30a7b04" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="22" height="12" viewBox="0 0 22 12"><path d="M21,9v8H3V9H21m2-2H1V19H23V7Z" transform="translate(-1 -7)" fill="#555d65"/><polygon points="12 4 9 4 9 7 12 7 13.5 5.4 12 4" fill="#555d65"/></svg>
        );
        /*
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

        registerBlockType( 'theme/google-map', {
          // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
          title: __( 'Google map' ), // Block title.
          icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
          category: 'theme-specific', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.

          attributes: {
            googleKey: {
              type: 'string',
            },
            mapLong: {
              type: 'string',
            },
            mapLat: {
              type: 'string',
            },
          },

          edit: function( {attributes, className, setAttributes} ) {
            
            return (
              <div className={ className }>
                <h3>Displays the map using the longitude and latitude fields to the below.</h3>
                <p className="google-map-heading"><PlainText
                onChange={ content => setAttributes({ googleKey: content }) }
                value={ attributes.googleKey }
                placeholder="Enter Google api key"
                className="google-map-heading-text"
                /></p>
                <p className="google-map-heading"><PlainText
                onChange={ content => setAttributes({ mapLong: content }) }
                value={ attributes.mapLong }
                placeholder="Enter longitude"
                className="google-map-heading-text"
                /></p>
                <p className="google-map-strapline"><PlainText
                  onChange={ content => setAttributes({ mapLat: content }) }
                  value={ attributes.mapLat }
                  placeholder="Enter latitude"
                  className="google-map-strapline-text"
                /></p>
              </div>
            );
          },

          save: function( attributes ) {
            return null;
          },
        } );

      }
    }
};

export default google_map;
