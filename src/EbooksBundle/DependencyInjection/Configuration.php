<?php

namespace EbooksBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder();

    $rootNode = $treeBuilder->root('ebooks');

    $rootNode
      ->children()
        ->arrayNode('search_types')
          ->useAttributeAsKey('name')
          ->prototype('scalar')->end()
        ->end()
        ->arrayNode('result_display')
          ->children()
            ->integerNode('min_value')->defaultValue(10)->end()
            ->integerNode('max_value')->defaultValue(50)->end()
            ->integerNode('step')->defaultValue(10)->end()
            ->integerNode('default_value')->defaultValue(20)->end()
          ->end()
        ->end()
      ->end();

    return $treeBuilder;

  }
}