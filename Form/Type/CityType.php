<?php 

// City field

namespace Jul\LocationBundle\Form\Type;

use Jul\LocationBundle\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Jul\LocationBundle\Form\DataTransformer\CityToObjectTransformer;

class CityType extends AbstractType
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
		$transformer = new CityToObjectTransformer($this->om);
		
		$arrayFields = array( 'name', 'fullname', 'postcode', 'latitude', 'longitude' );
		
		foreach( $arrayFields as $field )
		{
			$builder->add( $field, $this->configOptions[$field]['type'], $this->configOptions[$field]['options'] );
		}
		
		$builder
			->add( 'state', 'JulStateField' )
			->addModelTransformer($transformer);
		;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Jul\LocationBundle\Entity\City',
			'cascade_validation' => true
		));
	}
	
	public function getParent()
	{
		return 'form';
	}

	public function getName()
	{
		return 'JulCityField';
	}
}