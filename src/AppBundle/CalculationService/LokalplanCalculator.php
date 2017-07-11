<?php

namespace AppBundle\CalculationService;

use AppBundle\Entity\Grund;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\Entity\Lokalplan;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class LokalplanCalculator implements EventSubscriber {

  /**
   * {@inheritdoc}
   */
  public function getSubscribedEvents() {
    return [
      'prePersist',
      'preUpdate'
    ];
  }

  public function prePersist(LifecycleEventArgs $args) {
    $lokalplan = $args->getObject();

    // only act on some "Lokalplan" entity
    if (!$lokalplan instanceof Lokalplan) {
      return;
    }

    $this->calculateForbrugsAndel($lokalplan);

  }

  public function preUpdate(LifecycleEventArgs $args) {
    $lokalplan = $args->getObject();

    // only act on some "Lokalplan" entity
    if (!$lokalplan instanceof Lokalplan) {
      return;
    }

    $this->calculateForbrugsAndel($lokalplan);

  }

  /**
   * Calculate 'forbrugsandel'
   *
   * Copy/paste from legacy system
   *
   * @param \AppBundle\Entity\Lokalplan $lokalplan
   */
  private function calculateForbrugsAndel(Lokalplan $lokalplan) {
    $forbrugsandel = 0;

    if($lokalplan->getSalgbartareal() && $lokalplan->getSamletareal()) {
      $forbrugsandel = 100 * $lokalplan->getSamletareal() / $lokalplan->getSalgbartareal();
    }

    $lokalplan->setForbrugsandel($forbrugsandel);
  }

}