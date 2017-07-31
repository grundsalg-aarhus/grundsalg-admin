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
      ->andWhere('g.spGeometry IS NOT NULL')
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

  /**
   * Get Stats for 'Alle grunde'
   *
   * @return array
   */
  public function getStatsAlleGrunde($depth = 0, $node = null) {
    $delimiter = '__';
    $gTypes = [
      'Storparcel' => 1,
      'Erhvervsgrund' => 2,
      'Parcelhusgrund' => 3,
      'Andre' => 4,
      'Andre grunde' => 5,
      'Off. formål' => 6,
      '-> Grundtype mangler' => 7,
    ];

    $children = [];

    if ($depth == 0) {
      $antal = 0;
      $pris = 0;
      $minPris = 0;
      $m2 = 0;
      $maxm2 = 0;

      $sql = "SELECT SUM(g.pris) as pris,SUM(g.minBud) as minPris, SUM(g.bruttoAreal) as m2,SUM(g.maxEtageM2) as maxm2,COUNT(g.id) as thecount ";
      $sql .= "FROM Grund as g ";
      $sql .= "LEFT JOIN Salgsomraade as s on s.id=g.salgsomraadeId ";
      $sql .= "WHERE s.sagsNr = '' OR g.salgsomraadeId IS NULL";

      $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
      if ($row = $stmt->fetch()) {
        $child = [];

        $child["id"] = "-1" . $delimiter . "-1";
        $child["tree"] = "-> Intet sagsnummer";

        $antal += $row["thecount"];
        $child["antal"] = $row["thecount"];

        $pris += $row["pris"];
        $child["pris"] = $row["pris"];

        $minPris += $row["minPris"];
        $child["minPris"] = $row["minPris"];

        $m2 += $row["m2"];
        $child["m2"] = $row["m2"];

        $maxm2 += $row["maxm2"];
        $child["maxm2"] = $row["maxm2"];
        $child["leaf"] = false;

        $child['_breakdown'] = $this->getStatsAlleGrunde($depth + 1, $child['id']);

        $children[] = $child;
      }

      $sql = "SELECT s.sagsNr, l.titel,l.id, SUM(g.pris) as pris, SUM(g.minBud) as minPris, SUM(g.bruttoAreal) as m2,SUM(g.maxEtageM2) as maxm2,COUNT(g.id) as thecount ";
      $sql .= "FROM Grund as g ";
      $sql .= "JOIN Salgsomraade as s on s.id=g.salgsomraadeId ";
      // $sql .= "FULL JOIN Lokalplan as l on l.id=s.lokalPlanId ";
      $sql .= "JOIN Lokalplan as l on l.id=s.lokalPlanId ";
      $sql .= "WHERE s.sagsNr <> '' ";
      $sql .= "GROUP BY s.sagsNr, l.titel,l.id ";
      $sql .= "ORDER BY s.sagsNr, l.titel ASC";

      $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
      while ($row = $stmt->fetch()) {
        $child = [];

        $omraade = "";

        $titel = $row["titel"];
        if ($titel == null) {
          $titel = "";
        }

        $sagsnummer = $row["sagsNr"];
        $omraade = $sagsnummer . " - " . $titel;

        $child["id"] = $sagsnummer . $delimiter . $row["id"];
        $child["tree"] = $omraade;

        $antal += $row["thecount"];
        $child["antal"] = $row["thecount"];

        $pris += $row["pris"];
        $child["pris"] = $row["pris"];

        $minPris += $row["minPris"];
        $child["minPris"] =$row["minPris"];

        $m2 += $row["m2"];
        $child["m2"] = $row["m2"];

        $maxm2 += $row["maxm2"];
        $child["maxm2"] =$row["maxm2"];

        $child["leaf"] = false;

        $child['_breakdown'] = $this->getStatsAlleGrunde($depth + 1, $child['id']);

        $children[] = $child;
      }

      $child = [];
      $child["id"] = "total";
      $child["tree"] = "Total";
      $child["antal"] = $antal;
      $child["pris"] = $pris;
      $child["minPris"] = $minPris;
      $child["m2"] = $m2;
      $child["maxm2"] = $maxm2;
      $child["leaf"] = true;

      $children[] = $child;
    } elseif ($depth == 1) {
      $values = explode($delimiter, $node);
      $sagsNr = $values[0];
      $id = $values[1];

      $sql = "SELECT g.type grundType, IFNULL(s.sagsNr,'') as sagsNr, SUM(g.pris) as pris,SUM(g.minBud) as minPris, SUM(g.bruttoAreal) as m2,SUM(g.maxEtageM2) as maxm2,COUNT(g.id) as thecount ";
      $sql .= "FROM Grund as g ";
      $sql .= "LEFT JOIN Salgsomraade as s on s.id=g.salgsomraadeId ";

      if ($sagsNr == -1) {
        $sql .= "WHERE s.sagsNr = '' OR g.salgsomraadeId IS NULL ";
      } else {
        if ($id == "null"){
          $sql .= "WHERE s.sagsNr = '" . $sagsNr . "' ";
        } else {
          $sql .= "JOIN Lokalplan as l on l.id=s.lokalPlanId ";
          $sql .= "WHERE s.sagsNr = '" . $sagsNr . "' AND l.id='" . $id . "' ";
        }
      }

      $sql .= "GROUP BY g.type,IFNULL(s.sagsNr,'') ";
      $sql .= "ORDER BY g.type,IFNULL(s.sagsNr,'') ASC";

      $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
      while ($row = $stmt->fetch()) {
        $child = [];

        $gType = $row["grundType"];
        if ($gType == null) {
          $gType = "-> Grundtype mangler";
        }

        $child["id"] = $sagsNr . $delimiter . $id . $delimiter . $gTypes[$gType];
        $child["tree"] = $gType;
        $child["antal"] = $row["thecount"];
        $child["pris"] = $row["pris"];
        $child["minPris"] = $row["minPris"];
        $child["m2"] = $row["m2"];
        $child["maxm2"] = $row["maxm2"];
        $child["leaf"] = false;

        $child['_breakdown'] = $this->getStatsAlleGrunde($depth + 1, $child['id']);

        $children[] = $child;
      }
    } else {
      $values = explode($delimiter, $node);

      $sagsNr = $values[0];
      $id = $values[1];
      $gType = $values[2];

      $grundTypeSQL = "g.type='" . array_search($gType, $gTypes) . "' ";

      if ($gType == "7") { //ingen grundtype
        $grundTypeSQL = "g.type IS NULL ";
      }

      $sql = "SELECT g.vej, IFNULL(g.husNummer,'') as husNummer, IFNULL(g.bogstav,'') as bogstav, g.type, g.pris,g.id,g.minBud as minPris, g.bruttoAreal as m2,g.maxEtageM2 as maxm2 ";
      $sql .= "FROM Grund as g ";
      $sql .= "LEFT JOIN Salgsomraade as s on s.id=g.salgsomraadeId ";
      if ($sagsNr == "-1"){
        $sql .= "WHERE (s.sagsNr = '' OR g.salgsomraadeId IS NULL) and " . $grundTypeSQL;
      } else {
        if ($id == "null") {
          $sql .= "WHERE s.sagsNr = '" . $sagsNr . "' and " . $grundTypeSQL;
        } else {
          $sql .= "JOIN Lokalplan as l on l.id=s.lokalPlanId ";
          $sql .= "WHERE s.sagsNr = '" . $sagsNr . "' and l.id='" . $id . "' and " . $grundTypeSQL;
        }
      }

      $sql .= "ORDER BY g.vej,CAST(g.husNummer as int) ASC";
      $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
      while ($row = $stmt->fetch()) {
        $child = [];

        $adr = $row["vej"] . " " .  $row["husNummer"] . " " . $row["bogstav"];

        $child["id"] = $row["id"];
        $child["type"] = $row["type"];
        $child["tree"] = trim($adr);
        $child["pris"] = $row["pris"];
        $child["minPris"] = $row["minPris"];
        $child["m2"] = $row["m2"];
        $child["maxm2"] = $row["maxm2"];
        $child["leaf"] = true;

        $children[] = $child;
      }
    }

    return $children;
  }

}