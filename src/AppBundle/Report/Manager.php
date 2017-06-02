<?php

namespace AppBundle\Report;

/**
 * Report manager.
 */
class Manager {
  /**
   * The configuration.
   *
   * @var array
   */
  protected $configuration;

  /**
   * Constructor.
   */
  public function __construct(array $configuration) {
    $this->configuration = $configuration;
  }

  /**
   * Get all enabled reports.
   */
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
