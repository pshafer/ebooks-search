<?php

namespace AppSecurityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
  /**
   * {@inheritdoc}
   *
   */
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder();
    $rootNode = $treeBuilder->root('app_security');

    $rootNode
      ->children()
        ->arrayNode('cas_auth')
          ->children()
            ->booleanNode('cas_debug')
                ->defaultFalse()
            ->end()
            ->scalarNode('cas_debug_file')->end()
            ->scalarNode('cas_server_host')->end()
            ->scalarNode('cas_server_uri')->end()
            ->integerNode('cas_server_port')
              ->defaultValue(443)
            ->end()
            ->scalarNode('cas_server_version')->end()
        ->end() // cas_auth
      ->end();

    return $treeBuilder;
  }



}