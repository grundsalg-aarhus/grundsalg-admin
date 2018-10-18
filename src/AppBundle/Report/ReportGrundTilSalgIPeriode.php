<?php

namespace AppBundle\Report;

use AppBundle\DBAL\Types\GrundType;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Report.
 */
class ReportGrundTilSalgIPeriode extends Report {
  protected $title = 'Grund til salg i periode';

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

  protected function writeData() {
    $grundtype = $this->getParameterValue('grundtype');
    $startdate = $this->getParameterValue('startdate') ?? $this->getParameterValue('date') ;
    $enddate = $this->getParameterValue('enddate') ?? $startdate;
    if ($startdate == $enddate) {
      $title = sprintf('%s til salg pr. %s', $grundtype, $startdate->format('d/m/Y'));
    } else {
      $title = sprintf('%s til salg i perioden %s-%s', $grundtype, $startdate->format('d/m/Y'), $enddate->format('d/m/Y'));
    }

    $this->writeTitle($title, 8);
    $this->writeRow(['Priserne er ekskl. moms']);

      $sql = <<<SQL
SELECT
 grund.id, grund.SalgStatus, grund.beloebAnvist
FROM
 Grund AS grund
  INNER JOIN Lokalsamfund AS lokalsamfund ON grund.lokalSamfundId = lokalsamfund.id
WHERE
 grund.type = :grundtype
  AND grund.SalgStatus in ('Accepteret', 'Solgt')
  AND (
   :startdate <= grund.beloebAnvist and grund.beloebAnvist < :enddate
		or :startdate <= grund.accept and grund.accept < :enddate
  )
;
SQL;

    $stmt = $this->entityManager->getConnection()->prepare($sql);
    $stmt->bindValue(':grundtype', $grundtype);
    $stmt->bindValue(':startdate', $startdate, Type::DATE);
    $stmt->bindValue(':enddate', $enddate, Type::DATE);
    $stmt->execute();

    $this->writeRow([
      'Lokalsamfund',
      'LP-nr.',
      'Udb.',
      'Solgt',
      'Indtægt kr. i alt',
    ]);

    while ($row = $stmt->fetch()) {
      $this->writeRow($row);
    }
  }
}
