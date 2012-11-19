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
		
		$arrayFields = array( 'name', 'shortname', 'latitude', 'longitude' );
		
		foreach( $arrayFields as $field )
		{
			if( $this->configOptions[$field]['active'] )
			{
				$builder->add( $field, $this->configOptions[$field]['type'], $this->configOptions[$field]['options'] );
			}
		}
		
		$builder
		->addModelTransformer($transformer);
		;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Jul\LocationBundle\Entity\Country',
			'validation_groups' => ($this->configOptions['validation']) ? array( 'Default', $this->configOptions['validation'] ) : array( 'Default' )
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