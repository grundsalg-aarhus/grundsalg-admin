<?php

namespace AppBundle\Report;

use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Report.
 */
class ReportGrundTilSalgPrDato extends ReportGrundTilSalgIPeriode {
  protected $title = 'Grund til salg pr. dato';

  public function getParameters() {
    $parameters = parent::getParameters();
    unset($parameters['startdate'], $parameters['enddate']);
    $parameters['date'] = [
      'type' => DateType::class,
      'type_options' => [
        'data' => new \DateTime('first day of this month'),
        'label' => 'Date',
        'widget' => 'single_text',
      ],
    ];

    return $parameters;
  }
}
