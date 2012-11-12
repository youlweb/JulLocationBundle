<?php 

// State form

namespace Jul\LocationBundle\Form\Type;

use Jul\LocationBundle\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StateType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder
			->add( 'name', 'hidden' )
			->add( 'code', 'hidden' )
		;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Jul\LocationBundle\Entity\State'
		));
	}
	
	public function getName()
	{
		return 'state';
	}
}