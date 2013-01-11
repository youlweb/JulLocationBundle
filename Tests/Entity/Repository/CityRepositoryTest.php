<?php 

namespace Jul\LocationBundle\Tests\Entity\Repository;

use Jul\LocationBundle\Entity\City;
use Jul\LocationBundle\Entity\State;
use Jul\LocationBundle\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Caution: this test inserts a bunch of test entities in the DB.
 * 
 * @author julien
 *
 */
class CityRepositoryTest extends WebTestCase
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $om;
	
	/**
	 * @var \Doctrine\ORM\EntityRepository
	 */
	protected $entityRepository;
	
	/**
	 * {@inheritDoc}
	 */
	public function setUp()
	{
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		
		$this->om = static::$kernel->getContainer()->get('doctrine')->getEntityManager();
		
		$this->entityRepository = $this->om->getRepository('JulLocationBundle:City');
	}
	
	/*
	 * The getOneByCityName() repository function must:
	 * 
	 * - Retrieve a City entity if provided with a City name, a State name and a Country name
	 * - Allow any/every parameter to be NULL
	 * 
	 */
	public function testGetOneByCityName()
	{
		/*
		 * Create entities to be retrieved by name
		 */
		$countryName = uniqid( 'test_' );
		
		$country = new Country();
		$country->setName( $countryName );
		$this->om->persist( $country );
		
		$stateName = uniqid( 'test_' );
		
		$state = new State();
		$state->setName( $stateName );
		$state->setCountry( $country );
		$this->om->persist( $state );
		
		$cityName = uniqid( 'test_' );
		
		$city = new City();
		$city->setName( $cityName );
		$city->setState( $state );
		$this->om->persist( $city );
		
		$this->om->flush();
		
		/*
		 * Store entity's Id for comparison
		 */
		$entityId = $city->getId();
		
		/*
		 * Retrieve City entity
		 */
		$targetEntity = $this->entityRepository->getOneByCityName( $cityName, $stateName, $countryName );
		
		/*
		 * Assert that we retrieved the right entity
		 */
		$this->assertEquals( $entityId, $targetEntity->getId() );
		
		/*
		 * Try with all NULL parameters
		 */
		$targetEntity = $this->entityRepository->getOneByCityName();
		
		/*
		 * If an entity is found, assert that the data is indeed NULL
		 */
		if( $targetEntity !== NULL )
		{
			$this->assertNull( $targetEntity->getName() );
			$this->assertNull( $targetEntity->getState()->getName() );
			$this->assertNull( $targetEntity->getState()->getCountry()->getName() );
			
			return;
		}
		
		/*
		 * If not found, we create one and retrieve it
		 */
		if( ( $country = $this->om->getRepository('JulLocationBundle:Country')->findOneByName( null ) ) === null )
		{
			$country = new Country();
			$country->setName( NULL );
			$this->om->persist( $country );
		}
		
		if( ( $state = $this->om->getRepository('JulLocationBundle:State')->getOneByStateName() ) === null )
		{
			$state = new State();
			$state->setName( NULL );
			$state->setCountry( $country );
			$this->om->persist( $state );
		}
		
		$city = new City();
		$city->setName( NULL );
		$city->setState( $state );
		$this->om->persist( $city );
		
		$this->om->flush();
		
		/*
		 * Store entity's Id for comparison
		*/
		$entityId = $city->getId();
		
		/*
		 * Look for the entity
		*/
		$targetEntity = $this->entityRepository->getOneByCityName();
		
		/*
		 * Assert that we retrieved the right entity
		*/
		$this->assertEquals( $entityId, $targetEntity->getId() );
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