<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Grund;
use AppBundle\Entity\Salgsomraade;
use AppBundle\Service\WebsiteCommunicationService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

/**
 * Class EntityChangeSubscriber
 *
 * Calls the public website to notify of updated salgsomraade
 *
 * @package AppBundle
 */
class EntityChangeSubscriber implements EventSubscriber {

  /**
   * @var array Storage for inserted/updated/deleted Salgsomraade entities.
   *
   * Caveat: https://stackoverflow.com/a/43604820
   */
  private $updatedSalgsomraade = [];

  private $service;

  /**
   * Set the endpoint url defined in parameters.yml
   */
  public function __construct(WebsiteCommunicationService $service) {
    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubscribedEvents() {
    return [
      Events::onFlush,
      Events::postFlush,
    ];
  }

  /**
   * Store a list of inserted/updated/deleted Salgsomraade entities.
   *
   * @param \Doctrine\ORM\Event\OnFlushEventArgs $args
   */
  public function onFlush(OnFlushEventArgs $args) {
    $uow = $args->getEntityManager()->getUnitOfWork();

    $changed = array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates(), $uow->getScheduledEntityDeletions());

    foreach ($changed as $entity) {
      if ($entity instanceof Grund) {
        $salgsomraade = $entity->getSalgsomraade();
        $this->updatedSalgsomraade[$salgsomraade->getId()] = $entity->getSalgsomraade();
      } else if ($entity instanceof Salgsomraade) {
        $this->updatedSalgsomraade[$entity->getId()] = $entity;
      }
    }
  }

  /**
   * Notify web-site of inserted/updated Salgsomraade entities.
   */
  public function postFlush() {
    foreach ($this->updatedSalgsomraade as $id => $salgsomraade) {
      $this->service->syncDataToWebsite($salgsomraade);
    }
  }

}
