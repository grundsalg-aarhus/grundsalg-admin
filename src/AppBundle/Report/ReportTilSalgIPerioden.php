<?php

namespace AppBundle\Report;

use AppBundle\DBAL\Types\GrundType;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReportTilSalgIPerioden extends Report {
  protected $title = 'Til salg i perioden';

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
          ]
        ],
      ],
    ] + parent::getParameters();
  }

  protected function writeData() {
    // GrundSalgServlets/src/com/Symfoni/AArhus/GrundSalg/Servlets/Report.java:forsale
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

  private function writeDataParcelhusAndre() {
    $grundtype = $this->getParameterValue('grundtype');
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');
    $title = $grundtype . ' til salg i perioden ' . $startdate->format('d-m-Y') . '-' . $enddate->format('d-m-Y');
    $this->writeTitle($title, 8);

    $this->writeHeader(['Lokalsamfund vej',
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
    $stmt->bindValue(':grundtype' , $grundtype);
    $stmt->bindValue(':fromDate' , $startdate, Type::DATE);
    $stmt->bindValue(':toDate' , $enddate, Type::DATE);
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
        (int)$row['aktuelle'],
        (int)$row['solgt'],
        (int)$row['accept'],
        (int)$row['res'],
        (int)$row['disp'],
      ]);


      $sql = "SELECT g.vej,IFNULL(lp.nr,'') as nr,g.salgsType, COUNT(g.vej) as aktuelle,sum(CASE WHEN g.salgStatus='Solgt' THEN 1 else 0 end) as solgt ";
      $sql .= ",sum(CASE WHEN g.salgStatus='Skøde rekvireret' OR g.salgStatus='Accepteret' THEN 1 else 0 end) as accept ";
      $sql .= ",sum(CASE WHEN g.salgStatus='Reserveret' THEN 1 else 0 end) as res ";
      $sql .= "FROM Grund as g ";
      // $sql .= "FULL JOIN Salgsomraade as so on g.salgsomraadeId = so.id ";
      // $sql .= "FULL JOIN Lokalplan as lp on lp.id = so.lokalPlanId ";
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
      $itemStmt->bindValue(':grundtype' , $grundtype);
      $itemStmt->bindValue(':lokalSamfundId' , $row['lokalSamfundId']);
      $itemStmt->bindValue(':fromDate' , $startdate, Type::DATE);
      $itemStmt->bindValue(':toDate' , $enddate, Type::DATE);
      $itemStmt->execute();

      while ($item = $itemStmt->fetch()) {
        $item['disp'] = $item['aktuelle'] - $item['solgt'] - $item['accept'] - $item['res'];
        $this->writeRow([
          $item['vej'],
          $item['nr'],
          $item['salgsType'],
          (int)$item['aktuelle'],
					(int)$item['solgt'],
					(int)$item['accept'],
					(int)$item['res'],
					(int)$item['disp'],
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
}
