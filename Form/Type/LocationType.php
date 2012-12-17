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
		
		foreach( $this->configOptions as $field => $arrayField )
		{
			if( $arrayField['active'] )
			{
				$builder->add( $field, $arrayField['type'], $arrayField['options'] );
			}
		}
				
		$builder
			->add( 'city', 'JulCityField' )
			->addModelTransformer($transformer);
		;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Jul\LocationBundle\Entity\Location',
			'validation_groups' => ($this->configOptions['validation']) ? array( 'Default', $this->configOptions['validation'] ) : array( 'Default' ),
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