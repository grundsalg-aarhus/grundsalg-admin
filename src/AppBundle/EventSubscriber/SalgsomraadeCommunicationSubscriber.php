<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Salgsomraade;
use AppBundle\Service\GrundsalgCommunicationService;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * Class GrundsalgWebCommunication
 *
 * Calls the public website to notify of updated salgsomraade
 *
 * @package AppBundle
 */
class SalgsomraadeCommunicationSubscriber implements EventSubscriber {

  private $service;

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
  public function __construct(GrundsalgCommunicationService $service) {
    $this->service = $service;
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

    $this->service->saveSalgsomraade($entity);
  }

}