<?php

namespace DieSchittigs\ContaoGraphQLBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use DieSchittigs\ContaoGraphQLBundle\Type\Resolvers\Resolver;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;
        $rootNode = $treeBuilder->root('graphql');
        
        $rootNode
            ->useAttributeAsKey('name')
            ->arrayPrototype()
                ->ignoreExtraKeys()
                ->children()
                    ->scalarNode('singular')->end()
                    ->scalarNode('plural')->end()
                    ->scalarNode('resolver')
                        ->defaultValue(Resolver::class)
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }}
