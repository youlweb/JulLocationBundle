<?php

namespace Jul\LocationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CountryRepository
 *
 * @author julien
 */
class CountryRepository extends EntityRepository
{
	/**
	 * Find a Country
	 * 
	 * @param \Jul\LocationBundle\Entity\Country
	 * 
	 * @return Country
	 */
	public function getOneByCountryObject( \Jul\LocationBundle\Entity\Country $country )
	{	
		$query = $this	->createQueryBuilder( 'y' );
		
		/*
		 * Country
		 */
		if( $country === null )
		{
			$query	->where( 'y IS NULL' );
		}
		else
		{		
			$query	->where( 'y.name = :country OR ( y IS NOT NULL AND y.name IS NULL AND :country IS NULL )' )
					->setParameter( 'country', $country->getName() )
					;
		}
		
		return $query->getQuery()->getOneOrNullResult();
	}
}
