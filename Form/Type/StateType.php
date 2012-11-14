<?php 

// State field

namespace Jul\LocationBundle\Form\Type;

use Jul\LocationBundle\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Jul\LocationBundle\Form\DataTransformer\StateToObjectTransformer;

class StateType extends AbstractType
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
		$transformer = new StateToObjectTransformer($this->om);
		
		$arrayFields = array( 'name', 'fullname', 'code', 'latitude', 'longitude' );
		
		foreach( $arrayFields as $field )
		{
			$builder->add( $field, $this->configOptions[$field]['type'], $this->configOptions[$field]['options'] );
		}
		
		$builder
			->add( 'country', 'JulCountryField' )
			->addModelTransformer($transformer);
		;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Jul\LocationBundle\Entity\State'			
		));
	}
	
	public function getParent()
	{
		return 'form';
	}

	public function getName()
	{
		return 'JulStateField';
	}
}