<?php

/*
 * JulLocationBundle Symfony package.
 *
 * © 2013 Julien Tord <http://github.com/youlweb/JulLocationBundle>
 *
 * Full license information in the LICENSE text file distributed
 * with this source code.
 *
 */

namespace Jul\LocationBundle\Form\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\Util\Inflector;

class LocationRepository extends EntityRepository
{
	/**
	 * @var string
	 */
	private $entityType;
	
	/**
	 * @var array
	 */
	private $configOptions;
	
	/**
	 * @param string $entityType
	 * @param ObjectManager $om
	 * @param array $configOptions
	 */
	public function __construct( $entityType, $om, $configOptions )
	{
		$this->configOptions = $configOptions;
		$this->entityType = $entityType;
		
		$class = new ClassMetadata( $this->configOptions[ $entityType ][ 'data_class' ] );
		
		parent::__construct( $om, $class );
	}
	
	/**
	 * @param Object|null $entityObject
	 * @return Object|null
	 */
	public function findEntityObject( $entityObject )
	{
		$entitiesArray = array(
				'location' => array( 'location', 'city', 'state', 'country' ),
				'city' => array( 'city', 'state', 'country' ),
				'state' => array( 'state', 'country' ),
				'country' => array( 'country' )
				);
		
		/*
		 * The third letter of an Entity name is used as a query alias
		 */
		$entityAlias = $entitiesArray[ $this->entityType ][ 0 ][ 2 ];
		
		$query = $this->createQueryBuilder( $entityAlias );
		
		foreach( $entitiesArray[ $this->entityType ] as $entity )
		{
			if( ! $this->configOptions[ $entity ][ 'data_class' ] ) break;
			
			if( $entity == $entitiesArray[ $this->entityType ][ 0 ] )
			{
				$entityObject = func_get_arg( 0 );
			}
			else
			{
				$entityAlias = $entity[ 2 ];
				$query->leftJoin( $oldEntityAlias . '.' . $entity, $entityAlias );
				
				$method = 'get' . Inflector::classify( $entity );
				$entityObject = $entityObject->$method();
			}
			
			if( $entityObject === null ) $query->andWhere( $entityAlias . ' IS NULL' );
			
			else
			{
				foreach( $this->configOptions[ $entity ][ 'fields' ] as $field => $options )
				{
					if( $options[ 'enabled' ] && $options[ 'identifier' ] )
					{
						$method = 'get' . Inflector::classify( $field );
						
						$query	->andWhere( "{$entityAlias}.{$field} LIKE :{$entity}{$field} OR ( {$entityAlias} IS NOT NULL AND {$entityAlias}.{$field} IS NULL AND :{$entity}{$field} IS NULL )" )
								->setParameter( $entity . $field, $entityObject->$method() );
					}
				}
			}
			
			$oldEntityAlias = $entity[ 2 ];
		}
		
		return $query->getQuery()->getOneOrNullResult();
	}
}
