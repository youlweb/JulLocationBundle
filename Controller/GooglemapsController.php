<?php 

/*
Jul's Google maps javascript API default parameters setup
*/

namespace Jul\LocationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * 
 * @author julien
 *
 */
class GooglemapsController extends Controller
{
	public function placesAutocompleteAction
	(
			$locationForm,
			$zoomDefault = null,
			$zoomResolved = 17,
			$mapDiv = 'map_canvas',
			$mapOptions = array(),
			$acFields = null,
			$acFallback = false,
			$latitude = null,
			$longitude = null
	)
	{
		/*
		 * Find location top level
		 */
		$locationTypes = array( 'location', 'city', 'state', 'country' );
		
		foreach( $locationTypes as $locationType )
		{
			if( $locationForm->offsetExists( $locationType ) )
			{
				$topLevel = $locationType;
				break;
			}
		}
		
		/*
		 * Top level not found
		 */
		if( ! isset( $topLevel ) ) throw new \Exception( 'There is no location field in the form sent to the controller JulLocationBundle:Googlemaps:placesAutocomplete' );
		
		$topLevelForm = $locationForm->getChild( $topLevel );
		
		/*
		 * Default map center and zoom
		 */
		if( $topLevelForm->offsetExists( 'latitude' ) && ( $latForm = $topLevelForm->getChild( 'latitude' )->get( 'value' ) ) <> 0 )
		{
			/*
			 * If the form has been sent with a location
			 */
			$latitude = $latForm;
			$longitude = $topLevelForm->getChild( 'longitude' )->get( 'value' );
			
			$zoomDefault = $zoomResolved;
		}
		else
		{
			if( ! $latitude ) $latitude = 40.4230;
			if( ! $longitude ) $longitude = -98.7372;
			if( ! $zoomDefault ) $zoomDefault = 3;
		}
		
		/*
		 * Default map options array
		 */
		$mapOptions = array_merge( array(
			'zoom' => $zoomDefault
			), $mapOptions );
		
		/*
		 * Default autocomplete input field
		*/
		if( ! isset( $acFields[ 0 ][ 'acInput' ] ) )
		{
			$acFields[ 0 ][ 'acInput' ] = ( $topLevelForm->offsetExists( 'fullname' ) ) ? $topLevelForm->getChild( 'fullname' )->get( 'id' ) : $topLevelForm->getChild( 'name' )->get( 'id' );
		}
		
		/*
		 * Default autocomplete Types
		 */
		if( ! isset( $acFields[ 0 ][ 'acOptions' ]['types'] ) )
		{
			switch( $topLevel )
			{
				case 'location': $acFields[ 0 ][ 'acOptions' ][ 'types' ] = array( 'establishment' ); break;
				case 'city': $acFields[ 0 ][ 'acOptions' ][ 'types' ] = array( '(cities)' ); break;
				default: $acFields[ 0 ][ 'acOptions' ][ 'types' ] = array( '(regions)' );
			}
		}
		
		/*
		 * Address autocomplete fallback
		 */
		if( $acFallback && $topLevel == 'location' && ! isset( $acFields[ 1 ][ 'acInput' ] ) && $topLevelForm->offsetExists( 'fulladdress' ) )
		{
			$acFields[ 1 ][ 'acInput' ] = ( $topLevelForm->offsetExists( 'fullname' ) ) ? $topLevelForm->getChild( 'fulladdress' )->get( 'id' ) : $topLevelForm->getChild( 'address' )->get( 'id' );
			$acFields[ 1 ][ 'acOptions' ][ 'types' ] = array( 'geocode' );
		}
		
		/*
		 * Build javascript field IDs array using JulLocationBundle config
		 */
		
		$jsFieldIds = array();
		$tmpLevel = $locationForm;
		
		foreach( $this->container->parameters[ 'jul_location.options' ] as $level => $options )
		{
			$fields = $options['inputFields'];
			
			$tmpArray = array();
			
			if( $tmpLevel->offsetExists( $level ) )
			{	
				$tmpLevel = $tmpLevel->getChild( $level );
				
				foreach( $fields as $field => $fieldArray )
				{
					/*
					 * Check if field is active in config && exists in the form
					 */
					if( $fieldArray[ 'enabled' ] && $tmpLevel->offsetExists( $field ) ) $tmpArray[ $field ] = $tmpLevel->getChild( $field )->get( 'id' );
				}
			}
			
			$jsFieldIds[ $level ] = $tmpArray;
		}
		
		return $this->render('JulLocationBundle:Googlemaps:placesAutocomplete.html.twig', array(				
				'mapDiv' => $mapDiv,
				'mapOptions' => json_encode( $mapOptions ),
				'acFields' => json_encode( $acFields ),
				'topLevel' => $topLevel,
				'zoomResolved' => $zoomResolved,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'jsFieldIds' => json_encode( $jsFieldIds )
				));
	}
}
