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

namespace Jul\LocationBundle\Form\DataTransformer;

use Jul\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LocationTransformer implements DataTransformerInterface
{
	/**
	 * @var string
	 */
	private $entityType;
	
	/**
	 * @var ObjectManager
	 */
	private $om;
	
	/**
	 * Options sent via form Type
	 * 
	 * @var array
	 */
	private $configOptions;
	
	/**
	 * @param string $entityType
	 * @param ObjectManager $om
	 * @param array $configOptions
	 */
	public function __construct( $entityType, ObjectManager $om, $configOptions )
	{
		$this->entityType = $entityType;
		$this->om = $om;
		$this->configOptions = $configOptions;
	}
	
	/** 
	 * @param Object|null $entityObject
	 * @return Object
	 */
	public function transform( $entityObject )
	{
		if( null === $entityObject ) return null;
		
		return $entityObject;
	}
	
	/**
	 * @param Object|null $entityObject
	 * @return Object
	 */
	public function reverseTransform( $entityObject )
	{
		/*
		 * Check if Entity exists if demanded in configuration (default true)
		 */
		$locationRepository = new LocationRepository( $this->entityType, $this->om, $this->configOptions );
		
		if(!$entityObject->getId() || !$this->configOptions[$this->entityType]['update']){

			$entityDB = $locationRepository->findEntityObject( $entityObject );
			
			if( $entityDB )
			{
				// if Location found in DB
				
				if( $this->om->contains( $entityObject ) )
				{
					/*
					 * if entity is managed ( update process ):
					 * - restore the managed entity to its original state to preserve its data content
					 * - return the DB entity
					 */
					$this->om->refresh( $entityObject );
				}
			
				return $entityDB;
			}
			elseif( $this->om->contains( $entityObject ) )
			{
				/*
				 * if Location is not found in DB, but entity is managed ( update process ):
				 * - clone the entity
				 * - free the original from management to preserve its data content
				 * - persist the clone
				 */
				$newEntity = clone $entityObject;
				$newEntity->setSlug( null );	// to trigger Gedmo slug
				$this->om->detach( $entityObject );
				$entityObject = $newEntity;
			}
		}
		
		$this->om->persist( $entityObject );
		
		return $entityObject;
	}
}