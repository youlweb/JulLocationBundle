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
	 * Find a City
	 * 
	 * @param \Jul\LocationBundle\Entity\City
	 * 
	 * @return City
	 */
	public function getOneByCityObject( \Jul\LocationBundle\Entity\City $city )
	{	
		$query = $this	->createQueryBuilder( 'c' )
						->leftJoin( 'c.state', 's' )
						->leftJoin( 's.country', 'y' )
		;
		
		/*
		 * City
		 */
		if( $city === null )
		{
			$query	->where( 'c is NULL' );
		}
		else
		{
			$query	->where( 'c.name = :city OR ( c IS NOT NULL AND c.name IS NULL AND :city IS NULL )' )
					->setParameter( 'city', $city->getName() )
					;
			
			/*
			 * State
			 */
			if( ( $state = $city->getState() ) === null )
			{
				$query	->andWhere( 's IS NULL' );
			}
			else
			{
				$query	->andWhere( 's.name = :state OR ( s IS NOT NULL AND s.name IS NULL AND :state IS NULL )' )
						->setParameter( 'state', $state->getName() )
						;
				
				/*
				 * Country
				 */
				if( ( $country = $state->getCountry() ) === null )
				{
					$query	->andWhere( 'y IS NULL' );
					
				}
				else
				{
					$query	->andWhere( 'y.name = :country OR ( y IS NOT NULL AND y.name IS NULL AND :country IS NULL )' )
							->setParameter( 'country', $country->getName() )
							;
				}
			}
		}
		
		return $query->getQuery()->getOneOrNullResult();
	}
}
