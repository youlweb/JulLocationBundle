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
	public function __construct( ObjectManager $om )
	{
		$this->om = $om;
	}
	
	/**
	 * Transforms a State object
	 * 
	 * @param State|null $state
	 * @return State
	 */
	public function transform( $state )
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
	public function reverseTransform( $state )
	{
		/*
		 * Check if State exists
		 */
		$stateDB = $this	-> om
							-> getRepository( 'JulLocationBundle:State' )
							-> getOneByStateObject( $state );
		
		if( $stateDB )
		{
			// if State found in DB
			
			if( $this->om->contains( $state ) )
			{
				/*
				 * if entity is managed ( update process ):
				 * - restore the managed entity to its original state to preserve its data content
				 * - return the DB entity
				 */
				$this->om->refresh( $state );
			}
				
			return $stateDB;
		}
		elseif( $this->om->contains( $state ) )
		{
			/*
			 * if State is not found in DB, but entity is managed ( update process ):
			 * - clone the entity
			 * - free the original from management to preserve its data content
			 * - persist the clone
			 */	
			$newState = clone $state;
			$newState->setSlug( null );	// to trigger Gedmo slug
			$this->om->detach( $state );
			$state = $newState;
		}
		
		$this->om->persist( $state );
		
		return $state;
	}
}