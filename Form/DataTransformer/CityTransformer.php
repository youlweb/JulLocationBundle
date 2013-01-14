<?php 

namespace Jul\LocationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Persist / update a City entity, checking for subclasses duplicates
 *
 * @author julien
 *
 */
class CityTransformer implements DataTransformerInterface
{
	/**
	 * @var ObjectManager
	 */
	private $om;
	
	/**
	 * @param ObjectManager $om
	 */
	public function __construct( ObjectManager $om )
	{
		$this->om = $om;
	}
	
	/**
	 * Transforms a City object
	 * 
	 * @param City|null $city
	 * @return City
	 */
	public function transform( $city )
	{
		if( null === $city )
		{
			return null;
		}
		
		return $city;
	}
	
	/**
	 * Processes a City Object
	 * 
	 * @param City|null $city
	 * @return City
	 */
	public function reverseTransform( $city )
	{
		/*
		 * Check if City exists
		*/
		$cityDB = $this	-> om
						-> getRepository( 'JulLocationBundle:City' )
						-> getOneByCityObject( $city );
		
		if( $cityDB )
		{
			// if City found in DB
				
			if( $this->om->contains( $city ) )
			{
				/*
				 * if entity is managed ( update process ):
				 * - restore the managed entity to its original state to preserve its data content
				 * - return the DB entity
				 */
				$this->om->refresh( $city );
			}
		
			return $cityDB;
		}
		elseif( $this->om->contains( $city ) )
		{
			/*
			 * if City is not found in DB, but entity is managed ( update process ):
			 * - clone the entity
			 * - free the original from management to preserve its data content
			 * - persist the clone
			 */
			$newCity = clone $city;
			$newCity->setSlug( null );	// to trigger Gedmo slug
			$this->om->detach( $city );
			$city = $newCity;
		}
		
		$this->om->persist( $city );
		
		return $city;
	}
}