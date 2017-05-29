<?php

namespace AppBundle\Report;

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

  /**
   * Write report data.
   */
  private function writeDataParcelhusAndre() {
    $grundtype = $this->getParameterValue('grundtype');
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');
    $title = $grundtype . ' til salg i perioden ' . $startdate->format('d-m-Y') . '-' . $enddate->format('d-m-Y');
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

    $sql = "SELECT g.lokalSamfundId, s.name, count(g.vej) as aktuelle, SUM(CASE WHEN SalgStatus='Solgt' THEN 1  ELSE 0 END) as solgt, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN 1  ELSE 0 END) as accept, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Reserveret' THEN 1  ELSE 0 END) as res ";
    $sql .= "FROM Grund as g ";
    $sql .= "JOIN Lokalsamfund as s on s.id=g.lokalSamfundId ";
    $sql .= "WHERE g.type= :grundtype and not ( ";
    $sql .= "(beloebAnvist is not null and beloebAnvist < :fromDate) or ";
    $sql .= "(datoAnnonce1 is not null And datoAnnonce1 > :toDate) or ";
    $sql .= "(datoAnnonce1 is null And (datoAnnonce is not null And datoAnnonce > :toDate)) or ";
    $sql .= "(( auktionStartDato is not null And auktionStartDato > :toDate) And ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or ";
    $sql .= "(status = 'Fremtidig' And g.annonceres = 1)";
    $sql .= ") ";
    $sql .= "group by g.lokalSamfundId,s.name order by s.name ";

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

    while ($row = $stmt->fetch()) {
      $row['disp'] = $row['aktuelle'] - $row['solgt'] - $row['accept'] - $row['res'];
      foreach ($totals as $col => &$value) {
        $value += $row[$col];
      }

      $this->writeGroupHeader([
        $row['name'], NULL, NULL,
        (int) $row['aktuelle'],
        (int) $row['solgt'],
        (int) $row['accept'],
        (int) $row['res'],
        (int) $row['disp'],
      ]);

      $sql = "SELECT g.vej,IFNULL(lp.nr,'') as nr,g.salgsType, COUNT(g.vej) as aktuelle,sum(CASE WHEN g.salgStatus='Solgt' THEN 1 else 0 end) as solgt ";
      $sql .= ",sum(CASE WHEN g.salgStatus='Skøde rekvireret' OR g.salgStatus='Accepteret' THEN 1 else 0 end) as accept ";
      $sql .= ",sum(CASE WHEN g.salgStatus='Reserveret' THEN 1 else 0 end) as res ";
      $sql .= "FROM Grund as g ";
      $sql .= "JOIN Salgsomraade as so on g.salgsomraadeId = so.id ";
      $sql .= "JOIN Lokalplan as lp on lp.id = so.lokalPlanId ";
      $sql .= "WHERE g.type=:grundtype and g.lokalSamfundId=:lokalSamfundId and not ( ";
      $sql .= "(beloebAnvist is not null and beloebAnvist < :fromDate) or ";
      $sql .= "(datoAnnonce1 is not null And datoAnnonce1 > :toDate) or ";
      $sql .= "(datoAnnonce1 is null And (datoAnnonce is not null And datoAnnonce > :toDate)) or ";
      $sql .= "(( auktionStartDato is not null And auktionStartDato > :toDate) And ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or ";
      $sql .= "(status = 'Fremtidig' And g.annonceres = 1) ";
      $sql .= ") ";
      $sql .= "group by g.vej,lp.nr,g.salgsType";

      $itemStmt = $this->entityManager->getConnection()->prepare($sql);
      $itemStmt->bindValue(':grundtype', $grundtype);
      $itemStmt->bindValue(':lokalSamfundId', $row['lokalSamfundId']);
      $itemStmt->bindValue(':fromDate', $startdate, Type::DATE);
      $itemStmt->bindValue(':toDate', $enddate, Type::DATE);
      $itemStmt->execute();

      while ($item = $itemStmt->fetch()) {
        $item['disp'] = $item['aktuelle'] - $item['solgt'] - $item['accept'] - $item['res'];
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
  }

  /**
   * Write report data.
   */
  private function writeDataStorparcel() {
    $grundtype = $this->getParameterValue('grundtype');
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');
    $title = 'Storparceller til salg i perioden ' . $startdate->format('d-m-Y') . '-' . $enddate->format('d-m-Y');
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

    $sql = "SELECT g.lokalSamfundId,s.name,sum(maxEtageM2) as total, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Solgt' THEN maxEtageM2  ELSE 0 END) as solgt, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN maxEtageM2  ELSE 0 END) as accept, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Reserveret' THEN maxEtageM2 ELSE 0 END) as res ";
    $sql .= "FROM Grund as g ";
    $sql .= "JOIN Lokalsamfund as s on s.id=g.lokalSamfundId ";
    $sql .= "WHERE type= :grundtype and not ( ";
    $sql .= "(beloebAnvist is not null and beloebAnvist < :fromDate) or ";
    $sql .= "(datoAnnonce1 is not null And datoAnnonce1 > :toDate) or ";
    $sql .= "(datoAnnonce1 is null And (datoAnnonce is not null And datoAnnonce > :toDate)) or ";
    $sql .= "(( auktionStartDato is not null And auktionStartDato > :toDate) And ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or ";
    $sql .= "(status = 'Fremtidig' And annonceres = 1)";
    $sql .= ") ";
    $sql .= "group by g.lokalSamfundId,s.name order by s.name ";

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

      $sql = "SELECT g.vej,IFNULL(d.o1,'') as o1,IFNULL(d.o2,'') as o2,IFNULL(d.o3,'') as o3,IFNULL(g.delAreal,'') as delareal,IFNULL(s.matrikkel1,'')as m1,IFNULL(s.matrikkel2,'') as m2,salgsType,maxEtageM2 as total,";
      $sql .= "CASE WHEN SalgStatus='Solgt' THEN maxEtageM2  ELSE 0 END as solgt,";
      $sql .= "CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN maxEtageM2  ELSE 0 END as accept,";
      $sql .= "CASE WHEN SalgStatus='Reserveret' THEN maxEtageM2 ELSE 0 END as res ";
      $sql .= "FROM Grund as g ";
      $sql .= "JOIN Salgsomraade as s on s.id=g.salgsomraadeId ";
      $sql .= "JOIN Delomraade as d on d.id=s.delomraadeId ";
      $sql .= "WHERE g.type='Storparcel' and g.lokalSamfundId= :lokalSamfundId and not (";
      $sql .= "(beloebAnvist is not null and beloebAnvist < :fromDate) or ";
      $sql .= "(datoAnnonce1 is not null And datoAnnonce1 > :toDate) or ";
      $sql .= "(datoAnnonce1 is null And (datoAnnonce is not null And datoAnnonce > :toDate)) or ";
      $sql .= "(( auktionStartDato is not null And auktionStartDato > :toDate) And ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or ";
      $sql .= "(status = 'Fremtidig' And g.annonceres = 1)";
      $sql .= ") ";
      $sql .= " order by vej";

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
          $omr = $o1 . '-';
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

    $this->writeRow([]);
    $this->writeRow([]);
    $this->writeRow([
      'Maksimalt antal etm2/boliger á 80 m2.',
      '1) Etgm2: Prisen er efter den tilladte byggeret. Auktion:Salg ved Auktion.',
      '2) Solgt betyder, har at skøde er underskrevet og at udbetalingen er modtaget.',
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
    $title = 'Erhvervsgrunde til salg i perioden ' . $startdate->format('d-m-Y') . '-' . $enddate->format('d-m-Y');
    $this->writeTitle($title, 8);

    $this->writeHeader([
      'Lokalsamfund vej',
      'Kr./m2',
      'Kloak',
      'El',
      'Vand',
      'Fjv',
      'Solgt 1)',
      'Accept.',
      'Res. /Tilb 2)',
      'Disp',
    ]);

    $sql = "SELECT g.lokalSamfundId,s.name, count(g.vej) as totalCount,SUM(g.areal) as totalAreal, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Solgt' THEN g.areal  ELSE 0 END) as solgt, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Solgt' THEN 1  ELSE 0 END) as solgtCount,  ";
    $sql .= "SUM(CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN g.areal  ELSE 0 END) as accept,  ";
    $sql .= "SUM(CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN 1  ELSE 0 END) as acceptCount, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Reserveret' THEN g.areal  ELSE 0 END) as res, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Reserveret' THEN 1  ELSE 0 END) as resCount  ";
    $sql .= "FROM Grund as g  ";
    $sql .= "JOIN Lokalsamfund as s on s.id=g.lokalSamfundId  ";
    $sql .= "WHERE type='Erhvervsgrund'and not ( ";
    $sql .= "(beloebAnvist is not null and beloebAnvist < :fromDate) or  ";
    $sql .= "(datoAnnonce1 is not null And datoAnnonce1 > :toDate) or  ";
    $sql .= "(datoAnnonce1 is null And (datoAnnonce is not null And datoAnnonce > :toDate)) or  ";
    $sql .= "(( auktionStartDato is not null And auktionStartDato > :toDate) And ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or  ";
    $sql .= "(status = 'Fremtidig' And annonceres = 1) ";
    $sql .= ") ";
    $sql .= "group by g.lokalSamfundId,s.name order by s.name";

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
    $resCountTotal = 0;
    $dispCountTotal = 0;

    while ($row = $stmt->fetch()) {
      $totalAreal = $row['totalAreal'];
      $totalCount = $row['totalCount'];
      $solgt = $row['solgt'];
      $accept = $row['accept'];
      $res = $row['res'];
      $disp = $totalAreal - $solgt - $accept - $res;
      $solgtCount = $row['solgtCount'];
      $acceptCount = $row['acceptCount'];
      $resCount = $row['resCount'];
      $dispCount = $totalCount - $solgtCount - $acceptCount - $resCount;

      $solgtTotal += $solgt;
      $acceptTotal += $accept;
      $resTotal += $res;
      $dispTotal += $disp;

      $solgtCountTotal += $solgtCount;
      $acceptCountTotal += $acceptCount;
      $resCountTotal += $resCount;
      $dispCountTotal += $dispCount;

      $this->writeGroupHeader([
        $row['name'],
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        ($solgtCount > 0 ? $solgtCount . '/' . $solgt : ''),
        ($acceptCount > 0 ? $acceptCount . '/' . $accept : ''),
        ($resCount > 0 ? $resCount . '/' . $res : ''),
        ($dispCount > 0 ? $dispCount . '/' . $disp : ''),
      ]);

      $sql = "SELECT vej,g.areal,g.prism2,g.tilsluttet, ";
      $sql .= "CASE WHEN g.tilsluttet like '%Kloak%' THEN 1 ELSE 0 END as kloak, ";
      $sql .= "CASE WHEN g.tilsluttet like '%El%' THEN 1 ELSE 0 END as el, ";
      $sql .= "CASE WHEN g.tilsluttet like '%Vand%' THEN 1 ELSE 0 END as vand, ";
      $sql .= "CASE WHEN g.tilsluttet like '%Fjernvarme%' THEN 1 ELSE 0 END as fv, ";
      $sql .= "CASE ";
      $sql .= "WHEN beloebAnvist IS NOT NULL and beloebAnvist <= :toDate THEN 'Solgt'  ";
      $sql .= "WHEN Accept IS NOT NULL And Accept <= :toDate THEN 'Accepteret'  ";
      $sql .= "WHEN TilbudStart IS NOT NULL And TilbudStart <= :toDate THEN 'Reserveret'  ";
      $sql .= "WHEN ResStart IS NOT NULL And ResStart <= :toDate THEN 'Reserveret'  ";
      $sql .= "WHEN auktionSlutDato IS NOT NULL And auktionSlutDato <= :toDate THEN 'Reserveret' ";
      $sql .= "ELSE 'Disponibel' END as gsalgsstatus ";
      $sql .= "FROM Grund as g  ";
      $sql .= "JOIN Lokalsamfund as s on s.id=g.lokalSamfundId  ";
      $sql .= "WHERE type='Erhvervsgrund' and g.lokalSamfundId= :lokalSamfundId and not ( ";
      $sql .= "(beloebAnvist is not null and beloebAnvist < :fromDate) or  ";
      $sql .= "(datoAnnonce1 is not null And datoAnnonce1 > :toDate) or  ";
      $sql .= "(datoAnnonce1 is null And (datoAnnonce is not null And datoAnnonce > :toDate)) or  ";
      $sql .= "(( auktionStartDato is not null And auktionStartDato > :toDate) And ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or ";
      $sql .= "(status = 'Fremtidig' And annonceres = 1) ";
      $sql .= ") ";
      $sql .= "order by g.vej";

      $itemStmt = $this->entityManager->getConnection()->prepare($sql);
      $itemStmt->bindValue(':lokalSamfundId', $row['lokalSamfundId']);
      $itemStmt->bindValue(':fromDate', $startdate, Type::DATE);
      $itemStmt->bindValue(':toDate', $enddate, Type::DATE);
      $itemStmt->execute();

      $lines = [];

      while ($item = $itemStmt->fetch()) {
        $vej = trim($item['vej']);

        $cols = isset($lines[$vej]) ? $lines[$vej] : (array_fill(0, 3, NULL) + array_fill(3, 4, '-') + array_fill(7, 4, '0/0'));
        $cols[1] = $vej;

        $newM2Value = $item['prism2'];

        if ($cols[2] === NULL) {
          $cols[2] = $newM2Value;
        }
        else {
          $valMin = 0;
          $valMax = 0;

          if (strpos($cols[2], '-') !== FALSE) {
            $vals = explode('-', $cols[2]);

            $valMin = floatval($vals[0]);
            $valMax = floatval($vals[1]);

            if ($newM2Value > $valMax) {
              $valMax = $newM2Value;
            }
            elseif ($newM2Value < $valMin) {
              $valMin = $newM2Value;
            }

            $cols[2] = number_format($valMin, 3) . '-' . number_format($valMax, 3);
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

              $cols[2] = number_format($valMin, 3) . '-' . number_format($valMax, 3);
            }
          }
        }

        if ($item['kloak'] == 1) {
          $cols[3] = '+';
        }

        if ($item['el'] == 1) {
          $cols[4] = '+';
        }

        if ($item['vand'] == 1) {
          $cols[5] = '+';
        }

        if ($item['fv'] == 1) {
          $cols[6] = '+';
        }

        if ($item['gsalgsstatus'] === 'Solgt') {
          $tmp = explode('/', $cols[7]);
          $tmp[0] = intval($tmp[0]) + 1;
          $tmp[1] = floatval($tmp[1]) + $item['areal'];
          $cols[7] = $tmp[0] . '/' . $tmp[1];
        }

        if ($item['gsalgsstatus'] === 'Accepteret') {
          $tmp = explode('/', $cols[8]);
          $tmp[0] = intval($tmp[0]) + 1;
          $tmp[1] = floatval($tmp[1]) + $item['areal'];
          $cols[8] = $tmp[0] . '/' . $tmp[1];
        }

        if ($item['gsalgsstatus'] === 'Reserveret') {
          $tmp = explode('/', $cols[9]);
          $tmp[0] = intval($tmp[0]) + 1;
          $tmp[1] = floatval($tmp[1]) + $item['areal'];
          $cols[9] = $tmp[0] . '/' . $tmp[1];
        }

        if ($item['gsalgsstatus'] === 'Disponibel') {
          $tmp = explode('/', $cols[10]);
          $tmp[0] = intval($tmp[0]) + 1;
          $tmp[1] = floatval($tmp[1]) + $item['areal'];
          $cols[10] = $tmp[0] . '/' . $tmp[1];
        }

        $lines[$vej] = $cols;
      }

      foreach ($lines as $vej => $cols) {
        $this->writeRow([
          $cols[1],
          $this->formatNumber($cols[2]),
          $cols[3],
          $cols[4],
          $cols[5],
          $cols[6],
          $cols[7] !== '0/0' ? $cols[7] : '',
          $cols[8] !== '0/0' ? $cols[8] : '',
          $cols[9] !== '0/0' ? $cols[9] : '',
          $cols[10] !== '0/0' ? $cols[10] : '',
        ]);
      }
    }

    $this->writeFooter([
      'I alt',
      NULL,
      NULL,
      NULL,
      NULL,
      NULL,
      ($solgtCountTotal > 0 ? $solgtCountTotal . '/' . $solgtTotal : ''),
      ($acceptCountTotal > 0 ? $acceptCountTotal . '/' . $acceptTotal : ''),
      ($resCountTotal > 0 ? $resCountTotal . '/' . $resTotal : ''),
      ($dispCountTotal > 0 ? $dispCountTotal . '/' . $dispTotal : ''),
    ]);

    $this->writeRow([
      NULL,
      NULL,
      NULL,
      '1) Solgt: Betyder at skødet er underskrevet og at udbetalingen er modtaget.',
      '2) Omfatter grunde der på udtræksdato er tilbudt ellerreserveret samt evt. grunde, der ikke er frit disponible pga. auktion.',
    ]);
  }

}
