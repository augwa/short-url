<?php

namespace Augwa\ShortUrlBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('augwa_short_url');

        $rootNode
            ->children()
                ->arrayNode('geoip2')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('database_location')
                            ->defaultValue('%kernel.root_dir%/../data/geoip2/GeoLite2-Country.mmdb')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->addDefaultsIfNotSet()->end();

        return $treeBuilder;
    }
}
