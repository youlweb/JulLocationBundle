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
	 * Find a State
	 * 
	 * @param \Jul\LocationBundle\Entity\State
	 * 
	 * @return State
	 */
	public function getOneByStateObject( \Jul\LocationBundle\Entity\State $state )
	{	
		$query = $this	->createQueryBuilder( 's' )
						->leftJoin( 's.country', 'y' )
		;
		
		/*
		 * State
		 */
		if( $state === null )
		{
			$query	->where( 's IS NULL' );
		}
		else
		{		
			$query	->where( 's.name = :state OR ( s IS NOT NULL AND s.name IS NULL AND :state IS NULL )' )
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
		
		return $query->getQuery()->getOneOrNullResult();
	}
}
