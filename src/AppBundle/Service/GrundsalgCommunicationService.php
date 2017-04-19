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
   *
   * @return array
   *   The content received as json from the remote system.
   *
   * @throws \Exception
   *   If error message is return from the remote system.
   */
  public function saveSalgsomraade($salgsomraade) {
    $client = new Client();

    $response = $client->request('POST', $this->endpoint . "/api/udstykning/" . $salgsomraade->getId() . "/updated");

    $body = $response->getBody()->getContents();
    $content = \GuzzleHttp\json_decode($body);

    return (array)$content;
  }
}