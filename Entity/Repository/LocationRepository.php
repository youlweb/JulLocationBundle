<?php

namespace Jul\LocationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LocationRepository
 *
 * @author julien
 */
class LocationRepository extends EntityRepository
{
	/**
	 * Find a Location
	 *
	 * @param \Jul\LocationBundle\Entity\Location
	 *
	 * @return Location
	 */
	public function getOneByLocationObject( \Jul\LocationBundle\Entity\Location $location )
	{
		$query = $this	->createQueryBuilder( 'l' )
						->leftJoin( 'l.city', 'c' )
						->leftJoin( 'c.state', 's' )
						->leftJoin( 's.country', 'y' )
		;
		
		/*
		 * Location
		 */
		if( $location === null )
		{
			$query	->where( 'l IS NULL' );
		}
		else
		{
			$query	->where( 'l.name = :location OR ( l IS NOT NULL AND l.name IS NULL AND :location IS NULL )' )
					->andWhere( 'l.address = :address OR ( l.address IS NULL AND :address IS NULL )' )
					->andWhere( 'l.postcode = :postcode OR ( l.postcode IS NULL AND :postcode IS NULL )' )
					->setParameter( 'location', $location->getName() )
					->setParameter( 'address', $location->getAddress() )
					->setParameter( 'postcode', $location->getPostcode() )
					;
			
			/*
			 * City
			 */
			if( ( $city = $location->getCity() ) === null )
			{
				$query	->andWhere( 'c is NULL' );
			}
			else
			{
				$query	->andWhere( 'c.name = :city OR ( c IS NOT NULL AND c.name IS NULL AND :city IS NULL )' )
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
		}
		
		return $query->getQuery()->getOneOrNullResult();
	}
}
