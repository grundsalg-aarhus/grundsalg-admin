<?php

namespace AppBundle\CalculationService;

use AppBundle\Entity\Delomraade;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\Entity\Opkoeb;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class OpkoebCalculator implements EventSubscriber {

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
    $opkoeb = $args->getEntity();

    // only act on some "Opkoeb" entity
    if (!$opkoeb instanceof Opkoeb) {
      return;
    }

    $this->calculateProcentAfLokalplan($opkoeb);

  }

  public function preUpdate(LifecycleEventArgs $args) {
    $opkoeb = $args->getEntity();

    // only act on some "Opkoeb" entity
    if (!$opkoeb instanceof Opkoeb) {
      return;
    }

    $this->calculateProcentAfLokalplan($opkoeb);
  }

  /**
   * Calculate how big a percentage of the area of the 'Lokalplan' this 'Opkoeb' is
   *
   * @param \AppBundle\Entity\Opkoeb $opkoeb
   */
  private function calculateProcentAfLokalplan(Opkoeb $opkoeb) {
    $procentAfLokalplan = 0;

    if($opkoeb->getLokalplan()->getSamletareal() && $opkoeb->getM2()) {
      $procentAfLokalplan = ( $opkoeb->getM2() / $opkoeb->getLokalplan()->getSamletareal() ) * 100;
    }

    $opkoeb->setProcentaflp($procentAfLokalplan);
  }

}