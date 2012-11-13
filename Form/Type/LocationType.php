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
	 * @param ObjectManager $om
	 */
	public function __construct(ObjectManager $om)
	{
		$this->om = $om;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$transformer = new LocationToObjectTransformer($this->om);
		
		$builder
			
			->add( 'name', 'hidden', array( 'error_bubbling' => false ) )
			->add( 'fullname', 'text', array( 'label' => 'City', 'attr' => array( 'placeholder' => 'City' )))
			->add( 'address' )
			->add( 'latitude', 'hidden' )
			->add( 'longitude', 'hidden' )
			
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