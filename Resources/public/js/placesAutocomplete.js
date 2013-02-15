
/*
 * JulLocationBundle Symfony package.
 *
 * © 2013 Julien Tord <http://github.com/youlweb/JulLocationBundle>
 *
 * Full license information in the LICENSE text file distributed
 * with this source code.
 *
 */

function GmapInit( mapDiv, mapOptions, acFields, topLevel, zoomResolved, latitude, longitude, photoSelectorText, photoSelectedText, jsFieldIds ){
	
	/*
	 * Create map if map DIV exists
	 */
	if( ( mapDivElement = document.getElementById( mapDiv ) ) !== null )
	{
		// Add default center if needed
		mapOptions.center = new google.maps.LatLng( latitude, longitude );
		
		// Add default map type if needed
		if( mapOptions.mapTypeId == null ) mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
		
		var map = new google.maps.Map(mapDivElement, mapOptions);
		
		var infowindow = new google.maps.InfoWindow();
		var marker = new google.maps.Marker({map: map});
		
		// If latitude/longitude is set in form, place marker
		if( ( componentField = document.getElementById( eval( 'jsFieldIds.' + topLevel + '.latitude' ) ) ) !== null && componentField.value != 0 )
		{
			marker.setVisible(true);
			marker.setPosition(mapOptions.center);
		}
	}
	else
	{
		var map = false;
	}
	
	/*
	 * Autocomplete field(s) setup
	 */
	var acInput = new Array();
	var autoComplete = new Array();
	
	for( var acCount = 0; acCount < acFields.length; acCount ++ )
	{
		// Autocomplete input field
		acInput[ acCount ] = document.getElementById( acFields[ acCount ].acInput );
		
		autoComplete[ acCount ] = new google.maps.places.Autocomplete( acInput[ acCount ], acFields[ acCount ].acOptions );
	
		/*
		 * Caputre [ENTER] key press if required
		 */
		if( typeof acFields[ acCount ].captureEnter === 'undefined' || acFields[ acCount ].captureEnter )
		{
			google.maps.event.addDomListener( acInput[ acCount ], 'keydown', function(e) { if (e.keyCode == 13) { if (e.preventDefault) e.preventDefault(); else{ e.cancelBubble = true; e.returnValue = false; } } } );
		}
		
		/*
		 * Autocomplete listener
		 */
		google.maps.event.addListener( autoComplete[ acCount ], 'place_changed', function() {
			
			var place = this.getPlace();
			
			/*
			 * Outputs the place results in the javascript console
			 */
			//console.log( place );
			
			/*
			 * If there's a map, show marker
			 */
			if( map && place.geometry )
			{
				infowindow.close();
				marker.setVisible( false );
				
				/*
				 * If the place has a geometry, then present it on a map
				 */
				if ( place.geometry.viewport ){
					map.fitBounds( place.geometry.viewport );
				}else{
					map.setCenter( place.geometry.location );
					map.setZoom( zoomResolved );
				}
				marker.setPosition( place.geometry.location );
				marker.setVisible( true );
			}
			
			/*
			 * Rest fields
			 */
			for( tmpLevel in jsFieldIds )
			{
				for( tmpField in jsFieldIds[ tmpLevel ] )
				{
					// Don't reset the main autocomplete field
					if( acFields[ 0 ].acInput == jsFieldIds[ tmpLevel ][ tmpField ] ) continue;
					
					if( ( componentField = document.getElementById( jsFieldIds[ tmpLevel ][ tmpField ] ) ) !== null ) componentField.value = '';
				}
			}
			
			/*
			 * Level specific place details components
			 */
			if( topLevel == 'location' )
			{
				if( ( componentField = document.getElementById( jsFieldIds.location.long_address ) ) !== null ) componentField.value = place.formatted_address;
				if( place.website && ( componentField = document.getElementById( jsFieldIds.location.website_url ) ) !== null ) componentField.value = place.website;
				if( place.international_phone_number && ( componentField = document.getElementById( jsFieldIds.location.phone ) ) !== null ) componentField.value = place.international_phone_number;
				
				// image_url defaults to url of first Photo result
				if( place.photos && ( componentField = document.getElementById( jsFieldIds.location.image_url ) ) !== null ) componentField.value = place.photos[ 0 ].raw_reference.fife_url;
			}
			
			// Assign a place name if not a street name
			if( place.types[ 0 ] !== 'street_address' && place.types[ 0 ] !== 'route' && ( componentField = document.getElementById( eval( 'jsFieldIds.' + topLevel + '.name' ) ) ) !== null ) componentField.value = place.name;
			
			if( ( componentField = document.getElementById( eval( 'jsFieldIds.' + topLevel + '.latitude' ) ) ) !== null ) componentField.value = place.geometry.location.lat();
			if( ( componentField = document.getElementById( eval( 'jsFieldIds.' + topLevel + '.longitude' ) ) ) !== null ) componentField.value = place.geometry.location.lng();
			
			/*
			 * Address components
			 */
			for( var i = 0; i < place.address_components.length; i++ )
			{
				var addressComponent = place.address_components[ i ];
				
				switch( addressComponent.types[ 0 ] )
				{
					case 'street_number':
						if( ( componentField = document.getElementById( jsFieldIds.location.address ) ) !== null ) componentField.value = addressComponent.long_name + ' ';
					break;
	
					case 'route':
						if( ( componentField = document.getElementById( jsFieldIds.location.address ) ) !== null ) componentField.value += addressComponent.long_name;
					break;
					
					case 'postal_code':
						if( ( componentField = document.getElementById( jsFieldIds.location.postcode ) ) !== null ) componentField.value = addressComponent.long_name;
					break;
					
					case 'locality':
						if( ( componentField = document.getElementById( jsFieldIds.city.name ) ) !== null ) componentField.value = addressComponent.long_name;
					break;
					
					case 'sublocality':
						if( ( componentField = document.getElementById( jsFieldIds.city.name ) ) !== null ) componentField.value = addressComponent.long_name;
					break;
					
					case 'administrative_area_level_1':
						if( ( componentField = document.getElementById( jsFieldIds.state.name ) ) !== null ) componentField.value = addressComponent.long_name;
						if( ( componentField = document.getElementById( jsFieldIds.state.short_name ) ) !== null ) componentField.value = addressComponent.short_name;
					break;
					
					case 'country':
						if( ( componentField = document.getElementById( jsFieldIds.country.name ) ) !== null ) componentField.value = addressComponent.long_name;
						if( ( componentField = document.getElementById( jsFieldIds.country.short_name ) ) !== null ) componentField.value = addressComponent.short_name;
					break;
				}
			}
			
			/*
			 * Photo selector, if there's a div named JulLocationPhotoSelector
			 */
			if( ( componentField = document.getElementById('JulLocationPhotoSelector') ) !== null )
			{
				componentField.innerHTML = '';
				
				if( ( photos = place.photos ) )
				{
					/*
					 * Output html to a variable because if assigned directly to componentField.innerHTML, browsers will close the <ul> tag automatically
					 */
					var htmlString = '<div class="JulLocationPhotoSelectorText">' + photoSelectorText + '</div><ul>';
					
					for( var a = 0; a < photos.length; a ++)
					{
						htmlString += '<li><img id="JulLocationPhoto_' + a + '" src="' + photos[ a ].raw_reference.fife_url + '" class="JulLocationPhotoImg" onClick="setImageUrl( ' + a + ', ' + photos.length + ', \'' + photos[ a ].raw_reference.fife_url + '\' )" /></li>';
					}
					
					htmlString += '</ul>';
					
					componentField.innerHTML = htmlString;
					
					// Preselect first photo to reflect default image_url value
					
					document.getElementById( 'JulLocationPhoto_0' ).className = 'JulLocationPhotoImg JulLocationPhotoImgSelected';
				}
			}
		});
	}
	
	/*
	 * If form is sent and image_url as a value, display selected photo in the photo selector
	 */
	if( ( componentField = document.getElementById('JulLocationPhotoSelector') ) !== null && ( imageField = document.getElementById( jsFieldIds.location.image_url ) ) !== null && ( image_url = imageField.value ) )
	{
		componentField.innerHTML = '<div class="JulLocationPhotoSelectorText">' + photoSelectedText + '</div><ul><li><img id="JulLocationPhotoSelected" src="' + image_url + '" /></li></ul>';
	}
	
	/*
	 * Selects a photo when photo selector is present
	 */
	setImageUrl = function( elementId, arrayLength, image_url )
	{
		// Apply not selected style to all photos
		for( var a = 0; a < arrayLength; a ++ ) document.getElementById( 'JulLocationPhoto_' + a ).className = 'JulLocationPhotoImg';
		
		// Apply selected style to selected photo
		document.getElementById( 'JulLocationPhoto_' + elementId ).className = 'JulLocationPhotoImg JulLocationPhotoImgSelected';
		
		// Update image_url field value
		if( ( componentField = document.getElementById( jsFieldIds.location.image_url ) ) !== null ) componentField.value = image_url;
	}
}