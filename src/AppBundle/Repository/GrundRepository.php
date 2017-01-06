<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GrundRepository extends EntityRepository
{

  /**
   * Get Query for Grunde active for sale for given salgsomraade
   *
   * @param null $salgsomraadeId
   * @return \Doctrine\ORM\Query
   */
  public function getGrundeForSalgsOmraade($salgsomraadeId = null)
  {
    $qb = $this->getEntityManager()->createQueryBuilder();

    $qb->select('g')
        ->from('AppBundle:Grund', 'g')
        ->where('g.annonceresej != 1')
        ->andWhere('g.datoannonce < :now')
        ->addOrderBy('g.vej', 'ASC')
        ->addOrderBy('g.husnummer', 'ASC')
        ->addOrderBy('g.bogstav', 'ASC')
        ->setParameter('now', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME);

    if($salgsomraadeId) {
      $qb->andWhere('g.salgsomraade = :salgsomraadeid')
          ->setParameter('salgsomraadeid', $salgsomraadeId);
    }

    return $qb->getQuery();
  }
}