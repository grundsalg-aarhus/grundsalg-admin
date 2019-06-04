<?php

namespace AppBundle\Report;

use AppBundle\CalculationService\GrundCalculator;
use AppBundle\Entity\Grund;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Report.
 */
abstract class Report {
  protected $title;
  protected $entityManager;
  protected $grundCalculator;
  protected $parameters;
  protected $writer;

  /**
   * Constructor.
   */
  public function __construct(EntityManagerInterface $entityManager = NULL) {
    $this->entityManager = $entityManager;
    if (empty($this->title)) {
      throw new \Exception('Report title not defined.');
    }
  }

  public function setGrundCalculator(GrundCalculator $grundCalculator) {
    $this->grundCalculator = $grundCalculator;
  }

  /**
   * Get report title.
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * Get report parameters.
   */
  public function getParameters() {
    return [
      'startdate' => [
        'type' => DateType::class,
        'type_options' => [
          'data' => new \DateTime('first day of January'),
          'label' => 'Start date',
          'widget' => 'single_text',
        ],
      ],
      'enddate' => [
        'type' => DateType::class,
        'type_options' => [
          'data' => new \DateTime('last day of December'),
          'label' => 'End date',
          'widget' => 'single_text',
        ],
      ],
    ];
  }

  /**
   * Get value of a single parameter.
   */
  public function getParameterValue($name) {
    return isset($this->parameters[$name]) ? $this->parameters[$name] : NULL;
  }

  /**
   * Write report data to a writer.
   */
  public function write(array $parameters, WriterInterface $writer) {
    $this->parameters = $parameters;
    $this->writer = $writer;
    $this->writeData();
  }

  /**
   * Write the actual report data.
   */
  abstract protected function writeData();

  protected $titleStyle;

  /**
   * Write report title using title format.
   */
  protected function writeTitle($title, $colSpan = 1) {
    if ($this->titleStyle === NULL) {
      $this->titleStyle = (new StyleBuilder())
        ->setFontBold()
        ->setFontSize(24)
        ->build();
    }

    $this->writer->addRowWithStyle([$title], $this->titleStyle);
  }

  protected $headerStyle;

  /**
   * Write header using header format.
   */
  protected function writeHeader(array $data) {
    if ($this->headerStyle === NULL) {
      $this->headerStyle = (new StyleBuilder())
        ->setFontBold()
        ->setFontSize(18)
        ->build();
    }

    $this->writer->addRowWithStyle($data, $this->headerStyle);
  }

  protected $groupHeaderStyle;

  /**
   * Write group header using group header format.
   */
  protected function writeGroupHeader(array $data) {
    if ($this->groupHeaderStyle === NULL) {
      $this->groupHeaderStyle = (new StyleBuilder())
        ->setFontBold()
        ->setBackgroundColor(Color::rgb(0xDD, 0xDD, 0xDD))
        ->build();
    }

    $this->writer->addRowWithStyle($data, $this->groupHeaderStyle);
  }

  protected $footerStyle;

  /**
   *
   */
  protected function writeFooter(array $data) {
    if ($this->footerStyle === NULL) {
      $this->footerStyle = (new StyleBuilder())
        ->setFontBold()
        ->setBackgroundColor(Color::rgb(0xDD, 0xDD, 0xDD))
        ->build();
    }

    $this->writer->addRowWithStyle($data, $this->footerStyle);
  }

  /**
   *
   */
  protected function writeRow(array $data) {
    $this->writer->addRow($data);
  }

  /**
   * Format a date.
   */
  protected function formatDate(\DateTime $date) {
    return $date->format('d-m-Y');
  }

  /**
   * Format a decimal number.
   */
  protected function formatNumber($number, $decimals = 2) {
    return number_format($number, $decimals, ',', '');
  }

  /**
   * Format an amount.
   */
  protected function formatAmount($number, $decimals = 2) {
    return $this->formatNumber($number, $decimals);
  }

  protected function getGrundSalgstatuses(\DateTime $time = null, array $ids = null) {
    if (null === $time) {
      $time = new \DateTime();
    }
    $grunde = null === $ids
      ? $this->entityManager->getRepository(Grund::class)->findAll()
      : $this->entityManager->getRepository(Grund::class)->findBy(['id' => $ids]);

    $statusses = [];
    foreach ($grunde as $grund) {
      $statusses[$grund->getId()] = $this->grundCalculator->computeSalgstatusAt($grund, $time);
    }

    return $statusses;
  }

}
