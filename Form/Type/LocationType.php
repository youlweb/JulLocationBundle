<?php 

// Location field

namespace Jul\LocationBundle\Form\Type;

use Jul\LocationBundle\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Jul\LocationBundle\Form\DataTransformer\LocationToObjectTransformer;

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
	public function __construct(ObjectManager $om, $locationOptions)
	{
		$this->om = $om;
		$this->locationOptions = $locationOptions;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$transformer = new LocationToObjectTransformer($this->om);
		
		$arrayFields = array( 'name', 'fullname', 'address', 'latitude', 'longitude' );
		
		foreach( $arrayFields as $field )
		{
			$builder->add( $field, $this->locationOptions[$field]['type'], $this->locationOptions[$field]['options'] );
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