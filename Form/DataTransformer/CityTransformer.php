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
	public function __construct(ObjectManager $om)
	{
		$this->om = $om;
	}
	
	/**
	 * Transforms a City object
	 * 
	 * @param City|null $city
	 * @return City
	 */
	public function transform($city)
	{
		if(null === $city)
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
	public function reverseTransform($city)
	{
		/*
		 * Mandatory field
		 */
		if( ( $cityName = $city->getName() ) === null ) return $city;
		
		/**
		 * --------------------
		 * Check if City exists
		 */
		
		$cityDB = $this	->om
						->getRepository('JulLocationBundle:City')
						->getOneByCityName( $cityName, $city->getState()->getName(), $city->getState()->getCountry()->getName() );
		
		if( $cityDB )
		{
			// if found in DB
				
			if( $this->om->contains($city) )
			{
				// if entity is already managed ( update ) restore the DB entity to its original state
		
				$this->om->refresh($city);
			}
		
			return $cityDB;
		}
		elseif( $this->om->contains($city) )
		{
			// if not in DB, and entity is managed ( update ), we clone the entity, free it from its DB slot, and persist the clone
		
			$newCity = clone $city;
			$newCity->setSlug(null);	// to trigger Gedmo slug
			$this->om->detach($city);
			$city = $newCity;
		}
		
		$this->om->persist($city);
		
		return $city;
	}
}