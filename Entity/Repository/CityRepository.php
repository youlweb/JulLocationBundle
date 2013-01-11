<?php

namespace Jul\LocationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CityRepository
 *
 * @author julien
 */
class CityRepository extends EntityRepository
{
	/**
	 * Find a City using a City name, State name, and a Country name
	 * 
	 * @param string $cityName
	 * @param string $stateName
	 * @param string $countryName
	 * 
	 * @return City
	 */
	public function getOneByCityName( $cityName = NULL, $stateName = NULL, $countryName = NULL )
	{	
		$query = $this->getEntityManager()
		->createQuery( "SELECT c FROM Jul\LocationBundle\Entity\City c JOIN c.state s JOIN s.country y WHERE ( c.name = :city OR ( c.name IS NULL AND :city IS NULL ) ) AND ( s.name = :state OR ( s.name IS NULL AND :state IS NULL ) ) AND ( y.name = :country OR ( y.name IS NULL AND :country IS NULL ) )");
			
		$query->setParameters(array(
				'city' => $cityName,
				'state' => $stateName,
				'country' => $countryName
		));
		
		return $query->getOneOrNullResult();
	}
}
