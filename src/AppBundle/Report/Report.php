<?php

namespace AppBundle\Report;

use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

abstract class Report {
  protected $title;
  protected $entityManager;
  protected $parameters;
  protected $writer;

  public function __construct(EntityManagerInterface $entityManager = NULL) {
    $this->entityManager = $entityManager;
    if (empty($this->title)) {
      throw new \Exception('Report title not defined.');
    }
  }

  public function getTitle() {
    return $this->title;
  }

  public function getParameters() {
    return [
      'startdate' => [
        'label' => 'Start date',
        'type' => DateType::class,
        'type_options' => [],
      ],
      'enddate' => [
        'label' => 'End date',
        'type' => DateType::class,
        'type_options' => [],
      ],
    ];
  }

  public function getParameterValue($name) {
    return isset($this->parameters[$name]) ? $this->parameters[$name] : NULL;
  }

  public function write(array $parameters = [], WriterInterface $writer) {
    $this->parameters = $parameters;
    $this->writer = $writer;
    $this->writeData();
  }

  protected abstract function writeData();

  protected $titleStyle;

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

  protected function writeFooter(array $data) {
    if ($this->footerStyle === NULL) {
      $this->footerStyle = (new StyleBuilder())
        ->setFontBold()
        ->setBackgroundColor(Color::rgb(0xDD, 0xDD, 0xDD))
        ->build();
    }

    $this->writer->addRowWithStyle($data, $this->footerStyle);
  }

  protected function writeRow(array $data) {
    $this->writer->addRow($data);
  }
}
