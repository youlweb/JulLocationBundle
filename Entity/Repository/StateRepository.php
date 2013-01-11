<?php

namespace Jul\LocationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * StateRepository
 *
 * @author julien
 */
class StateRepository extends EntityRepository
{
	/**
	 * Find a State using a State name and a Country name
	 * 
	 * @param string $stateName
	 * @param string $countryName
	 * 
	 * @return State
	 */
	public function getOneByStateName( $stateName = NULL, $countryName = NULL )
	{	
		$query = $this->getEntityManager()
		->createQuery( "SELECT s FROM Jul\LocationBundle\Entity\State s JOIN s.country c WHERE ( s.name = :state OR ( s.name IS NULL AND :state IS NULL ) ) AND ( c.name = :country OR ( c.name IS NULL AND :country IS NULL ) )");
			
		$query->setParameters(array(
				'state' => $stateName,
				'country' => $countryName
		));
		
		return $query->getOneOrNullResult();
	}
}
