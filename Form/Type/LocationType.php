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

namespace Jul\LocationBundle\Form\Type;

use Jul\LocationBundle\Form\DataTransformer\LocationTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LocationType extends AbstractType
{
	/**
	 * @var string
	 */
	private $entityType;
	
	/**
	 * @var ObjectManager;
	 */
	private $om;
	
	/**
	 * Options sent via config
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
	
	public function buildForm( FormBuilderInterface $builder, array $options )
	{
		$entitiesArray = array(
				'location' => array( 'city', 'state', 'country' ),
				'city' => array( 'state', 'country' ),
				'state' => array( 'country' ),
				'country' => array()
		);
		
		/*
		 * Generate form builder fields from config
		 */
		foreach( $this->configOptions[ $this->entityType ][ 'fields' ] as $field => $fieldArray )
		{	
			if( $fieldArray[ 'enabled' ] )
			{
				// Set HTML5 'required' option according to config
				if( ! isset( $fieldArray[ 'options' ][ 'required' ] ) ) $fieldArray[ 'options' ][ 'required' ] = $this->configOptions[ $this->entityType ][ 'fields' ][ $field ][ 'required' ];
				
				$builder->add( $field, $fieldArray[ 'type' ], $fieldArray[ 'options' ] );
			}
		}
		
		foreach( $entitiesArray[ $this->entityType ] as $entity )
		{
			if( ! $this->configOptions[ $entity ][ 'data_class' ] ) continue;
			
			if( $this->configOptions[ $entity ][ 'data_class' ] )
			{
				$builder->add( $entity, 'Jul' . ucfirst( $entity ) . 'Field' );
				break;
			}
		}
		
		$transformer = new LocationTransformer( $this->entityType, $this->om, $this->configOptions );
		
		$builder->addModelTransformer( $transformer );
	}
	
	public function setDefaultOptions( OptionsResolverInterface $resolver )
	{
		/*
		 * Generate Validation array from config
		 */
		$validationArray = array();
		
		foreach( $this->configOptions[ $this->entityType ][ 'fields' ] as $field => $fieldArray )
		{
			if( $fieldArray[ 'enabled' ] && $fieldArray[ 'required' ] )
			{
				array_push( $validationArray, $this->entityType . '_' . $field );
			}
		}
		
		$resolver->setDefaults( array(
			'data_class' => $this->configOptions[ $this->entityType ][ 'data_class' ],
			'validation_groups' => $validationArray,
			'cascade_validation' => true
		));
	}
	
	public function getParent()
	{
		return 'form';
	}

	public function getName()
	{
		return 'Jul' . ucfirst( $this->entityType ) . 'Field';
	}
}