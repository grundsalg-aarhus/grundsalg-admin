<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */
namespace AppBundle\Service;

use AppBundle\Entity\Salgsomraade;
use GuzzleHttp\Client;

/**
 * Class GrundsalgCommunicationService
 *
 * @package AppBundle
 */
class GrundsalgCommunicationService {
  private $endpoint;
  private $username;
  private $password;

  /**
   * Constructor
   */
  public function __construct($endpoint, $username, $password) {
    $this->endpoint = $endpoint;
    $this->username = $username;
    $this->password = $password;
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
  public function saveSalgsomraade(Salgsomraade $salgsomraade) {
    if($salgsomraade->isAnnonceres() && $this->endpoint) {
      $client = new Client();

      if($this->username && $this->password) {
        $response = $client->request('POST', $this->endpoint . "/api/udstykning/" . $salgsomraade->getId() . "/updated", ['auth' => [$this->username, $this->password]]);
      } else {
        $response = $client->request('POST', $this->endpoint . "/api/udstykning/" . $salgsomraade->getId() . "/updated");
      }

      $body = $response->getBody()->getContents();
      $content = \GuzzleHttp\json_decode($body);

      return (array) $content;
    } else {
      return FALSE;
    }
  }
}