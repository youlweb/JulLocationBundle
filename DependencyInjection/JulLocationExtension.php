<?php

namespace Jul\LocationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JulLocationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        //print_r( $config );
        //exit;
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $container->setParameter('jul_location.locationOptions', $config['inputFields']['location']);
        $container->setParameter('jul_location.cityOptions', $config['inputFields']['city']);
        $container->setParameter('jul_location.stateOptions', $config['inputFields']['state']);
        $container->setParameter('jul_location.countryOptions', $config['inputFields']['country']);
    }
}
