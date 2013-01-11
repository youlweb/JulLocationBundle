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
class CountryTransformer implements DataTransformerInterface
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
	 * Transforms a Country object
	 * 
	 * @param Country|null $country
	 * @return Country
	 */
	public function transform( $country )
	{
		if( null === $country )
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
	public function reverseTransform( $country )
	{
		/*
		 * Check if Country name exists
		 */
		$countryDB = $this	-> om
							-> getRepository( 'JulLocationBundle:Country' )
							-> findOneByName( $country->getName() );
		
		if( $countryDB )
		{
			// if name found in DB
			
			if( $this->om->contains( $country ) )
			{
				/*
				 * if entity is managed ( update process ):
				 * - restore the managed entity to its original state to preserve its data content
				 * - return the DB entity
				 */
				$this->om->refresh( $country );
			}
					
			return $countryDB;
		}
		elseif( $this->om->contains( $country ) )
		{
			/*
			 * if name is not found in DB, but entity is managed ( update process ):
			 * - clone the entity
			 * - free the original from management to preserve its data content
			 * - persist the clone
			 */
			$newCountry = clone $country;
			$newCountry->setSlug( null );	// to trigger Gedmo slug
			$this->om->detach( $country );
			$country = $newCountry;
		}
		
		$this->om->persist( $country );
		
		return $country;
	}
}