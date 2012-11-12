<?php 

// Transforms the City field type compound into City entity
// Verifies if entity already exists before persisting

namespace Jul\LocationBundle\Form\DataTransformer;

use Jul\LocationBundle\Entity\Country;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Jul\LocationBundle\Entity\City;

use Symfony\Component\Form\Exception\TransformationFailedException;

class CityToCompoundTransformer implements DataTransformerInterface
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
	 * Processes a City Object for duplicates and persists
	 * 
	 * @param City|null $city
	 * @return City
	 */
	public function reverseTransform($city)
	{
		if( $city->getName() === null ) return $city;
		
		// Check if city exists
		
		$cityDB = $this->om
		->getRepository('JulLocationBundle:City')
		->findOneByFullname( $city->getFullname() );
		
		if( $cityDB ) // if city exists
		{
			// if UPDATE, we reset the current City to its DB state before returning the City we just found
			if( $this->om->contains($city) ) $this->om->refresh($city);
			
			return $cityDB;
		}
		elseif( $this->om->contains($city) ) // if new city
		{
			// if UPDATE, we clone the current City, persist the clone, and return the old one to its DB state
			
			$newCity = clone $city;
			$newCity->setSlug(null); // otherwise won't trigger Gedmo
			$this->om->persist($newCity);
			$this->om->detach($city);
			$city = $newCity;
		}
		
		// Check if country exists
		
		$country = $city->getCountry();
		
		$countryDB = $this->om
		->getRepository('JulLocationBundle:Country')
		->findOneBy( array( 'name' => $country->getName(), 'code' => $country->getCode() ));
		
		if( $countryDB ) // if country exists
		{
			// if UPDATE, we return Country to its DB state
			if( $this->om->contains($country) ) $this->om->refresh($country);
			
			$city->setCountry($countryDB);
		}
		elseif( $this->om->contains($country) ) // if new country & UPDATING
		{
			$newCountry = clone $country;
			$newCountry->setSlug(null);
			$this->om->persist($newCountry);
			$city->setCountry($newCountry);
			$this->om->detach($country);
			
		} else $this->om->persist($country); // new country & CREATING
		
		// Check if state exists
		
		$state = $city->getState();
		
		if( ( $stateName = $state->getName() ) !== null )
		{
			$country = $city->getCountry();
			
			if( $countryDB ) // if known country, might be known state too
			{
				$stateDB = $this->om
				->getRepository('JulLocationBundle:State')
				->findOneBy( array( 'name' => $stateName, 'code' => $state->getCode(), 'country' => $country ));
			}
			
			if( isset( $stateDB ) && $stateDB !== null ) // if state exists
			{
				// if UPDATE, we return State to its DB state
				if( $this->om->contains($state) ) $this->om->refresh($state);
				
				$this->om->persist($stateDB);
				$city->setState($stateDB);
			}
			else
			{
				if( $this->om->contains($state) ) // if new state & UPDATING member
				{
					$newState = clone $state;
					$newState->setSlug(null);
					$this->om->persist($newState);
					$this->om->detach($state);
					$state = $newState;
				}
				else $this->om->persist($state); // if new state & CREATING member
				
				$state->setCountry($country);
				$city->setState($state);
			}
		}
		else
		{
			// if UPDATE, return the State to its DB state
			if( $this->om->contains($state) ) $this->om->refresh($state);
			
			$city->setState(null);
		}
		
		
		$this->om->persist($city);
		
		return $city;
	}
}