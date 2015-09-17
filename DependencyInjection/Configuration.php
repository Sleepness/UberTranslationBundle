<?php

namespace Sleepness\UberTranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Respond for building configuration of bundle
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generate the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sleepness_uber_translation');
        $rootNode
            ->children()
                ->arrayNode('memcached')
                    ->children()
                        ->scalarNode('host')->end()
                        ->integerNode('port')->end()
                    ->end()
                ->end()
                ->arrayNode('redis')
                    ->children()
                        ->scalarNode('host')->end()
                        ->integerNode('port')->end()
                    ->end()
                ->end()
                ->arrayNode('supported_locales')
                    ->defaultValue(array('en'))
                    ->prototype('scalar')
                ->end()
        ;

        return $treeBuilder;
    }
}
