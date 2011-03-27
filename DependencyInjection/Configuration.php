<?php

namespace BeSimple\GearmanBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('be_simple_gearman');

        $rootNode
            ->children()
                ->scalarNode('timeout')->defaultValue(-1)->end()
            ->end()
        ;

        $this->addServersSection($rootNode);

        return $treeBuilder->buildTree();
    }

    private function addServersSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('servers')
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')->end()
                        ->scalarNode('port')->defaultNull()->end()
                        ->arrayNode('active')
                            ->isRequired()
                            ->children()
                                ->booleanNode('worker')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
