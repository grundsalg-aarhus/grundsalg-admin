<?php

namespace AppBundle\CalculationService;

use AppBundle\Entity\Salgsomraade;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class SalgsomraadeCalculator implements EventSubscriber {
  private $em;

  /**
   * Constructor
   */
  public function __construct(EntityManager $em) {
    $this->em = $em;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubscribedEvents() {
    return [
      'prePersist',
    ];
  }

  public function prePersist(LifecycleEventArgs $args) {
    $salgsomraade = $args->getObject();

    // only act on some "Salgsomraade" entity
    if (!$salgsomraade instanceof Salgsomraade) {
      return;
    }

    $this->calculateLpLoebenummerNr($salgsomraade);
    $this->calculateNr($salgsomraade);
  }

  private function calculateLpLoebenummerNr(Salgsomraade $salgsomraade) {
    $repo = $this->em->getRepository('AppBundle\Entity\Salgsomraade');

    $higestLpNum = $repo->findMaxLpLoebenummerById($salgsomraade->getLokalplan());
    $nextLpNum = sprintf("%03d", $higestLpNum + 1);

    $salgsomraade->setLploebenummer($nextLpNum);
  }

  private function calculateNr(Salgsomraade $salgsomraade) {
    $nr = 'S-' . $salgsomraade->getLokalplan()->getNr() . '-' . $salgsomraade->getLploebenummer();

    $salgsomraade->setNr($nr);
  }

}