<?php

namespace AppBundle\Repository;

use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\DBAL\Types\GrundType;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Connection;

class GrundRepository extends EntityRepository {

  /**
   * Get Query for Grunde active for sale for given salgsomraade
   *
   * @param null $salgsomraadeId
   * @return \Doctrine\ORM\Query
   */
  public function getGrundeForSalgsOmraade($salgsomraadeId = NULL) {
    $qb = $this->getEntityManager()->createQueryBuilder();

    $qb->select('g')
      ->from('AppBundle:Grund', 'g')
      ->where('g.annonceres = 1')
      ->andWhere('g.datoannonce < :now')
      ->andWhere('g.sp_geometry IS NOT NULL')
      ->addOrderBy('g.vej', 'ASC')
      ->addOrderBy('g.husnummer', 'ASC')
      ->addOrderBy('g.bogstav', 'ASC')
      ->setParameter('now', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME);

    if ($salgsomraadeId) {
      $qb->andWhere('g.salgsomraade = :salgsomraadeid')->setParameter('salgsomraadeid', $salgsomraadeId);
    }

    return $qb->getQuery()->getResult();
  }

  public function getGrundeByTypeYear($type, $year, $quarter = NULL) {
    $qb = $this->getEntityManager()->createQueryBuilder();

    $qb->select('g', 'pb', 'kpb')
      ->from('AppBundle:Grund', 'g')
      ->leftJoin('g.postby', 'pb')
      ->leftJoin('g.koeberPostby', 'kpb')
      ->where('g.type = :type')
      ->andWhere('g.salgstatus = :salgstatus')
      ->andWhere('YEAR(COALESCE(g.beloebanvist, g.accept)) = :year')
      ->addOrderBy('QUARTER(COALESCE(g.beloebanvist, g.accept))', 'ASC')
      ->addOrderBy('g.vej', 'ASC')
      ->addOrderBy('g.husnummer', 'ASC')
      ->addOrderBy('g.bogstav', 'ASC')
      ->setParameter('type', $type)
      ->setParameter('salgstatus', GrundSalgStatus::SOLGT)
      ->setParameter('year', $year);

    if ($quarter) {
      $qb->andWhere('QUARTER(COALESCE(g.beloebanvist, g.accept)) = :quarter')
        ->setParameter('quarter', $quarter);
    }

    return $qb->getQuery()->getResult();
  }

  /**
   * Get Stats for 'betalte' Grunde by type, grouped by year sold
   *
   * @param \AppBundle\DBAL\Types\GrundType $grundType
   * @return array
   */
  public function getStatsBetalteIalt($grundType) {

    $sql = "SELECT YEAR(COALESCE(beloebAnvist,accept)) as year, COUNT(YEAR(COALESCE(beloebAnvist,accept))) as thecount, SUM(pris) as pris, SUM(salgsPrisUMoms) as salgspris ";
    $sql .= "FROM Grund WHERE type = ? AND salgStatus = '" . GrundSalgStatus::SOLGT . "' ";
    $sql .= "GROUP BY YEAR(COALESCE(beloebAnvist,accept)) ";
    $sql .= "ORDER BY YEAR(COALESCE(beloebAnvist,accept)) DESC";

    $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
    $stmt->bindValue(1, $grundType);
    $stmt->execute();

    return $stmt->fetchAll();

  }

  /**
   * Get Stats for 'betalte' Grunde by type, year
   *
   * @param \AppBundle\DBAL\Types\GrundType $grundType
   * @return array
   */
  public function getStatsBetalte($grundType, $year) {

    $sql = "SELECT SUM(pris) as pris,SUM(salgsPrisUMoms) as salgspris, count(pris) as thecount ";
    $sql .= "FROM Grund WHERE type = ? AND salgStatus = '" . GrundSalgStatus::SOLGT . "' ";
    $sql .= "AND YEAR(COALESCE(beloebAnvist,accept)) = ? ";

    $values = [$grundType, $year];
    $types = [\PDO::PARAM_STR, \PDO::PARAM_INT];
    $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $values, $types);

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  /**
   * Get Stats for 'betalte' Grunde by type, year grouped by quarter sold
   *
   * @param \AppBundle\DBAL\Types\GrundType $grundType
   * @return array
   */
  public function getStatsBetalteByQuarter($grundType, $year) {

    $quarters = array();
    $quarters[1] = [1, 2, 3];
    $quarters[2] = [4, 5, 6];
    $quarters[3] = [7, 8, 9];
    $quarters[4] = [10, 11, 12];

    $result = array();

    for ($i = 1; $i <= 4; $i++) {
      $sql = "SELECT SUM(pris) as pris,SUM(salgsPrisUMoms) as salgspris, count(pris) as thecount ";
      $sql .= "FROM Grund WHERE type = ? AND salgStatus = '" . GrundSalgStatus::SOLGT . "' ";
      $sql .= "AND YEAR(COALESCE(beloebAnvist,accept)) = ? ";
      $sql .= "AND MONTH(COALESCE(beloebAnvist,accept)) IN (?)";

      $values = [$grundType, $year, $quarters[$i]];
      $types = [\PDO::PARAM_STR, \PDO::PARAM_INT, Connection::PARAM_INT_ARRAY];
      $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $values, $types);

      $result[$i] = $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    return $result;
  }
}