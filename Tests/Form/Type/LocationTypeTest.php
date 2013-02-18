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

namespace Jul\LocationBundle\Tests\Form\Type;

use Jul\LocationBundle\Form\Type\LocationType;
use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Util\Inflector;

class LocationTypeTest extends TypeTestCase
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	public $om;
	
	/**
	 * @var array
	 */
	private $configOptions;
	
	/**
	 * @var array
	 */
	private $configOptionsBackup;
	
	/**
	 * @var array
	 */
	private $entitiesArray = array( 'location', 'city', 'state', 'country' );
	
	public function setUp()
	{
		parent::setUp();
		
		$managerInstance = new ObjectManagerInstance();
		
		$this->om = $managerInstance->getManager();
		$this->configOptions = $managerInstance->getOptions();
		$this->configOptionsBackup = $managerInstance->getOptions();
		
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
		foreach( $this->entitiesArray as $entity )
		{
			$this->configOptions[ $entity ][ 'data_class' ] = false;
			
			foreach( $this->configOptions[ $entity ][ 'fields' ] as $field => $config )
			{
				$this->configOptions[ $entity ][ 'fields' ][ $field ] = array( 'enabled' => true, 'required' => true, 'identifier' => true, 'type' => 'text', 'options' => array() );
			}
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function tearDown()
	{
		parent::tearDown();
		$this->om->close();
	}
	
	public function testBindValidData()
	{
		$formData = array();
		
		foreach( $this->entitiesArray as $entity )
		{
			foreach( $this->configOptions[ $entity ][ 'fields' ] as $field => $config )
			{
				$formData[ $entity ][ $field ] = 1;
			}
			
			$this->configOptions[ $entity ][ 'data_class' ] = $this->configOptionsBackup[ $entity ][ 'data_class' ];
			
			$type = new LocationType( $entity, $this->om, $this->configOptions );
			
			$form = $this->factory->create( $type );
			
			$object = new $this->configOptions[ $entity ][ 'data_class' ];
			
			$this->configOptions[ $entity ][ 'data_class' ] = false;
			
			foreach( $formData[ $entity ] as $key => $value )
			{
				$method = 'set' . Inflector::classify( $key );
			
				if( method_exists( $object, $method ) )
				{
					call_user_func( array( $object, $method ), $value );
				}
			}
			
			$form->bind( $formData[ $entity ] );
			
			$this->assertTrue( $form->isSynchronized() );
			
			$this->assertEquals( $object, $form->getData() );
			
			$children = $form->createView()->children;
			
			foreach( array_keys( $formData[ $entity ] ) as $key )
			{
				$this->assertArrayHasKey( $key, $children );
			}
		}
	}
}

class ObjectManagerInstance extends WebTestCase
{
	
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	public $om;
	
	/**
	 * @var array
	 */
	private $configOptions;
	
	public function __construct()
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
	}
	
	public function getManager()
	{
		return $this->om;
	}
	
	public function getOptions()
	{
		return $this->configOptions;
	}
}