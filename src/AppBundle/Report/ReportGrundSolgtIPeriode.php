<?php

namespace AppBundle\Report;

use AppBundle\DBAL\Types\GrundType;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Report.
 */
class ReportGrundSolgtIPeriode extends Report {
  protected $title = 'Grund solgt i periode';

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
    $startdate = $this->getParameterValue('startdate');
    $enddate = $this->getParameterValue('enddate');
    $title = sprintf('%s solgt i perioden %s-%s', $grundtype, $startdate->format('d/m/Y'), $enddate->format('d/m/Y'));

    $this->writeTitle($title, 8);
    $this->writeRow(['Priserne er ekskl. moms']);
  }
}
