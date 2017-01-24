<?php

namespace AppSecurityBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManager;

class UserFormTokenAuthenticator extends AbstractGuardAuthenticator
{
  private $entitymanager;

  public function __construct(EntityManager $entitymanager) {
    $this->entitymanager = $entitymanager;
  }
}