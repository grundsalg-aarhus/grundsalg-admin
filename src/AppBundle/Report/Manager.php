<?php

namespace AppBundle\Report;

class Manager {
  /**
   * @var array
   */
  protected $configuration;

  public function __construct(array $configuration) {
    $this->configuration = $configuration;
  }

  public function getReports() {
    $reports = [];

    if (isset($this->configuration['reports'])) {
      foreach ($this->configuration['reports'] as $report) {
        if (isset($report['class'])) {
          $class = $report['class'];
          if (is_subclass_of($class, Report::class)) {
            $reports[$class] = new $class();
          }
        }
      }
    }

    return $reports;
  }
}
