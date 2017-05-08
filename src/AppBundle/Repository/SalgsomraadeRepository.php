<?php

namespace AppBundle\Repository;

use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\DBAL\Types\GrundType;
use AppBundle\Entity\Lokalplan;
use AppBundle\Entity\Salgsomraade;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Connection;

class SalgsomraadeRepository extends EntityRepository {

  public function findMaxLpLoebenummerById(Lokalplan $lokalplan) {
    $highest_id = $this->getEntityManager()->createQueryBuilder()
      ->select('MAX(s.lploebenummer)')
      ->from('AppBundle:Salgsomraade', 's')
      ->where('s.lokalplan = :lokalplan')
      ->setParameter('lokalplan', $lokalplan)
      ->getQuery()
      ->getSingleScalarResult();

    return $highest_id;
  }

}