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
	public function __construct( ObjectManager $om )
	{
		$this->om = $om;
	}
	
	/**
	 * Transforms a Location object
	 * 
	 * @param Location|null $location
	 * @return Location
	 */
	public function transform( $location )
	{
		if( null === $location )
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
	public function reverseTransform( $location )
	{
		/*
		 * Check if Location exists
		*/
		$locationDB = $this	-> om
							-> getRepository( 'JulLocationBundle:Location' )
							-> getOneByLocationObject( $location );
		
		if( $locationDB )
		{
			// if Location found in DB
			
			if( $this->om->contains( $location ) )
			{
				/*
				 * if entity is managed ( update process ):
				 * - restore the managed entity to its original state to preserve its data content
				 * - return the DB entity
				 */
				$this->om->refresh( $location );
			}
		
			return $locationDB;
		}
		elseif( $this->om->contains( $location ) )
		{
			/*
			 * if Location is not found in DB, but entity is managed ( update process ):
			 * - clone the entity
			 * - free the original from management to preserve its data content
			 * - persist the clone
			 */
			$newLocation = clone $location;
			$newLocation->setSlug( null );	// to trigger Gedmo slug
			$this->om->detach( $location );
			$location = $newLocation;
		}
		
		$this->om->persist( $location );
		
		return $location;
	}
}