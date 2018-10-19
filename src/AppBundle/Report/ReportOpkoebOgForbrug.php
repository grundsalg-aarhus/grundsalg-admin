<?php

namespace AppBundle\Report;

use Doctrine\DBAL\Types\Type;

/**
 * Report.
 */
class ReportOpkoebOgForbrug extends Report {
  protected $title = 'Opkøb og forbrug i perioden';

  /**
   * {@inheritdoc}
   */
  protected function writeData() {
    // GrundSalgServlets/src/com/Symfoni/AArhus/GrundSalg/Servlets/Report.java:opkoeb
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');

    $this->writeRow(['Opkøb og forbrug i perioden ' . $this->formatDate($startdate) . '–' . $this->formatDate($enddate)]);
    $this->writeRow([]);
    $this->writeRow([NULL, NULL, 'Opkøb', NULL, 'Forbrugt i perioden', NULL, 'Forbrugt i alt']);
    $this->writeRow([
      'Matrikelnr.',
      'Opkøbt:',
      'Areal i m2',
      'Beløb',
      'Areal i m2',
      'Beløb',
      'Areal i m2',
      'Beløb',
    ]);

    $beloebTotal = 0;
    $arealTotal = 0;
    $forbrugIAltArealTotal = 0;
    $forbrugIAltBeloebTotal = 0;
    $forbrugPeriodeArealTotal = 0;
    $forbrugPeriodeBeloebTotal = 0;

    $sql = "SELECT l.nr,o.id as opkoebid,o.procentAfLP,(IFNULL(matrik1,'')+IFNULL(matrik2,''))+' '+ejerlav+' ('+l.nr+')' as matrik,opkoebDato,m2,(CAST(m2 as double)*CAST(pris as double)) as beloeb  ";
    $sql .= "FROM Opkoeb as o ";
    $sql .= "join Lokalplan as l on l.id=o.lokalPlanId ";
    $sql .= "WHERE opkoebDato >= :fromDate and opkoebDato <= :toDate";

    $stmt = $this->entityManager->getConnection()->prepare($sql);
    $stmt->bindValue(':fromDate', $startdate, Type::DATE);
    $stmt->bindValue(':toDate', $enddate, Type::DATE);
    $stmt->execute();

    while ($row = $stmt->fetch()) {
      $matr = $row['matrik'];
      $date = new \DateTime($row['opkoebDato']);
      $areal = $row['m2'];
      $beloeb = $row['beloeb'];
      $prism2 = $beloeb / $row['m2'];
      $procentAfLP = $row['procentAfLP'];
      $forbrugPeriodeAreal = 0;
      $forbrugPeriodeBeloeb = 0;
      $forbrugIAltAreal = 0;
      $forbrugIAltBeloeb = 0;

      $sql = "SELECT IFNULL(((SUM((g.areal*l.forbrugsAndel)/100)*:procentAfLP)/100),0) as forbrugIalt ";
      $sql .= "FROM Grund as g ";
      $sql .= "JOIN Salgsomraade as s on s.id=g.salgsomraadeId ";
      $sql .= "JOIN Lokalplan as l on l.id=s.lokalPlanId ";
      $sql .= "where salgStatus='Solgt' and l.nr = :nr";

      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->bindValue(':procentAfLP', $procentAfLP, Type::FLOAT);
      $stmt->bindValue(':nr', $row['nr'], Type::INTEGER);
      $stmt->execute();
      if ($item = $stmt->fetch()) {
        $forbrugIAltAreal = $item['forbrugIalt'];
      }

      $sql = "SELECT IFNULL(((SUM((g.areal*l.forbrugsAndel)/100)*:procentAfLP)/100),0) as forbrugPeriode ";
      $sql .= "FROM Grund as g ";
      $sql .= "JOIN Salgsomraade as s on s.id=g.salgsomraadeId ";
      $sql .= "JOIN Lokalplan as l on l.id=s.lokalPlanId ";
      $sql .= "where salgStatus='Solgt' and l.nr=:nr AND ";
      $sql .= "BeloebAnvist IS NOT NULL AND (BeloebAnvist  >= :fromDate And BeloebAnvist <= :toDate)";

      $stmt = $this->entityManager->getConnection()->prepare($sql);
      $stmt->bindValue(':procentAfLP', $procentAfLP, Type::FLOAT);
      $stmt->bindValue(':nr', $row['nr'], Type::INTEGER);
      $stmt->bindValue(':fromDate', $startdate, Type::DATE);
      $stmt->bindValue(':toDate', $enddate, Type::DATE);
      $stmt->execute();
      if ($item = $stmt->fetch()) {
        $forbrugPeriodeAreal = $item['forbrugPeriode'];
      }

      $forbrugPeriodeBeloeb = $forbrugPeriodeAreal * $prism2;
      $forbrugIAltBeloeb = $forbrugIAltAreal * $prism2;

      $beloebTotal += $beloeb;
      $arealTotal += $areal;
      $forbrugIAltArealTotal += $forbrugIAltAreal;
      $forbrugIAltBeloebTotal += $forbrugIAltBeloeb;
      $forbrugPeriodeArealTotal += $forbrugPeriodeAreal;
      $forbrugPeriodeBeloebTotal += $forbrugPeriodeBeloeb;

      $this->writeRow([
        $matr,
        $this->formatDate($date),
        (int) $areal,
        (int) $beloeb,
        (int) $forbrugPeriodeAreal,
        (int) $forbrugPeriodeBeloeb,
        (int) $forbrugIAltAreal,
        (int) $forbrugIAltBeloeb,
      ]);
    }

    $this->writeRow([
      'Total', NULL,
      (int) $arealTotal,
      (int) $beloebTotal,
      (int) $forbrugPeriodeArealTotal,
      (int) $forbrugPeriodeBeloebTotal,
      (int) $forbrugIAltArealTotal,
      (int) $forbrugIAltBeloebTotal,
    ]);
  }

}
