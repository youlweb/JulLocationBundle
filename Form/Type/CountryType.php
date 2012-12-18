<?php 

// Country field

namespace Jul\LocationBundle\Form\Type;

use Jul\LocationBundle\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Jul\LocationBundle\Form\DataTransformer\CountryTransformer;

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
	 */
	public function __construct(ObjectManager $om, $configOptions)
	{
		$this->om = $om;
		$this->configOptions = $configOptions;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$transformer = new CountryTransformer($this->om);
		
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
				array_push( $validationArray, "country$field" );
			}
		}
		
		$resolver->setDefaults(array(
			'data_class' => 'Jul\LocationBundle\Entity\Country',
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