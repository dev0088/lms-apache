( function( api ) {

	// Extends our custom "portfolio-web" section.
	api.sectionConstructor['portfolio-web'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );