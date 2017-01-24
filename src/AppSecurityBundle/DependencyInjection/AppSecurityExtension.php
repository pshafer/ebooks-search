<?php

namespace AppSecurityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;


class AppSecurityExtension extends Extension
{
  public function load(array $configs, ContainerBuilder $container) {

    $configuration = new Configuration();
    $config = $this->processConfiguration($configuration, $configs);

    $authenticator = $container->register(
        'app_security.cas_authenticator',
        'AppSecurityBundle\Security\CasAuthenticator'
    );

    $authenticator->setArguments(array($config));
  }
}