<?php

namespace AppSecurityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppSecurityBundle\Entity\User;


class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
  /**
   * @var ContainerInterface
   */
  private $container;

  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  public function load(ObjectManager $manager)
  {
    $userAdmin = new User();
    $userAdmin->setUsername('admin');

    $encoder = $this->container->get('security.password_encoder');
    $password = $encoder->encodePassword($userAdmin, 'test');

    $userAdmin->setPassword($password);
    $userAdmin->setFirstName('Library');
    $userAdmin->setLastName('Admin');
    $userAdmin->setEmail('library@rowan.edu');
    $userAdmin->setRole('ROLE_ADMIN');

    $user1 = new User();
    $user1->setUsername('shafer');

    $password = $encoder->encodePassword($user1, '4six94');

    $user1->setPassword($password);
    $user1->setFirstName('Library');
    $user1->setLastName('Admin');
    $user1->setEmail('shafer@rowan.edu');
    $user1->setRole('ROLE_USER');

    $manager->persist($userAdmin);
    $manager->persist($user1);
    $manager->flush();
  }
}