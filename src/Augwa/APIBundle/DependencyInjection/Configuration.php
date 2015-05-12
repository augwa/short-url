<?php

namespace Augwa\APIBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('augwa_api');

        $rootNode
            ->children()
                ->scalarNode('exception_location')
                    ->defaultValue('%kernel.root_dir%/logs/exceptions')
                ->end()
                ->arrayNode('authentication_routes')
                    ->prototype('scalar')
                    ->end()
                ->end()
            ->end()
        ->addDefaultsIfNotSet()->end()
        ;

        return $treeBuilder;
    }
}
