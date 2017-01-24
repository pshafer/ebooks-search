<?php

namespace EbooksBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EbooksExtension extends Extension
{
  public function load(array $configs, ContainerBuilder $container)
  {
    $configuration = new Configuration();
    $config = $this->processConfiguration($configuration, $configs);

    $container->setParameter( 'ebooks.search_types', $config[ 'search_types' ]);
    $container->setParameter('ebooks.result_display', $config['result_display']);
  }
}