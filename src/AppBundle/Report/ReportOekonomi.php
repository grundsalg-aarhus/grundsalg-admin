<?php

namespace AppBundle\Report;

use AppBundle\DBAL\Types\GrundType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReportOekonomi extends Report {
  protected $title = 'Økonomi';

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
            ]
          ],
        ],
      ] + parent::getParameters();
  }

  protected function writeData() {
  }
}
