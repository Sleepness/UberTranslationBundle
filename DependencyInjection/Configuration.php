<?php

namespace Sleepness\UberTranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class respond for building configuration of this bundle
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sleepness_uber_translation');

        $rootNode
            ->children()
            ->scalarNode('memcache_host')->isRequired()->cannotBeEmpty()->end()
            ->integerNode('memcache_port')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('supported_locales')
                ->defaultValue(array('en'))
                ->prototype('scalar')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
