<?php 

namespace Jul\LocationBundle\Tests\Form\DataTransformer;

use Jul\LocationBundle\Entity\City;
use Jul\LocationBundle\Entity\State;
use Jul\LocationBundle\Entity\Country;
use Jul\LocationDemoBundle\Form\DataTransformer\CityTransformer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Caution: this test inserts a bunch of test entities in the DB.
 * 
 * @author julien
 *
 */
class CityTransformerTest extends WebTestCase
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $om;
	
	/**
	 * @var \Jul\LocationBundle\Form\DataTransformer\CityTransformer
	 */
	protected $entityTransformer;
	
	/**
	 * @var \Jul\LocationBundle\Entity\State
	 */
	protected $state;
	
	/**
	 * @var \Jul\LocationBundle\Entity\Country
	 */
	protected $country;
	
	protected $repository;
	
	/**
	 * {@inheritDoc}
	 */
	public function setUp()
	{
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		
		$this->om = static::$kernel->getContainer()->get('doctrine')->getEntityManager();
		
		$this->entityTransformer = new \Jul\LocationBundle\Form\DataTransformer\CityTransformer( $this->om );
		
		$this->repository = $this->om->getRepository('JulLocationBundle:City');
		
		/*
		 * Dependent entities
		 */
		$this->country = new Country();
		$this->country->setName( uniqid( 'test_' ) );
		$this->om->persist( $this->country );
		
		$this->state = new State();
		$this->state->setName( uniqid( 'test_' ) );
		$this->state->setCountry( $this->country );
		$this->om->persist( $this->state );
	}
	
	/*
	 * This test enforces that the entity form type data transformer
	 * doesn't allow entities with existing names to be added in the DB.
	 * 
	 * ------------------------------------------------------------------
	 * This test applies in the 'CREATE' context, where the entity is not
	 * managed by Doctrine's object manager
	 * 
	 */
	public function testReverseTransformCreate()
	{
		/*
		 * Generate a unique random entity name
		 */
		$entityName = uniqid( 'test_' );
		
		/*
		 * Create an entity
		 */
		$entity1 = new City();
		$entity1->setName( $entityName );
		$entity1->setState( $this->state );
		
		/*
		 * Entity has a unique random name, thus not in DB
		 * The transformer should return the object untouched
		 * 
		 */
		$this->assertEquals( $entity1, $this->entityTransformer->reverseTransform( $entity1 ) );
		
		/*
		 * Persist the entity in DB
		 */
		$this->om->persist( $entity1 );
		$this->om->flush();
		
		/*
		 * Store entity's DB Id
		 */
		$entityId = $entity1->getId();
		
		/*
		 * Create new entity with same name
		 */
		$entity2 = new City();
		$entity2->setName( $entityName );
		$entity2->setState( $this->state );
		
		/*
		 * Entity with similar name exists in DB
		 * The transformer should return the DB object
		 * 
		 */
		$this->assertEquals( $entityId, $this->entityTransformer->reverseTransform( $entity2 )->getId() );
	}
	
	/*
	 * This test applies in the 'UPDATE' context, where the entity is
	 * already managed by Doctrine's object manager.
	 * 
	 * In this context, additional processing must be done to preserve
	 * the managed entity's data integrity.
	 * 
	 * This test focuses on updating to an entity that doesn't exist
	 * in the database
	 *
	 */
	public function testReverseTransformUpdate()
	{
		/*
		 * Generate a unique random entity name
		 */
		$entityName = uniqid( 'test_' );
	
		/*
		 * Create and persist an entity
		 */
		$entity1 = new City();
		$entity1->setName( $entityName );
		$entity1->setState( $this->state );
		$this->om->persist( $entity1 );
		$this->om->flush();
		
		/*
		 * Store entity's DB id
		 */
		$entityId = $entity1->getId();
		
		/*
		 * Entity with similar name exists in DB
		 * The transformer should return the DB object
		 */
		$this->assertEquals( $entity1, $this->entityTransformer->reverseTransform( $entity1 ) );
		
		/*
		 * We change the name of the managed entity ( update process )
		 */
		$entityNewName = uniqid( 'test_' );
		$entity1->setName( $entityNewName );
		
		/*
		 * Entity is not found in DB, the transformer should:
		 * - refresh the managed entity to preserve its original DB data
		 * - return a new entity with the new name
		 */
		$transformedEntity = $this->entityTransformer->reverseTransform( $entity1 );
		
		/*
		 * First, we check that the old entity kept its name
		 */
		$this->assertEquals( $entityName, $this->repository->find( $entityId )->getName() );
		
		/*
		 * Next, we check that the entity returned by the transformer has the new name
		 */
		$this->assertEquals( $entityNewName, $transformedEntity->getName() );
	}
	
	/*
	 * This test applies in the 'UPDATE' context, where the entity is
	 * already managed by Doctrine's object manager.
	 *
	 * In this context, additional processing must be done to preserve
	 * the managed entity's data integrity.
	 *
	 * This test focuses on updating to an entity that already exists
	 * in the database
	 *
	 */
	public function testReverseTransformUpdateExisting()
	{
		/*
		 * Generate a couple of unique random entities names
		 */
		$entityName1 = uniqid( 'test_' );
		$entityName2 = uniqid( 'test_' );
	
		/*
		 * Create & persist a couple of entities
		 */
		$entity1 = new City();
		$entity1->setName( $entityName1 );
		$entity1->setState( $this->state );
		$this->om->persist( $entity1 );
		
		$entity2 = new City();
		$entity2->setName( $entityName2 );
		$entity2->setState( $this->state );
		$this->om->persist( $entity2 );
		
		$this->om->flush();
		
		/*
		 * Store entities' DB ids
		 */
		$entity1Id = $entity1->getId();
		$entity2Id = $entity2->getId();
		
		/*
		 * Change name of second entity to that of first entity
		 */
		$entity2->setName( $entityName1 );
		
		/*
		 * Entity is found in DB, the transformer should:
		 * - refresh the managed entity to preserve its original DB data
		 * - return the entity with the same name found in DB ( entity1 )
		 */
		$transformedEntity = $this->entityTransformer->reverseTransform( $entity2 );
		
		/*
		 * First, we check that the old entity kept its name
		 */
		$this->assertEquals( $entityName2, $this->repository->find( $entity2Id )->getName() );
		
		/*
		 * Lastly, we check that the transformer returned the entity with the same name found in DB ( entity1 )
		 */
		$this->assertEquals( $entity1, $transformedEntity );
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function tearDown()
	{
		parent::tearDown();
		$this->om->close();
	}
}