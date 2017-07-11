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
    $delomraade = $args->getObject();

    // only act on some "Delomraade" entity
    if (!$delomraade instanceof Delomraade) {
      return;
    }

    $this->calculateKpl1($delomraade);
    $this->calculateO1($delomraade);

  }

  /**
   * Set 'kpl1' as 'Lokalsamfund' number
   *
   * @param \AppBundle\Entity\Delomraade $delomraade
   */
  private function calculateKpl1(Delomraade $delomraade) {
    $delomraade->setKpl1($delomraade->getLokalplan()->getLokalsamfund()->getNumber());
  }

  /**
   * Set 'O1' value to 'Lokalplan' number if it's empty
   *
   * @param \AppBundle\Entity\Delomraade $delomraade
   */
  private function calculateO1(Delomraade $delomraade) {
    if(!$delomraade->getO1()) {
      $delomraade->setO1($delomraade->getLokalplan()->getNr());
    }
  }

}