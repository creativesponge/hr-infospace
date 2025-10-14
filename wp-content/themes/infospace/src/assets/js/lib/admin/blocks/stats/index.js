var stats = {
    init: function() {
        this.stats();
    },
    stats: function() {

      /**
       * BLOCK: Stats
       *
       * Registering a basic block with Gutenberg.
       * Simple block, renders and saves the same content without any interactivity.
       */
      if ( document.body.classList.contains( 'block-editor-page' )) { // check if is a gutenberg page
        const { __ } = wp.i18n; // Import __() from wp.i18n
        const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
        const { InnerBlocks } = wp.blockEditor;

        /**
         * Custom SVG path
        */
        const MyIcon = () => (
          <svg id="a567dea2-29ac-4c41-8e97-8a4c0cca60ee" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 9"><path d="M3,16Z" transform="translate(-2 -8)" fill="#fff"/><polygon points="2 0 0 0 0 9 2 9 2 0 2 0" fill="#555d65"/><rect x="3" y="2" width="6" height="6" fill="#555d65"/><rect x="14" y="2" width="6" height="6" fill="#555d65"/><path d="M14,16Z" transform="translate(-2 -8)" fill="#fff"/><polygon points="13 0 11 0 11 9 13 9 13 0 13 0" fill="#555d65"/></svg>
        );

        const TEMPLATE = [
        	['theme/stats-item', {}, []],
        	['theme/stats-item', {}, []],
          ['theme/stats-item', {}, []],
        ]
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
        registerBlockType( 'theme/stats-list', {
        	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
        	title: __( 'Stats list' ), // Block title.
        	icon: MyIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        	category: 'theme-specific', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.

        	/**
        	 * The edit function describes the structure of your block in the context of the editor.
        	 * This represents what the editor will render when the block is used.
        	 *
        	 * The "edit" property must be a valid function.
        	 *
        	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
        	 */
           edit: function( {className} ) {
        		// Creates a <p class='wp-block-cgb-block-related-posts'></p>.
        		return (
        			<div className={ className }>

                <div className="stats-container">
                  <InnerBlocks template={TEMPLATE} allowedBlocks={ ['theme/stats-item'] } />
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
        	save: function( { attributes } ) {

         		return (
                  <InnerBlocks.Content />
         		);

        	},
        } );

      }
    }
};

export default stats;
