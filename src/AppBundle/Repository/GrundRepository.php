<?php

namespace AppBundle\Repository;

use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\DBAL\Types\GrundType;
use AppBundle\Entity\Salgsomraade;
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

  /**
   * Get list of 'Grund' of 'type' sold in 'year'
   *
   * @param $type
   * @param $year
   * @param bool $quarter
   * @return array
   */
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
   * Get list of 'Grund' of 'type' belonging to 'salgsomaraade'
   *
   * @param $type
   * @param \AppBundle\Entity\Salgsomraade $salgsomraade
   * @param bool $year
   * @return array
   */
  public function getGrundeByTypeSalgsomraade($type, Salgsomraade $salgsomraade, $year = NULL) {
    $qb = $this->getEntityManager()->createQueryBuilder();

    $qb->select('g', 'pb', 'kpb')
      ->from('AppBundle:Grund', 'g')
      ->leftJoin('g.postby', 'pb')
      ->leftJoin('g.koeberPostby', 'kpb')
      ->where('g.type = :type')
      ->andWhere('g.salgstatus = :salgstatus')
      ->andWhere('g.salgsomraade = :salgsomraade')
      ->addOrderBy('g.vej', 'ASC')
      ->addOrderBy('g.husnummer', 'ASC')
      ->addOrderBy('g.bogstav', 'ASC')
      ->setParameter('type', $type)
      ->setParameter('salgstatus', GrundSalgStatus::SOLGT)
      ->setParameter('salgsomraade', $salgsomraade);

    if ($year) {
      $qb->andWhere('YEAR(COALESCE(g.beloebanvist, g.accept)) = :year')
        ->setParameter('year', $year);
    }

    return $qb->getQuery()->getResult();
  }

  /**
   * Get Stats for 'betalte' Grunde by type, if 'group' grouped by year sold else in total
   *
   * @param $grundType
   * @param bool $group
   * @return array
   */
  public function getStatsBetalteIalt($grundType, $group = TRUE) {

    $sql = "SELECT YEAR(COALESCE(beloebAnvist,accept)) as year, COUNT(YEAR(COALESCE(beloebAnvist,accept))) as thecount, SUM(pris) as pris, SUM(salgsPrisUMoms) as salgspris ";
    $sql .= "FROM Grund WHERE type = ? AND salgStatus = '" . GrundSalgStatus::SOLGT . "' ";
    if($group) {
      $sql .= "GROUP BY YEAR(COALESCE(beloebAnvist,accept)) ";
      $sql .= "ORDER BY YEAR(COALESCE(beloebAnvist,accept)) DESC";
    }

    $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
    $stmt->bindValue(1, $grundType);
    $stmt->execute();

    return $group ? $stmt->fetchAll() : $stmt->fetch(\PDO::FETCH_ASSOC);

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
   * Get Stats for 'betalte' Grunde by type, year
   *
   * @param \AppBundle\DBAL\Types\GrundType $grundType
   * @param \AppBundle\Entity\Salgsomraade $salgsomraade
   * @return array
   */
  public function getStatsOmraade($grundType, $salgsomraade) {

    $sql = "SELECT SUM(pris) as pris,SUM(salgsPrisUMoms) as salgspris, count(pris) as thecount ";
    $sql .= "FROM Grund WHERE type = ? AND salgStatus = '" . GrundSalgStatus::SOLGT . "' ";
    $sql .= "AND salgsomraadeId = ? ";

    $values = [$grundType, $salgsomraade->getId()];
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

  /**
   * Get Stats for 'betalte' Grunde by type, grouped by 'salgsOmraade'
   *
   * @param \AppBundle\DBAL\Types\GrundType $grundType
   * @return array
   */
  public function getStatsOmraadeIalt($grundType, $group = true) {

    $sql = "SELECT COUNT(YEAR(COALESCE(beloebAnvist,accept))) as thecount, SUM(pris) as pris, SUM(salgsPrisUMoms) as salgspris, s.titel AS salgsomraadeTitel, s.id AS salgsomraadeId, s.titel AS salgsomraadeTitel ";
    $sql .= "FROM Grund g ";
    $sql .= "LEFT JOIN Salgsomraade s ON g.salgsomraadeId = s.id ";
    $sql .= "WHERE g.type = ? AND g.salgStatus = '" . GrundSalgStatus::SOLGT . "' ";
    if($group) {
      $sql .= "GROUP BY salgsomraadeId ";
      $sql .= "ORDER BY s.titel ASC ";
    }

    $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
    $stmt->bindValue(1, $grundType);
    $stmt->execute();

    return $group ? $stmt->fetchAll() : $stmt->fetch(\PDO::FETCH_ASSOC);

  }

  /**
   * Get Stats for 'betalte' Grunde by type, salgsomraade
   *
   * @param $grundType
   * @param \AppBundle\Entity\Salgsomraade $salgsomraade
   * @return mixed
   */
  public function getStatsOmraadeByType($grundType, Salgsomraade $salgsomraade) {

    $sql = "SELECT YEAR(COALESCE(beloebAnvist,accept)) as year, COUNT(YEAR(COALESCE(beloebAnvist,accept))) as thecount, SUM(pris) as pris, SUM(salgsPrisUMoms) as salgspris ";
    $sql .= "FROM Grund WHERE type = ? AND salgStatus = '" . GrundSalgStatus::SOLGT . "' ";
    $sql .= "AND salgsomraadeId = ? ";
    $sql .= "GROUP BY year ";

    $values = [$grundType, $salgsomraade->getId()];
    $types = [\PDO::PARAM_STR, \PDO::PARAM_INT];
    $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $values, $types);

    return $stmt->fetchAll();
  }
}