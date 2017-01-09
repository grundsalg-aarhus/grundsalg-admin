<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService.
 */
namespace AppBundle\Service;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class GrundsalgCommunicationService
 *
 * @package AppBundle
 */
class GrundsalgCommunicationService {
  protected $container;

  /**
   * Constructor
   */
  public function __construct(Container $container) {
    $this->container = $container;
  }

  /**
   * Handle save of salgsomraade.
   *
   * @param $salgsomraade
   */
  public function saveSalgsomraade($salgsomraade) {
    $client = 1;
  }
}