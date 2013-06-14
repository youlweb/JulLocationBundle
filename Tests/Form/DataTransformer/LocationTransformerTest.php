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

namespace Jul\LocationBundle\Tests\Form\DataTransformer;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Jul\LocationBundle\Form\DataTransformer\LocationTransformer;

/**
 * CAUTION! The LocationTransformer test uses your DB connection, therefore:
 * - You must have a working JulLocationBundle setup with configured entities.
 * - The test will INSERT a random entity in your database.
 */
class LocationTransformerTest extends WebTestCase
{
	/**
	 * @var LocationTransformer
	 */
	private $transformer;
	
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $om;
	
	/**
	 * @var array
	 */
	private $configOptions;
	
	/**
	 * {@inheritDoc}
	 */
	public function setUp()
	{
		/*
		 * Kernel connection
		 */
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		$this->om = static::$kernel->getContainer()->get( 'doctrine' )->getManager();
		
		/*
		 * Load JulLocationBundle user configuration
		 */
		$client = static::createClient();
		$this->configOptions = $client->getContainer()->getParameter( 'jul_location.options' );
		
		if( 
			! $this->configOptions[ 'location' ][ 'data_class' ] || 
			! $this->configOptions[ 'city' ][ 'data_class' ] || 
			! $this->configOptions[ 'state' ][ 'data_class' ] || 
			! $this->configOptions[ 'country' ][ 'data_class' ]
			
			) throw new \Exception( 'Every entity in the JulLocationBundle must be configured.' );
		
		/*
		 * Force field configuration:
		 * - Every field must be enabled.
		 * - Every field must be an identifier.
		 */
		$this->configOptions[ 'location' ][ 'fields' ] = array(
				'name' => array( 'enabled' => true, 'identifier' => true ),
				'long_name' => array( 'enabled' => true, 'identifier' => true ),
				'address' => array( 'enabled' => true, 'identifier' => true ),
				'long_address' => array( 'enabled' => true, 'identifier' => true ),
				'postcode' => array( 'enabled' => true, 'identifier' => true ),
				'latitude' => array( 'enabled' => true, 'identifier' => true ),
				'longitude' => array( 'enabled' => true, 'identifier' => true ),
				'image_url' => array( 'enabled' => true, 'identifier' => true ),
				'website_url' => array( 'enabled' => true, 'identifier' => true ),
				'phone' => array( 'enabled' => true, 'identifier' => true )
		);
		$this->configOptions[ 'city' ][ 'fields' ] = array(
				'name' => array( 'enabled' => true, 'identifier' => true ),
				'long_name' => array( 'enabled' => true, 'identifier' => true ),
				'latitude' => array( 'enabled' => true, 'identifier' => true ),
				'longitude' => array( 'enabled' => true, 'identifier' => true )
		);
		$this->configOptions[ 'state' ][ 'fields' ] = array(
				'name' => array( 'enabled' => true, 'identifier' => true ),
				'long_name' => array( 'enabled' => true, 'identifier' => true ),
				'short_name' => array( 'enabled' => true, 'identifier' => true ),
				'latitude' => array( 'enabled' => true, 'identifier' => true ),
				'longitude' => array( 'enabled' => true, 'identifier' => true )
		);
		$this->configOptions[ 'country' ][ 'fields' ] = array(
				'name' => array( 'enabled' => true, 'identifier' => true ),
				'short_name' => array( 'enabled' => true, 'identifier' => true ),
				'latitude' => array( 'enabled' => true, 'identifier' => true ),
				'longitude' => array( 'enabled' => true, 'identifier' => true )
		);
		
		/*
		 * Transformer instance
		 */
		$this->transformer = new LocationTransformer( 'location', $this->om, $this->configOptions );
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function tearDown()
	{
		parent::tearDown();
		$this->om->close();
	}
	
	public function testTransform()
	{
		$location = $this->getLocation();
		$this->assertEquals( $location->getId(), $this->transformer->transform( $location )->getId() );
		
		$location = null;
		$this->assertNull( $location, $this->transformer->transform( $location ) );
	}
	
	public function testReverseTransform()
	{
		echo "\n\n\x1B[31mCAUTION!\x1B[37m The LocationTransformer test uses your DB connection, therefore:";
		echo "\n - Every entity in the JulLocationBundle must be configured.";
		echo "\n - The test will INSERT random entities in your database.";
		
		/*
		 * This test enforces that the data transformer doesn't allow entities
		 * with existing names to be added in the DB.
		 * 
		 * This part applies to new entities that are not managed by Doctrine.
		 */
		$location = $this->getLocation();
		
		/*
		 * This entity is random, thus new, so the transformer should simply return it.
		 */
		$this->assertEquals( $this->transformer->reverseTransform( $location ), $location );
		
		/*
		 * Copy the entity.
		 */
		$locationCopy = clone $location;
		
		/*
		 * Persist original entity in DB.
		 */
		$this->om->persist( $location );
		$this->om->flush();
		
		/*
		 * Calling the transformer with the clone should return the original DB entity.
		 */
		$this->assertEquals( $this->transformer->reverseTransform( $locationCopy )->getId(), $location->getId() );
		
		
		/*
		 * This part applies in the update context, where the entity is
		 * already managed by Doctrine.
		 * 
		 * In this context, additional processing must be done to preserve
		 * the managed entity's data integrity.
		 * 
		 * This test focuses on updating to an entity that doesn't exist
		 * in the database.
		 */
		
		$location = $this->getLocation();
		$originalName = $location->getName();
		$originalId = $location->getId();
		
		/*
		 * Entity exists in DB, the transformer returns the DB object.
		 */
		$this->assertEquals( $this->transformer->reverseTransform( $location )->getName(), $location->getName() );
		
		/*
		 * Update the managed entity by changing its name.
		 */
		$newName = uniqid( 'test_' );
		$location->setName( $newName );
		
		/*
		 * Entity is not found in DB, the transformer should:
		 * - Refresh the managed entity to preserve its original DB data.
		 * - Return a new entity with the new name.
		 */
		$updatedLocation = $this->transformer->reverseTransform( $location );
		
		/*
		 * Check that the old entity kept its name.
		 */
		$DBname = $this->om->getRepository( $this->configOptions[ 'location' ][ 'data_class' ] )->find( $originalId )->getName();
		
		$this->assertEquals( $originalName, $DBname );
		
		/*
		 * Check that the entity returned by the transformer has the new name.
		 */
		$this->assertEquals( $newName, $updatedLocation->getName() );
		
		/*
		 * This part applies in the update context, where the entity is
		 * already managed by Doctrine.
		 *
		 * This test focuses on updating to an entity that already exists
		 * in the database.
		 */
		
		/*
		 * Persist two identical random entities with different names
		 */
		$location1 = $this->getLocation();
		$location2 = clone $location1;
		$location2->setName( uniqid( 'test_' ) );
		$this->om->persist( $location2 );
		$this->om->flush( $location2 );
		
		$location1Id = $location1->getId();
		$location2Id = $location2->getId();
		$location1Name = $location1->getName();
		$location2Name = $location2->getName();
		
		/*
		 * Change name of second entity to that of first entity
		 */
		$location2->setName( $location1Name );
		
		/*
		 * Check that the transformer returned the entity with the same name
		*/
		$this->assertEquals( $this->transformer->reverseTransform( $location2 )->getId(), $location1->getId() );
		
		/*
		 * Check that DB location2 kept its name
		 */
		$DBname = $this->om->getRepository( $this->configOptions[ 'location' ][ 'data_class' ] )->find( $location2Id )->getName();
		$this->assertEquals( $location2Name, $DBname );	
	}
	
	/**
	 * Create a fully random Location entity
	 * 
	 * @return Location
	 */
	private function getLocation( $persist = true )
	{
		$location = new $this->configOptions[ 'location' ][ 'data_class' ];
		$city = new $this->configOptions[ 'city' ][ 'data_class' ];
		$state = new $this->configOptions[ 'state' ][ 'data_class' ];
		$country = new $this->configOptions[ 'country' ][ 'data_class' ];
		
		$location->setName( uniqid( 'test_' ) );
		$location->setLongName( uniqid( 'test_' ) );
		$location->setAddress( uniqid( 'test_' ) );
		$location->setLongAddress( uniqid( 'test_' ) );
		$location->setPostcode( rand( 10000, 99999 ) );
		$location->setLatitude( rand( 1, 10000 ) );
		$location->setLongitude( rand( 1, 10000) );
		$location->setImageUrl( uniqid( 'test_' ) );
		$location->setWebsiteUrl( uniqid( 'test_' ) );
		$location->setPhone( uniqid( 'test_' ) );
		
		$city->setName( uniqid( 'test_' ) );
		$city->setLongName( uniqid( 'test_' ) );
		$city->setLatitude( rand( 1, 10000 ) );
		$city->setLongitude( rand( 1, 10000 ) );
		
		$state->setName( uniqid( 'test_' ) );
		$state->setLongName( uniqid( 'test_' ) );
		$state->setShortName( uniqid( 'test_' ) );
		$state->setLatitude( rand( 1, 10000 ) );
		$state->setLongitude( rand( 1, 10000 ) );
		
		$country->setName( uniqid( 'test_' ) );
		$country->setShortName( uniqid( 'test_' ) );
		$country->setLatitude( rand( 1, 10000 ) );
		$country->setLongitude( rand( 1, 10000 ) );
		
		$state->setCountry( $country );
		$city->setState( $state );
		$location->setCity( $city );
		
		if( $persist )
		{
			$this->om->persist( $country );
			$this->om->persist( $state );
			$this->om->persist( $city );
			$this->om->persist( $location );
			
			$this->om->flush();
		}
		
		return $location;
	}
}