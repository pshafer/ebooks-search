<?php

namespace EbooksBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EbooksBundle\Entity\Vendor;

class VendorRepository extends EntityRepository
{
  public function findAllOrderedByName()
  {
    $query = $this->createAlpabeticalQueryBuilder();

    return $query->getQuery()->execute();
  }

  public function createAlpabeticalQueryBuilder()
  {
    return $this->createQueryBuilder('vendor')
      ->orderBy('vendor.name', 'ASC');
  }
}