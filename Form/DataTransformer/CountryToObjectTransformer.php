<?php 

namespace Jul\LocationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Persist / update a Country entity, checking for duplicates
 *
 * @author julien
 *
 */
class CountryToObjectTransformer implements DataTransformerInterface
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
	 * Transforms a Country object
	 * 
	 * @param Country|null $country
	 * @return Country
	 */
	public function transform($country)
	{
		if(null === $country)
		{
			return null;
		}
		
		return $country;
	}
	
	/**
	 * Processes a Country Object
	 * 
	 * @param Country|null $country
	 * @return Country
	 */
	public function reverseTransform($country)
	{
		if( ( $countryName = $country->getName() ) === null ) return null;
		
		/*
		 * -----------------------
		 * Check if Country exists
		 */
		
		$countryDB = $this	->om
							->getRepository('JulLocationBundle:Country')
							->findOneByName( $countryName );
		
		if( $countryDB )
		{
			// if found in DB
			
			if( $this->om->contains($country) )
			{
				// if entity is already managed ( update ) restore the DB entity to its original state
				
				$this->om->refresh($country);
			}
					
			return $countryDB;
		}
		elseif( $this->om->contains($country) )
		{
			// if not in DB, and entity is managed ( update ), we clone the entity, free it from its DB slot, and persist the clone
			
			$newCountry = clone $country;
			$newCountry->setSlug(null);	// to trigger Gedmo slug
			$this->om->detach($country);
			$country = $newCountry;
		}
		
		$this->om->persist($country);
		
		return $country;
	}
}