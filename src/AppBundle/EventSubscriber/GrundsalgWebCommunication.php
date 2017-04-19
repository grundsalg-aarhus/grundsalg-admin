<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Salgsomraade;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use GuzzleHttp\Client;

/**
 * Class GrundsalgWebCommunication
 *
 * Calls the public website to notify of updated salgsomraade
 *
 * @package AppBundle
 */
class GrundsalgWebCommunication implements EventSubscriber {

  private $endpoint;

  /**
   * {@inheritdoc}
   */
  public function getSubscribedEvents() {
    return [
      'postPersist',
      'postUpdate',
    ];
  }

  /**
   * Set the endpoint url defined in parameters.yml
   */
  public function __construct($endpoint) {
    $this->endpoint = $endpoint;
  }

  public function postPersist(LifecycleEventArgs $args) {
    $this->handleEvent($args);
  }

  public function postUpdate(LifecycleEventArgs $args) {
    $this->handleEvent($args);
  }

  private function handleEvent(LifecycleEventArgs $args) {
    $entity = $args->getEntity();

    // only act on some "Salgsomraade" entity
    if (!$entity instanceof Salgsomraade) {
      return;
    }

    $this->saveSalgsomraade($entity);
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
  private function saveSalgsomraade($salgsomraade) {
    $client = new Client();

    $response = $client->request('POST', $this->endpoint . "/api/udstykning/" . $salgsomraade->getId() . "/updated");

    $body = $response->getBody()->getContents();
    $content = \GuzzleHttp\json_decode($body);

    return (array) $content;
  }
}