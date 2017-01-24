<?php

namespace EbooksBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use EbooksBundle\Entity\Subject;

class SubjectRepository extends EntityRepository
{
  public function findByName($name)
  {
    return $this->createQueryBuilder('subject')
              ->andWhere('subject.name = :name')
              ->setParameter('name', $name)
              ->getQuery()
              ->execute();
  }

  public function findAllOrderedByName()
  {
    $query = $this->createAlpabeticalQueryBuilder();

    return $query->getQuery()->execute();
  }

  public function createAlpabeticalQueryBuilder()
  {
    return $this->createQueryBuilder('subject')
      ->orderBy('subject.name', 'ASC');
  }
}