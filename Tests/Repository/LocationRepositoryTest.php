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

namespace Jul\LocationBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Jul\LocationBundle\Repository\LocationRepository;

/**
 * CAUTION! The LocationRepository test uses your DB connection, therefore:
 * - You must have a working JulLocationBundle setup with configured entities.
 * - The test will INSERT a random entity in your database.
 */
class LocationRepositoryTest extends WebTestCase
{
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
	}
	
	public function testLocationRepository()
	{
		echo "\n\n\x1B[31mCAUTION!\x1B[37m The LocationRepository test uses your DB connection, therefore:";
		echo "\n - You must have a working JulLocationBundle setup with configured entities.";
		echo "\n - The test will INSERT a random entity in your database.";
		
		$repository = new LocationRepository( 'location', $this->om, $this->configOptions );
		
		$location = $this->getLocation();
		
		$locationDB = $repository->findEntityObject( $location );
		
		$this->assertEquals( $locationDB->getName(), $location->getName() );
	}
	
	/**
	 * Create a fully random Location entity
	 * 
	 * @return Location
	 */
	private function getLocation()
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
		
		$this->om->persist( $country );
		$this->om->persist( $state );
		$this->om->persist( $city );
		$this->om->persist( $location );
		
		$this->om->flush();
		
		return $location;
	}
}