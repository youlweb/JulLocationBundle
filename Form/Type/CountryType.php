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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Jul\LocationBundle\Form\DataTransformer\LocationTransformer;

class CountryType extends AbstractType
{
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
	 * @param ObjectManager $om
	 * @param array $configOptions
	 */
	public function __construct( ObjectManager $om, $configOptions )
	{
		$this->om = $om;
		$this->configOptions = $configOptions;
	}
	
	public function buildForm( FormBuilderInterface $builder, array $options )
	{
		/*
		 * Generate form builder fields from config
		 */
		foreach( $this->configOptions[ 'country' ][ 'fields' ] as $field => $fieldArray )
		{
			if( $fieldArray[ 'enabled' ] )
			{
				$builder->add( $field, $fieldArray[ 'type' ], $fieldArray[ 'options' ] );
			}
		}
		
		$transformer = new LocationTransformer( 'country', $this->om, $this->configOptions );
		
		$builder->addModelTransformer( $transformer );
	}
	
	public function setDefaultOptions( OptionsResolverInterface $resolver )
	{
		/*
		 * Generate Validation array from config
		*/
		$validationArray = array();
		
		foreach( $this->configOptions[ 'country' ][ 'fields' ] as $field => $fieldArray )
		{
			if( $fieldArray[ 'enabled' ] && $fieldArray[ 'required' ] )
			{
				array_push( $validationArray, "country_$field" );
			}
		}
		
		$resolver->setDefaults( array(
			'data_class' => $this->configOptions[ 'country' ][ 'data_class' ],
			'validation_groups' => $validationArray
		));
	}
	
	public function getParent()
	{
		return 'form';
	}

	public function getName()
	{
		return 'JulCountryField';
	}
}