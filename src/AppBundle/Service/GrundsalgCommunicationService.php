<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */
namespace AppBundle\Service;

use GuzzleHttp\Client;

/**
 * Class GrundsalgCommunicationService
 *
 * @package AppBundle
 */
class GrundsalgCommunicationService {
  private $endpoint;

  /**
   * Constructor
   */
  public function __construct($endpoint) {
    $this->endpoint = $endpoint;
  }

  /**
   * Handle save of salgsomraade.
   *
   * @param $salgsomraade
   */
  public function saveSalgsomraade($salgsomraade) {
    $client = new Client();

    $client->request('POST', $this->endpoint . "/api/udstykning/" . $salgsomraade->getId() . "/updated");
  }
}