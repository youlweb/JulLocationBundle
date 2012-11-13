<?php 

// City custom field type

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
	 * @param ObjectManager $om
	 */
	public function __construct(ObjectManager $om)
	{
		$this->om = $om;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$transformer = new CityToObjectTransformer($this->om);
		
		$builder
			->add( 'fullname', 'text', array( 'label' => 'City', 'attr' => array( 'placeholder' => 'City' )))
			->add( 'name', 'hidden', array( 'error_bubbling' => false ) )
			->add( 'postcode', 'hidden' )
			->add( 'latitude', 'hidden' )
			->add( 'longitude', 'hidden' )
			->add( 'state', new StateType() )
			->add( 'country', new CountryType() )
			->addModelTransformer($transformer);
		;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Jul\LocationBundle\Entity\City',
			'cascade_validation' => true,
			
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