<?php 

namespace Jul\LocationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Persist / update a Location entity, checking for subclasses duplicates
 * 
 * @author julien
 *
 */
class LocationTransformer implements DataTransformerInterface
{
	/**
	 * @var ObjectManager
	 */
	private $om;
	
	/**
	 * @param ObjectManager $om
	 */
	public function __construct(ObjectManager $om)
	{
		$this->om = $om;
	}
	
	/**
	 * Transforms a Location object
	 * 
	 * @param Location|null $location
	 * @return Location
	 */
	public function transform($location)
	{
		if(null === $location)
		{
			return null;
		}
		
		return $location;
	}
	
	/**
	 * Processes a Location Object
	 * 
	 * @param Location|null $location
	 * @return Location
	 */
	public function reverseTransform($location)
	{
		
		/**
		 * ------------------------
		 * Check if Location exists
		 */
		
		$locationDB = $this	->om
							->getRepository( 'JulLocationBundle:Location' )
							->getOneByLocationName( $location->getName(), $location->getAddress(), $location->getPostcode(), $location->getCity()->getName(), $location->getCity()->getState()->getName(), $location->getCity()->getState()->getCountry()->getName() );
		
		if( $locationDB )
		{
			// if found in DB
			
			if( $this->om->contains( $location ) )
			{
				// if entity is already managed ( update ) restore the DB entity to its original state
		
				$this->om->refresh( $location );
			}
		
			return $locationDB;
		}
		elseif( $this->om->contains( $location ) )
		{
			// if not in DB, and entity is managed ( update ), we clone the entity, free it from its DB slot, and persist the clone
		
			$newLocation = clone $location;
			$newLocation->setSlug( null );	// to trigger Gedmo slug
			$this->om->detach( $location );
			$location = $newLocation;
		}
		
		$this->om->persist($location);
		
		return $location;
	}
}