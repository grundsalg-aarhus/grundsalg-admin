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
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

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
      Events::onFlush,
      Events::postFlush,
    ];
  }

  /**
   * Set the endpoint url defined in parameters.yml
   */
  public function __construct(GrundsalgCommunicationService $service) {
    $this->service = $service;
  }

  /**
   * @var array Storage for inserted/updated/deleted Salgsomraade entities.
   *
   * Caveat: https://stackoverflow.com/a/43604820
   */
  private $updatedSalgsomraade = [];

  const INSERT = 'insert';
  const UPDATE = 'update';
  const DELETE = 'delete';

  /**
   * Store a list of inserted/updated/deleted Salgsomraade entities.
   *
   * @param \Doctrine\ORM\Event\OnFlushEventArgs $args
   */
  public function onFlush(OnFlushEventArgs $args) {
    $uow = $args->getEntityManager()->getUnitOfWork();
    $this->updatedSalgsomraade = [
      self::INSERT => [],
      self::UPDATE => [],
    ];
    foreach ($uow->getScheduledEntityInsertions() as $entity) {
      if ($entity instanceof Salgsomraade) {
        $this->updatedSalgsomraade[self::INSERT][] = $entity;
      }
    }
    foreach ($uow->getScheduledEntityUpdates() as $entity) {
      if ($entity instanceof Salgsomraade) {
        $this->updatedSalgsomraade[self::UPDATE][] = $entity;
      }
    }
    foreach ($uow->getScheduledEntityDeletions() as $entity) {
      if ($entity instanceof Salgsomraade) {
        $this->updatedSalgsomraade[self::DELETE][] = $entity;
      }
    }
  }

  /**
   * Notify web-site of inserted/updated Salgsomraade entities.
   */
  public function postFlush() {
    if (isset($this->updatedSalgsomraade[self::INSERT])) {
      foreach ($this->updatedSalgsomraade[self::INSERT] as $salgsomraade) {
        $this->service->saveSalgsomraade($salgsomraade);
      }
    }
    if (isset($this->updatedSalgsomraade[self::UPDATE])) {
      foreach ($this->updatedSalgsomraade[self::UPDATE] as $salgsomraade) {
        $this->service->saveSalgsomraade($salgsomraade);
      }
    }
  }

}
