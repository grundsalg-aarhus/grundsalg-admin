<?php

namespace AppBundle\CalculationService;

use AppBundle\Entity\Delomraade;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class DelomraadeCalculator implements EventSubscriber {

  /**
   * {@inheritdoc}
   */
  public function getSubscribedEvents() {
    return [
      'prePersist'
    ];
  }

  public function prePersist(LifecycleEventArgs $args) {
    $lokalplan = $args->getEntity();

    // only act on some "Delomraade" entity
    if (!$lokalplan instanceof Delomraade) {
      return;
    }

    $this->calculateKpl1($lokalplan);
    $this->calculateO1($lokalplan);

  }

  private function calculateKpl1(Delomraade $delomraade) {
    $delomraade->setKpl1($delomraade->getLokalplan()->getLokalsamfund()->getNumber());
  }

  private function calculateO1(Delomraade $delomraade) {
    if(!$delomraade->getO1()) {
      $delomraade->setO1($delomraade->getLokalplan()->getNr());
    }
  }

}