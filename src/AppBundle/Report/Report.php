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
abstract class Report {
  protected $title;
  protected $entityManager;
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
          'data' => new \DateTime('2000-01-01'),
          'label' => 'Start date',
          'widget' => 'single_text',
        ],
      ],
      'enddate' => [
        'type' => DateType::class,
        'type_options' => [
          'data' => new \DateTime('2100-01-01'),
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

}
