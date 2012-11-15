<?php 

namespace Jul\LocationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Persist / update a State entity, checking for subclasses duplicates
 *
 * @author julien
 *
 */
class StateTransformer implements DataTransformerInterface
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
	 * Transforms a State object
	 * 
	 * @param State|null $state
	 * @return State
	 */
	public function transform($state)
	{
		if(null === $state)
		{
			return null;
		}
		
		return $state;
	}
	
	/**
	 * Processes a State Object
	 * 
	 * @param State|null $state
	 * @return State
	 */
	public function reverseTransform($state)
	{
		/*
		 * ---------------------
		 * Check if State exists
		 */
		
		$stateDB = $this	->om
							->getRepository('JulLocationBundle:State')
							->getOneByStateName( $state->getName(), $state->getCountry()->getName() );
		
		if( $stateDB )
		{
			// if found in DB
			
			if( $this->om->contains($state) )
			{
				// if entity is already managed ( update ) restore the DB entity to its original state
		
				$this->om->refresh($state);
			}
				
			return $stateDB;
		}
		elseif( $this->om->contains($state) )
		{
			// if not in DB, and entity is managed ( update ), we clone the entity, free it from its DB slot, and persist the clone
				
			$newState = clone $state;
			$newState->setSlug(null);	// to trigger Gedmo slug
			$this->om->detach($state);
			$state = $newState;
		}
		
		$this->om->persist($state);
		
		return $state;
	}
}