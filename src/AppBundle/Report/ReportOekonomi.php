<?php

namespace AppBundle\Report;

use AppBundle\DBAL\Types\GrundType;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Report.
 */
class ReportOekonomi extends Report {
  protected $title = 'Udtræk med økonomi';

  /**
   * {@inheritdoc}
   */
  public function getParameters() {
    return [
      'grundtype' => [
        'label' => 'Grundtype',
        'type' => ChoiceType::class,
        'type_options' => [
          'choices' => [
            GrundType::PARCELHUS => GrundType::PARCELHUS,
            GrundType::ERHVERV => GrundType::ERHVERV,
            GrundType::STORPARCEL => GrundType::STORPARCEL,
          ],
        ],
      ],
    ] + parent::getParameters();
  }

  /**
   *
   */
  protected function writeData() {
    // GrundSalgServlets/src/com/Symfoni/AArhus/GrundSalg/Servlets/Report.java:economy
    $grundtype = $this->getParameterValue('grundtype');
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');
    $title = 'Udbudte ' . $grundtype . ', BM Aktuelle i perioden ' . $startdate->format('d-m-Y') . '-' . $enddate->format('d-m-Y');
    $this->writeTitle($title, 8);

    $headline = 'I periode';
    if ($grundtype === GrundType::STORPARCEL) {
      $headline = 'Boliger a 80m2 i periode';
    }
    elseif ($grundtype === GrundType::ERHVERV) {
      $headline = 'm2 i periode';
    }

    $this->writeRow([
      'I alt',
      $enddate->format('d-m-Y'),
      $headline,
      $enddate->format('d-m-Y'),
    ]);

    $this->writeRow([
      'Lokalsamfund',
      'Udb.',
      'Solgt',
      'Indtægt kr.',
      'Udb.',
      'Solgt',
      'Indtægt kr.',
      'Tilb.',
      'Usolgt',
      'Salgsværdi usolgt kr.',
    ]);

    $sql = 'SELECT s.id, s.name FROM Grund as g ';
    $sql .= 'JOIN Lokalsamfund as s on s.id = g.lokalSamfundId ';
    $sql .= 'WHERE type = :grundtype and NOT (';
    $sql .= '(datoAnnonce is not null and datoAnnonce > :toDate) or ';
    $sql .= '(auktionStartDato is not null and auktionStartDato > :toDate) or ';
    $sql .= '(beloebAnvist is not null and beloebAnvist > :toDate) ';
    $sql .= ') ';
    $sql .= 'group by s.id,s.name order by s.name';

    $stmt = $this->entityManager->getConnection()->prepare($sql);
    $stmt->bindValue(':grundtype', $grundtype);
    $stmt->bindValue(':fromDate', $startdate, Type::DATE);
    $stmt->bindValue(':toDate', $enddate, Type::DATE);
    $stmt->execute();

    $totals = array_fill(2, 9, 0);

    while ($row = $stmt->fetch()) {
      $lines = [];
      $col1 = '';
      $col2 = 0;
      $col3 = 0;
      $col4 = 0;
      $col5 = 0;
      $col6 = 0;
      $col7 = 0;
      $col8 = 0;
      $col9 = 0;
      $col10 = 0;

      $sql = "SELECT CASE ";
      $sql .= " WHEN beloebAnvist IS NOT NULL and beloebAnvist <= :toDate THEN 'Solgt' ";
      $sql .= " WHEN Accept IS NOT NULL And Accept <= :toDate THEN 'Accepteret' ";
      $sql .= " WHEN TilbudStart IS NOT NULL And TilbudStart <= :toDate THEN 'Reserveret' ";
      $sql .= " WHEN ResStart IS NOT NULL And ResStart <= :toDate THEN 'Reserveret' ";
      $sql .= " WHEN auktionSlutDato IS NOT NULL And auktionSlutDato <= :toDate THEN 'Reserveret' ";
      $sql .= " ELSE 'Disponibel' ";
      $sql .= "END as gsalgsstatus, ";
      $sql .= "CASE WHEN datoAnnonce IS NOT NULL and datoAnnonce > :fromDate THEN 'x' ELSE '' END as inPeriod, ";
      $sql .= "CASE WHEN beloebAnvist IS NOT NULL and beloebAnvist > :fromDate THEN 'x' ELSE '' END as soldInPeriod, ";
      $sql .= "vej,maxEtageM2,areal,pris,antagetBud,minBud,datoAnnonce,salgsType ";
      $sql .= "FROM Grund as g ";
      $sql .= "JOIN Lokalsamfund as s on s.id=g.lokalSamfundId ";
      $sql .= "WHERE type= :grundtype and g.lokalSamfundId = :lokalSamfundId and NOT ( ";
      $sql .= "(datoAnnonce is not null and datoAnnonce > :toDate) or ";
      $sql .= "(auktionStartDato is not null and auktionStartDato > :toDate) or ";
      $sql .= "(beloebAnvist is not null and beloebAnvist > :toDate) ";
      $sql .= ") ";
      $sql .= "order by vej";

      $itemStmt = $this->entityManager->getConnection()->prepare($sql);
      $itemStmt->bindValue(':grundtype', $grundtype);
      $itemStmt->bindValue(':lokalSamfundId', $row['id']);
      $itemStmt->bindValue(':fromDate', $startdate, Type::DATE);
      $itemStmt->bindValue(':toDate', $enddate, Type::DATE);
      $itemStmt->execute();

      while ($item = $itemStmt->fetch()) {
        $vej = trim($item['vej']);
        $col1 = $row['name'];

        $m2Descr = 'kvadratmeterpris';
        if ($grundtype === GrundType::STORPARCEL) {
          $m2Descr = 'etgm2';
        }

        $pris = 0;
        if (strcasecmp($item['salgsType'], 'fastpris') === 0 || strcasecmp($item['salgsType'], $m2Descr) === 0) {
          $pris = $item['pris'];
        }
        else {
          if ($item['gsalgsstatus'] === 'Solgt') {
            $pris = $item['antagetBud'];
          }
          else {
            $pris = $item['minBud'];
          }
        }

        $cols = isset($lines[$vej]) ? $lines[$vej] : array_fill(0, 11, 0);

        if ($grundtype === GrundType::STORPARCEL) {
          $maxEtageM2DivBy80 = (int) ($item['maxEtageM2'] / 80);
          $col2 += $maxEtageM2DivBy80;
          $cols[2] += $maxEtageM2DivBy80;

          if ($item['gsalgsstatus'] === "Solgt") {
            $cols[3] += $maxEtageM2DivBy80;
            $cols[4] += $pris;

            $col3 += $maxEtageM2DivBy80;
            $col4 += $pris;

            if ($item['soldInPeriod'] === "x") {
              $col6 += $maxEtageM2DivBy80;
              $col7 += $pris;
              $cols[6] += $maxEtageM2DivBy80;
              $cols[7] += $pris;
            }

          }
          elseif ($item['gsalgsstatus'] === "Disponibel") {
            $col9 += $maxEtageM2DivBy80;
            $cols[9] += $maxEtageM2DivBy80;
            $col10 += $pris;
            $cols[10] += $pris;
          }
          elseif ($item['gsalgsstatus'] === "Accepteret" || $item['gsalgsstatus'] === "Reserveret") {
            $col8 += $maxEtageM2DivBy80;
            $cols[8] += $maxEtageM2DivBy80;
          }

          if ($item['inPeriod'] === "x") {
            $col5 += $maxEtageM2DivBy80;
            $cols[5] += $maxEtageM2DivBy80;
          }
        }
        elseif ($grundtype === GrundType::PARCELHUS) {
          $col2++;
          $cols[2]++;

          if ($item['gsalgsstatus'] === "Solgt") {
            $cols[3]++;
            $cols[4] += $pris;

            $col3++;
            $col4 += $pris;

            if ($item['soldInPeriod'] === "x") {
              $col6++;
              $col7 += $pris;
              $cols[6]++;
              $cols[7] += $pris;
            }

          }
          elseif ($item['gsalgsstatus'] === "Disponibel") {
            $col9++;
            $cols[9]++;
            $col10 += $pris;
            $cols[10] += $pris;
          }
          elseif ($item['gsalgsstatus'] === "Accepteret" || $item['gsalgsstatus'] === "Reserveret") {
            $col8++;
            $cols[8]++;
          }

          if ($item['inPeriod'] === "x") {
            $col5++;
            $cols[5]++;
          }
        }
        elseif ($grundtype === GrundType::ERHVERV) {
          $areal = (int) $item['areal'];
          $col2 += $areal;
          $cols[2] += $areal;

          if ($item['gsalgsstatus'] === "Solgt") {
            $cols[3] += $areal;
            $cols[4] += $pris;

            $col3 += $areal;
            $col4 += $pris;

            if ($item['soldInPeriod'] === "x") {
              $col6 += $areal;
              $col7 += $pris;
              $cols[6] += $areal;
              $cols[7] += $pris;
            }
          }
          elseif ($item['gsalgsstatus'] === "Disponibel") {
            $col9 += $areal;
            $cols[9] += $areal;
            $col10 += $pris;
            $cols[10] += $pris;
          }
          elseif ($item['gsalgsstatus'] === "Accepteret" || $item['gsalgsstatus'] === "Reserveret") {
            $col8 += $areal;
            $cols[8] += $areal;
          }

          if ($item['inPeriod'] === "x") {
            $col5 += $areal;
            $cols[5] += $areal;
          }
        }

        $lines[$vej] = $cols;
      }

      $totals[2] += $col2;
      $totals[3] += $col3;
      $totals[4] += $col4;
      $totals[5] += $col5;
      $totals[6] += $col6;
      $totals[7] += $col7;
      $totals[8] += $col8;
      $totals[9] += $col9;
      $totals[10] += $col10;

      $this->writeGroupHeader([$col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10]);

      foreach ($lines as $vej => $cols) {
        $this->writeRow([$vej, $cols[2], $cols[3], $cols[4], $cols[5], $cols[6], $cols[7], $cols[8], $cols[9], $cols[10]]);
      }
    }

    $this->writeFooter([
      'I alt',
      $totals[2],
      $totals[3],
      $totals[4],
      $totals[5],
      $totals[6],
      $totals[7],
      $totals[8],
      $totals[9],
      $totals[10],
    ]);
  }

}
