<?php 

// Location field

namespace Jul\LocationBundle\Form\Type;

use Jul\LocationBundle\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Jul\LocationBundle\Form\DataTransformer\LocationTransformer;

class LocationType extends AbstractType
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
	 */
	public function __construct(ObjectManager $om, $configOptions)
	{
		$this->om = $om;
		$this->configOptions = $configOptions;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$transformer = new LocationTransformer($this->om);
		
		/*
		 * Generate form builder fields from config
		 */
		foreach( $this->configOptions as $field => $fieldArray )
		{
			if( $fieldArray['active'] )
			{
				$builder->add( $field, $fieldArray['type'], $fieldArray['options'] );
			}
		}
		
		$builder
			->add( 'city', 'JulCityField' )
			->addModelTransformer($transformer);
		;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		/*
		 * Generate Validation array from config
		 */
		$validationArray = array();
		
		foreach( $this->configOptions as $field => $fieldArray )
		{
			if( $fieldArray['active'] && $fieldArray['validation'] )
			{
				array_push( $validationArray, "location$field" );
			}
		}
		
		$resolver->setDefaults(array(
			'data_class' => 'Jul\LocationBundle\Entity\Location',
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
		return 'JulLocationField';
	}
}