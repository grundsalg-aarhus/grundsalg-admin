<?php

namespace AppBundle\Report;

use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\DBAL\Types\GrundType;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Report.
 */
class ReportTilSalgIPerioden extends Report {
  protected $title = 'Til salg i perioden';

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
            GrundType::ANDRE => GrundType::ANDRE,
          ],
        ],
      ],
    ] + parent::getParameters();
  }

  /**
   * {@inheritdoc}
   */
  protected function writeData() {
    // GrundSalgServlets/src/com/Symfoni/AArhus/GrundSalg/Servlets/Report.java:forsale.
    $grundtype = $this->getParameterValue('grundtype');
    switch ($grundtype) {
      case GrundType::PARCELHUS:
      case GrundType::ANDRE:
        return $this->writeDataParcelhusAndre();

      case GrundType::STORPARCEL:
        return $this->writeDataStorparcel();

      case GrundType::ERHVERV:
        return $this->writeDataErhverv();
    }
  }

  private function getGrundSalgstatusesAtEndOfPeriod(array $ids) {
    return $this->getGrundSalgstatuses($this->getParameterValue('enddate'), $ids);
  }

  /**
   * Write report data.
   */
  private function writeDataParcelhusAndre() {
    $grundtype = $this->getParameterValue('grundtype');
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');
    $title = $grundtype . ' til salg i perioden ' . $this->formatDate($startdate) . '–' . $this->formatDate($enddate);
    $this->writeTitle($title, 8);

    $this->writeHeader([
      'Lokalsamfund vej',
      'LP',
      'Salgsform 1)',
      'Aktuelle i alt 2).',
      'Solgt 3)',
      'Accept.',
      'Res. /Tilb 4)',
      'Disp',
    ]);


    $sql = <<<'SQL'
SELECT
 s.name,
 g.vej,
 IFNULL(lp.nr, '') AS nr,
 g.salgsType,
 '' status,
 g.id grund_id
FROM
 Grund AS g
  JOIN Lokalsamfund AS s ON s.id = g.lokalSamfundId
  JOIN Salgsomraade AS so ON g.salgsomraadeId = so.id
  JOIN Lokalplan AS lp ON lp.id = so.lokalPlanId
WHERE
 g.type = :grundtype
  AND NOT(
   (
    beloebAnvist IS NOT NULL AND beloebAnvist < :fromDate)
     OR (datoAnnonce1 IS NOT NULL AND datoAnnonce1 > :toDate)
     OR (datoAnnonce1 IS NULL AND (datoAnnonce IS NOT NULL AND datoAnnonce > :toDate))
     OR ((auktionStartDato IS NOT NULL AND auktionStartDato > :toDate) AND ( datoAnnonce1 IS NULL OR datoAnnonce1 > :toDate))
     OR (status = 'Fremtidig' AND g.annonceres = 1
   )
  )
ORDER BY
 s.name,
 g.vej,
 lp.nr,
 g.salgsType
SQL;

    $stmt = $this->entityManager->getConnection()->prepare($sql);
    $stmt->bindValue(':grundtype', $grundtype);
    $stmt->bindValue(':fromDate', $startdate, Type::DATE);
    $stmt->bindValue(':toDate', $enddate, Type::DATE);
    $stmt->execute();

    $totals = [
      'aktuelle' => 0,
      'solgt' => 0,
      'accept' => 0,
      'res' => 0,
      'disp' => 0,
    ];

    $rows = [];
    while ($row = $stmt->fetch()) {
      $rows[$row['grund_id']] = $row;
    }

    $salgstatuses = $this->getGrundSalgstatusesAtEndOfPeriod(array_keys($rows));

    // Collect all rows and group by
    //   1. Lokalsamfund.name.
    //   2. Grund.vej, Lokalplan.nr, Grund.salgsType (compounded)

    $groups = [];
    foreach ($rows as $grundId => $row) {
      $salgstatus = $salgstatuses[$grundId];
      $row['solgt'] = GrundSalgStatus::SOLGT === $salgstatus ? 1 : 0;
      $row['accept'] = GrundSalgStatus::ACCEPTERET === $salgstatus ? 1 : 0;
      $row['res'] = GrundSalgStatus::RESERVERET === $salgstatus ? 1 : 0;

      $key1 = $row['name'];
      $key2 = json_encode([$row['vej'], $row['nr'], $row['salgsType']]);
      $groups[$key1]['name'] = $row['name'];
      $groups[$key1]['children'][$key2]['vej'] = $row['vej'];
      $groups[$key1]['children'][$key2]['nr'] = $row['nr'];
      $groups[$key1]['children'][$key2]['salgsType'] = $row['salgsType'];
      $groups[$key1]['children'][$key2]['children'][] = $row;
    }

    // Accumulate values from children to parents.
    foreach ($groups as &$group) {
      $group += [
        'aktuelle' => 0,
        'solgt' => 0,
        'accept' => 0,
        'res' => 0,
        'disp' => 0,
      ];

      foreach ($group['children'] as $key => &$group1) {
        $group1 += [
          'aktuelle' => 0,
          'solgt' => 0,
          'accept' => 0,
          'res' => 0,
          'disp' => 0,
        ];

        foreach ($group1['children'] as $child) {
          $group1['aktuelle'] += 1;
          $group1['solgt'] += $child['solgt'];
          $group1['accept'] += $child['accept'];
          $group1['res'] += $child['res'];
        }
        $group1['disp'] = $group1['aktuelle'] - $group1['solgt'] - $group1['accept'] - $group1['res'];


        $group['aktuelle'] += $group1['aktuelle'];
        $group['solgt'] += $group1['solgt'];
        $group['accept'] += $group1['accept'];
        $group['res'] += $group1['res'];
        $group['disp'] += $group1['disp'];
      }

      $totals['aktuelle'] += $group['aktuelle'];
      $totals['solgt'] += $group['solgt'];
      $totals['accept'] += $group['accept'];
      $totals['res'] += $group['res'];
      $totals['disp'] += $group['disp'];
    }

    // Write groups.
    foreach ($groups as $row) {
      $this->writeGroupHeader([
        $row['name'], NULL, NULL,
        (int) $row['aktuelle'],
        (int) $row['solgt'],
        (int) $row['accept'],
        (int) $row['res'],
        (int) $row['disp'],
      ]);

      foreach ($row['children'] as $item) {
        $this->writeRow([
          $item['vej'],
          $item['nr'],
          $item['salgsType'],
          (int) $item['aktuelle'],
          (int) $item['solgt'],
          (int) $item['accept'],
          (int) $item['res'],
          (int) $item['disp'],
        ]);
      }
    }

    $this->writeFooter([
      'I alt', NULL, NULL,
      $totals['aktuelle'],
      $totals['solgt'],
      $totals['accept'],
      $totals['res'],
      $totals['disp'],
    ]);

    $this->writeRow(['']);

    $this->writeRow(
      ['1) Fastpris: Der er en fastpris for grundene(e). Auktion: Salg ved auktion.']
    );
    $this->writeRow(
      ['2) Udbudt før eller i perioden og usolgt ved periodens begyndelse.']
    );
    $this->writeRow(
      ['3) Solgt betyder, at skødet er underskrevet og at betaling er modtaget .']
    );
    $this->writeRow(
      ['4) Omfatter grunde der på udtræksdato er tilbudt eller reserveret samt evt. grunde der ikke er frit disponible pga. auktion.']
    );

  }

  /**
   * Write report data.
   */
  private function writeDataStorparcel() {
    $grundtype = $this->getParameterValue('grundtype');
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');
    $title = 'Storparceller til salg i perioden ' . $this->formatDate($startdate) . '–' . $this->formatDate($enddate);
    $this->writeTitle($title, 8);

    $this->writeHeader([
      'Lokalsamfund vej',
      'Salgsform 1)',
      'Solgt 2)',
      'Accept.',
      'Res. /Tilb 3)',
      'Disp',
    ]);
    $this->writeHeader([
      'LP-Omr.   Delareal   Matr.nr.',
    ]);

    $sql = <<<'SQL'
SELECT
 g.lokalSamfundId,s.name,sum(maxEtageM2) as total,
 SUM(CASE WHEN SalgStatus='Solgt' THEN maxEtageM2 ELSE 0 END) as solgt,
 SUM(CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN maxEtageM2 ELSE 0 END) as accept,
 SUM(CASE WHEN SalgStatus='Reserveret' THEN maxEtageM2 ELSE 0 END) as res
FROM
 Grund as g
  JOIN Lokalsamfund as s on s.id=g.lokalSamfundId
WHERE
 type= :grundtype
  AND NOT(
 (beloebAnvist IS NOT NULL AND beloebAnvist < :fromDate) or
 (datoAnnonce1 IS NOT NULL AND datoAnnonce1 > :toDate) or
 (datoAnnonce1 is null AND (datoAnnonce IS NOT NULL AND datoAnnonce > :toDate)) or
 (( auktionStartDato IS NOT NULL AND auktionStartDato > :toDate) AND ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or
 (status = 'Fremtidig' AND annonceres = 1)
 )
GROUP BY
 g.lokalSamfundId,s.name order by s.name
SQL;

    $stmt = $this->entityManager->getConnection()->prepare($sql);
    $stmt->bindValue(':grundtype', $grundtype);
    $stmt->bindValue(':fromDate', $startdate, Type::DATE);
    $stmt->bindValue(':toDate', $enddate, Type::DATE);
    $stmt->execute();

    $col3Total = 0;
    $col4Total = 0;
    $col5Total = 0;
    $col6Total = 0;

    while ($row = $stmt->fetch()) {
      $col1 = $row['name'];
      $col3 = $row['solgt'];
      $col4 = $row['accept'];
      $col5 = $row['res'];
      $col6 = $row['total'] - $col3 - $col4 - $col5;

      $col3Total += $col3;
      $col4Total += $col4;
      $col5Total += $col5;
      $col6Total += $col6;

      $strCol3 = '';
      if ($col3 != 0) {
        $strCol3 = $col3 . "/" . (int) round($col3 / 80.0);
      }

      $strCol4 = "";
      if ($col4 != 0) {
        $strCol4 = $col4 . '/' . (int) round($col4 / 80.0);
      }

      $strCol5 = "";
      if ($col5 != 0) {
        $strCol5 = $col5 . '/' . (int) round($col5 / 80.0);
      }

      $strCol6 = "";
      if ($col6 != 0) {
        $strCol6 = $col6 . '/' . (int) round($col6 / 80.0);
      }

      $this->writeGroupHeader([
        $col1,
        NULL,
        $strCol3,
        $strCol4,
        $strCol5,
        $strCol6,
      ]);

    $sql = <<<'SQL'
SELECT
 g.vej,IFNULL(d.o1,'') as o1,
 IFNULL(d.o2,'') as o2,
 IFNULL(d.o3,'') as o3,
 IFNULL(g.delAreal,'') as delareal,
 IFNULL(s.matrikkel1,'')as m1,
 IFNULL(s.matrikkel2,'') as m2,
 salgsType,
 maxEtageM2 as total,
 CASE WHEN SalgStatus='Solgt' THEN maxEtageM2 ELSE 0 END as solgt,
 CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN maxEtageM2 ELSE 0 END as accept,
 CASE WHEN SalgStatus='Reserveret' THEN maxEtageM2 ELSE 0 END as res
FROM
 Grund as g
  JOIN Salgsomraade as s on s.id=g.salgsomraadeId
  JOIN Delomraade as d on d.id=s.delomraadeId
WHERE
  g.type='Storparcel' AND g.lokalSamfundId= :lokalSamfundId AND NOT(
(beloebAnvist IS NOT NULL AND beloebAnvist < :fromDate) or
(datoAnnonce1 IS NOT NULL AND datoAnnonce1 > :toDate) or
(datoAnnonce1 is null AND (datoAnnonce IS NOT NULL AND datoAnnonce > :toDate)) or
(( auktionStartDato IS NOT NULL AND auktionStartDato > :toDate) AND ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or
(status = 'Fremtidig' AND g.annonceres = 1)
)
ORDER BY
 vej
SQL;

      $itemStmt = $this->entityManager->getConnection()->prepare($sql);
      $itemStmt->bindValue(':lokalSamfundId', $row['lokalSamfundId']);
      $itemStmt->bindValue(':fromDate', $startdate, Type::DATE);
      $itemStmt->bindValue(':toDate', $enddate, Type::DATE);
      $itemStmt->execute();

      while ($item = $itemStmt->fetch()) {
        $this->writeRow([$item['vej']]);

        $o1 = $item['o1'];
        $o2 = $item['o2'];
        $o3 = $item['o3'];
        $delAreal = $item['delareal'];
        $m1 = $item['m1'];
        $m2 = $item['m2'];

        $omr = '';

        if ($o1) {
          $omr = $o1 . '–';
        }

        if ($o2) {
          $omr .= $o2 . '.';
        }

        if ($o3) {
          $omr .= $o3 . '   ';
        }

        if ($delAreal) {
          $omr .= $delAreal . ' ';
        }

        if ($m1) {
          $omr .= $m1 . ' ';
        }

        if ($m2) {
          $omr .= $m2 . ' ';
        }

        $total = $item['total'];
        $solgt = $item['solgt'];
        $accept = $item['accept'];
        $res = $item['res'];
        $disp = $total - $solgt - $accept - $res;

        $strSolgt = '';
        if ($solgt != 0) {
          $strSolgt = $solgt . '/' . (int) round($solgt / 80.0);
        }

        $strAccept = '';
        if ($accept != 0) {
          $strAccept = $accept . '/' . (int) round($accept / 80.0);
        }

        $strRes = '';
        if ($res != 0) {
          $strRes = $res . '/' . (int) round($res / 80.0);
        }

        $strDisp = '';
        if ($disp != 0) {
          $strDisp = $disp . '/' . (int) round($disp / 80.0);
        }

        $this->writeRow([
          $omr,
          $item['salgsType'],
          $strSolgt,
          $strAccept,
          $strRes,
          $strDisp,
        ]);
      }
    }

    $this->writeFooter([
      'I alt',
      NULL,
      $col3Total . '/' . (int) round($col3Total / 80.0),
      $col4Total . '/' . (int) round($col4Total / 80.0),
      $col5Total . '/' . (int) round($col5Total / 80.0),
      $col6Total . '/' . (int) round($col6Total / 80.0),
    ]);

    $this->writeRow(['']);
    $this->writeRow(['']);
    $this->writeRow([
      'Maksimalt antal etm2/boliger á 80 m2.',
    ]);
    $this->writeRow([
      '1) Etgm2: Prisen er efter den tilladte byggeret. Auktion:Salg ved Auktion.',
    ]);
    $this->writeRow([
      '2) Solgt betyder, har at skøde er underskrevet og at udbetalingen er modtaget.',
    ]);
    $this->writeRow([
      '3) Omfatter storparceller der på udtræksdatoen er tilbudt eller reserveret samt evt. storparceller der ikke er frit disponible pga. auktion',
    ]);
  }

  /**
   * Write report data.
   */
  private function writeDataErhverv() {
    $grundtype = $this->getParameterValue('grundtype');
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');
    $title = 'Erhvervsgrunde til salg i perioden ' . $this->formatDate($startdate) . '–' . $this->formatDate($enddate);
    $this->writeTitle($title, 8);

    $this->writeHeader([
      'Lokalsamfund vej',
      'Kr./m2',
      'Solgt 1)',
      'Accept.',
    ]);

    $sql = <<<'SQL'
SELECT
 g.lokalSamfundId,
 s.name,
 count(g.vej) as totalCount,
 SUM(g.areal) as totalAreal,
 SUM(CASE WHEN SalgStatus='Solgt' THEN g.areal ELSE 0 END) as solgt,
 SUM(CASE WHEN SalgStatus='Solgt' THEN 1 ELSE 0 END) as solgtCount,
 SUM(CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN g.areal ELSE 0 END) as accept,
 SUM(CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN 1 ELSE 0 END) as acceptCount,
 SUM(CASE WHEN SalgStatus='Reserveret' THEN g.areal ELSE 0 END) as res,
 SUM(CASE WHEN SalgStatus='Reserveret' THEN 1 ELSE 0 END) as resCount
FROM
 Grund as g
  JOIN Lokalsamfund as s ON s.id = g.lokalSamfundId
WHERE
 type='Erhvervsgrund'
  AND NOT(
   (beloebAnvist IS NOT NULL AND beloebAnvist < :fromDate)
OR (datoAnnonce1 IS NOT NULL AND datoAnnonce1 > :toDate)
OR (datoAnnonce1 is null AND (datoAnnonce IS NOT NULL AND datoAnnonce > :toDate))
OR
 (( auktionStartDato IS NOT NULL AND auktionStartDato > :toDate) AND ( datoAnnonce1 is null Or datoAnnonce1 > :toDate))
 OR (status = 'Fremtidig' AND annonceres = 1)
 )
GROUP BY
 g.lokalSamfundId,s.name order by s.name
SQL;

    $stmt = $this->entityManager->getConnection()->prepare($sql);
    $stmt->bindValue(':fromDate', $startdate, Type::DATE);
    $stmt->bindValue(':toDate', $enddate, Type::DATE);
    $stmt->execute();

    $solgtTotal = 0;
    $acceptTotal = 0;
    $resTotal = 0;
    $dispTotal = 0;
    $solgtCountTotal = 0;
    $acceptCountTotal = 0;

    while ($row = $stmt->fetch()) {
      $totalAreal = $row['totalAreal'];
      $totalCount = $row['totalCount'];
      $solgt = $row['solgt'];
      $accept = $row['accept'];
      $res = $row['res'];
      $disp = $totalAreal - $solgt - $accept - $res;
      $solgtCount = $row['solgtCount'];
      $acceptCount = $row['acceptCount'];

      $solgtTotal += $solgt;
      $acceptTotal += $accept;
      $resTotal += $res;
      $dispTotal += $disp;

      $solgtCountTotal += $solgtCount;
      $acceptCountTotal += $acceptCount;

      $this->writeGroupHeader([
        $row['name'],
        NULL,
        ($solgtCount > 0 ? $solgtCount . '/' . intval($solgt) : ''),
        ($acceptCount > 0 ? $acceptCount . '/' . intval($accept) : ''),
      ]);

    $sql = <<<'SQL'
SELECT
 vej,
 g.areal,
 g.prism2,
 g.tilsluttet,
 CASE
  WHEN beloebAnvist IS NOT NULL AND beloebAnvist <= :toDate THEN 'Solgt'
  WHEN Accept IS NOT NULL AND Accept <= :toDate THEN 'Accepteret'
  WHEN TilbudStart IS NOT NULL AND TilbudStart <= :toDate THEN 'Reserveret'
  WHEN ResStart IS NOT NULL AND ResStart <= :toDate THEN 'Reserveret'
  WHEN auktionSlutDato IS NOT NULL AND auktionSlutDato <= :toDate THEN 'Reserveret'
 ELSE 'Disponibel'
 END as gsalgsstatus
FROM
 Grund as g
  JOIN Lokalsamfund as s on s.id=g.lokalSamfundId
WHERE
 type = 'Erhvervsgrund'
 AND g.lokalSamfundId = :lokalSamfundId
 AND NOT(
   (beloebAnvist IS NOT NULL AND beloebAnvist < :fromDate)
   OR (datoAnnonce1 IS NOT NULL AND datoAnnonce1 > :toDate)
   OR (datoAnnonce1 is null AND (datoAnnonce IS NOT NULL AND datoAnnonce > :toDate))
   OR (
    (auktionStartDato IS NOT NULL AND auktionStartDato > :toDate)
    AND (datoAnnonce1 is null Or datoAnnonce1 > :toDate)
   )
   OR (status = 'Fremtidig' AND annonceres = 1)
 )
ORDER BY
 g.vej
SQL;

      $itemStmt = $this->entityManager->getConnection()->prepare($sql);
      $itemStmt->bindValue(':lokalSamfundId', $row['lokalSamfundId']);
      $itemStmt->bindValue(':fromDate', $startdate, Type::DATE);
      $itemStmt->bindValue(':toDate', $enddate, Type::DATE);
      $itemStmt->execute();

      $lines = [];

      while ($item = $itemStmt->fetch()) {
        $vej = trim($item['vej']);
        $cols = isset($lines[$vej]) ? $lines[$vej] : array_merge(array_fill(0, 3, NULL), array_fill(0, 2, '0/0'));
        $cols[1] = $vej;

        $newM2Value = $item['prism2'];

        if ($cols[2] === NULL) {
          $cols[2] = $this->formatAmount($newM2Value);
        }
        else {
          $valMin = 0;
          $valMax = 0;

          if (strpos($cols[2], '–') !== FALSE) {
            $vals = explode('–', $cols[2]);

            $valMin = floatval($vals[0]);
            $valMax = floatval($vals[1]);

            if ($newM2Value > $valMax) {
              $valMax = $newM2Value;
            }
            elseif ($newM2Value < $valMin) {
              $valMin = $newM2Value;
            }

            $cols[2] = $this->formatAmount($valMin) . '–' . $this->formatAmount($valMax);
          }
          else {
            $val = floatval($cols[2]);
            if ($val != $newM2Value) {
              if ($newM2Value > $val) {
                $valMax = $newM2Value;
                $valMin = $val;
              }
              elseif ($newM2Value < $val) {
                $valMin = $newM2Value;
                $valMax = $val;
              }

              $cols[2] = $this->formatAmount($valMin) . '–' . $this->formatAmount($valMax);
            }
          }
        }

        if ($item['gsalgsstatus'] === 'Solgt') {
          $tmp = explode('/', $cols[3]);
          $tmp[0] = intval($tmp[0]) + 1;
          $tmp[1] = intval($tmp[1]) + $item['areal'];
          $cols[3] = $tmp[0] . '/' . $tmp[1];
        }

        if ($item['gsalgsstatus'] === 'Accepteret') {
          $tmp = explode('/', $cols[4]);
          $tmp[0] = intval($tmp[0]) + 1;
          $tmp[1] = intval($tmp[1]) + $item['areal'];
          $cols[4] = $tmp[0] . '/' . $tmp[1];
        }

        $lines[$vej] = $cols;
      }

      foreach ($lines as $vej => $cols) {
        $this->writeRow([
          $cols[1],
          $cols[2],
          $cols[3] !== '0/0' ? $cols[3] : '',
          $cols[4] !== '0/0' ? $cols[4] : '',
        ]);
      }
    }

    $this->writeFooter([
      'I alt',
      NULL,
      ($solgtCountTotal > 0 ? $solgtCountTotal . '/' . $solgtTotal : ''),
      ($acceptCountTotal > 0 ? $acceptCountTotal . '/' . $acceptTotal : ''),
    ]);

  }

}
