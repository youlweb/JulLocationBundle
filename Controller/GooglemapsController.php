<?php

/*
 * JulLocationBundle Symfony package.
 *
 * © 2013 Julien Tord <http://github.com/youlweb/JulLocationBundle>
 *
 * Full license information in the LICENSE text file distributed
 * with this source code.
 *
 */

namespace Jul\LocationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GooglemapsController extends Controller
{
	public function placesAutocompleteAction
	(
			$locationForm,
			$zoomDefault = null,
			$zoomResolved = 17,
			$latitude = null,
			$longitude = null,
			$mapDiv = 'map_canvas',
			$mapOptions = array(),
			$acFields = null,
			$addressFallback = false,
			$maxImageWidth = 200,
			$maxImageHeight = 200
	)
	{
        $propertyAccessor = PropertyAccess::getPropertyAccessor();

		/*
		 * Find top level entity
		 */
		$locationTypes = array( 'location', 'city', 'state', 'country' );
		foreach( $locationTypes as $locationType )
		{
			if(  array_key_exists ($locationType , $locationForm['children']))
			{

				$topLevel = $locationType;

				$topLevelForm = $locationForm['children'][$topLevel];

				break;
			}

			if( $locationForm->getName() == 'Jul' . ucfirst( $locationType ) . 'Field' )
			{
				$topLevel = $locationType;
				$topLevelForm = $locationForm;

				break;
			}
		}

		/*
		 * Top level not found
		 */

		if( ! isset( $topLevel ) ) throw new \Exception( 'There is no location field in the form sent to the controller JulLocationBundle:Googlemaps:placesAutocomplete' );

		/*
		 * Default map center and zoom
		 */



		if( $topLevelForm['children']['latitude' ] && ( $latForm = $topLevelForm['children']['latitude']['vars']['value'] ) <> 0 )
		{

			/*
			 * If the form has been sent with a location
			 */
			$latitude = $latForm;

			$longitude = $topLevelForm['children']['longitude']['vars']['value'];

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

			$acFields[ 0 ][ 'acInput' ] = ( array_key_exists ('long_name' , $topLevelForm['children']) ) ? $topLevelForm['children']['long_name']['vars']['id'] : $topLevelForm['children']['name']['vars']['id'];

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
		if( $addressFallback && $topLevel == 'location' && ! isset( $acFields[ 1 ][ 'acInput' ] ) && $topLevelForm->offsetExists( 'long_address' ) )
		{
			$acFields[ 1 ][ 'acInput' ] = ( $topLevelForm->offsetExists( 'long_name' ) ) ? $propertyAccessor->getValue($topLevelForm , 'children[long_address.id]') : $propertyAccessor->getValue($topLevelForm , 'children[address.id]');
			$acFields[ 1 ][ 'acOptions' ][ 'types' ] = array( 'geocode' );
		}

		/*
		 * Build javascript field IDs array using JulLocationBundle config
		 */

		$jsFieldIds = array();
		$tmpLevel = $locationForm;

		foreach( $this->container->parameters[ 'jul_location.options' ] as $level => $options )
		{
			$fields = $options['fields'];
			$tmpArray = array();
			if( array_key_exists ($level  , $tmpLevel['children']) )
			{

				$tmpLevel = $tmpLevel['children'][$level];

				foreach( $fields as $field => $fieldArray )
				{
					/*
					 * Check if field is active in config && exists in the form
					 */

					if( $fieldArray[ 'enabled' ] && array_key_exists ($field  , $tmpLevel['children'])  ) $tmpArray[ $field ] = $tmpLevel['children'][ $field ]['vars']['id'] ;

				}
			}

			$jsFieldIds[ $level ] = $tmpArray;
		}
return $this->render( 'JulLocationBundle:Googlemaps:placesAutocomplete.html.twig', array(
				'mapDiv' => $mapDiv,
				'mapOptions' => json_encode( $mapOptions ),
				'acFields' => json_encode( $acFields ),
				'topLevel' => $topLevel,
				'zoomResolved' => $zoomResolved,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'jsFieldIds' => json_encode( $jsFieldIds ),
				'maxImageWidth' => $maxImageWidth,
				'maxImageHeight' => $maxImageHeight
				));
	}
}
