<?php

namespace AppBundle\CalculationService;

use AppBundle\Entity\Grund;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class GrundCalculator implements EventSubscriber {

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
    $grund = $args->getEntity();

    // only act on some "Grund" entity
    if (!$grund instanceof Grund) {
      return;
    }

    $this->persistStatus($grund);
    $this->calculateSalgstatus($grund);
    $this->calculateToDates($grund);

  }

  public function preUpdate(LifecycleEventArgs $args) {
    $grund = $args->getEntity();
    $changeset = $args->getEntityChangeSet();

    // only act on some "Grund" entity
    if (!$grund instanceof Grund) {
      return;
    }

    // if either status or salgsstaus is explicitly set abstain from changing
    // (bulk change)
    if(!array_key_exists('status', $changeset)) {
      $this->updateStatus($grund);
    }
    if(!array_key_exists('salgstatus', $changeset)) {
      $this->calculateSalgstatus($grund);
    }
    $this->calculateToDates($grund);

  }

  /**
   * Update status base on dates for auktion, reserveret, etc.
   *
   * "Copy-paste" from legacy system
   *
   * @param \AppBundle\Entity\Grund $grund
   */
  private function updateStatus(Grund $grund)
  {
    $today = new \DateTime();
    $today->setTime(12,0);

    if($grund->getSalgstype() === SalgsType::AUKTION) {

      // Auktion slut dato i fortid: Auktion slut
      if($grund->getAuktionslutdato() && $grund->getAuktionslutdato() < $today) {
        $grund->setStatus(GrundStatus::AUKTION_SLUT);
      }

      // Ny ell. 'Fremtidig'/'Annonceret' grund - annonce dato i fortiden: Annonceret
      elseif(($grund->getStatus() === GrundStatus::FREMTIDIG || $grund->getStatus() === GrundStatus::ANNONCERET) && $grund->getDatoannonce() && $grund->getDatoannonce() <= $today) {
        $grund->setStatus(GrundStatus::ANNONCERET);
      }

      // Auktion slut dato i fremtid: Fremtidig
      elseif($grund->getAuktionslutdato() && $grund->getAuktionslutdato() > $today) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }

      // Ingen auktion start dato: Fremtidig
      elseif(!$grund->getAuktionstartdato()) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }

      // 'Annonceret' grund - annoncedato i fremtiden: Fremtidig
      elseif($grund->getStatus() === GrundStatus::ANNONCERET && $grund->getDatoannonce() && $grund->getDatoannonce() > $today) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }

    } else {

      // 'Fremtidig'/'Annonceret' grund - annonce dato i fortiden: Annonceret
      if(($grund->getStatus() === GrundStatus::FREMTIDIG || $grund->getStatus() === GrundStatus::ANNONCERET) && $grund->getDatoannonce() && $grund->getDatoannonce() <= $today) {
        $grund->setStatus(GrundStatus::ANNONCERET);
      }

      // 'Skal-annonceres' grund - ingen annonce dato endnu: Fremtidig
      elseif ($grund->isAnnonceres() && !$grund->getDatoannonce()) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }

      // 'Annonceret' grund - annoncedato i fremtiden: Fremtidig
      elseif ($grund->getStatus() === GrundStatus::ANNONCERET && $grund->getDatoannonce() && $grund->getDatoannonce() > $today) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }
    }
  }

  /**
   * Update status base on dates for auktion, reserveret, etc.
   *
   * "Copy-paste" from legacy system
   *
   * @param \AppBundle\Entity\Grund $grund
   */
  private function persistStatus(Grund $grund)
  {
    $today = new \DateTime();
    $today->setTime(12,0);

    if($grund->getSalgstype() === SalgsType::AUKTION) {

      // Ny - annonce dato i fortiden: Annonceret
      if($grund->getDatoannonce() && $grund->getDatoannonce() <= $today) {
        $grund->setStatus(GrundStatus::ANNONCERET);
      }

      // Ny - ingen annonce dato: Fremtidig
      elseif(!$grund->getDatoannonce()) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }

      // Ny - annoncedato i fremtiden: Fremtidig
      elseif($grund->getDatoannonce() && $grund->getDatoannonce() > $today) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }

    } else {

      // Ny - annonce dato i fortiden: Annonceret
      if($grund->getDatoannonce() && $grund->getDatoannonce() <= $today) {
        $grund->setStatus(GrundStatus::ANNONCERET);
      }

      // Ny - ingen annonce dato endnu: Fremtidig
      elseif ($grund->isAnnonceres() && !$grund->getDatoannonce()) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }

      // Ny - annoncedato i fremtiden: Fremtidig
      elseif ($grund->getDatoannonce() && $grund->getDatoannonce() > $today) {
        $grund->setStatus(GrundStatus::FREMTIDIG);
      }
    }
  }

  /**
   * Update salgstatus base on dates for auktion, reserveret, etc.
   *
   * "Copy-paste" from legacy system
   *
   * @param \AppBundle\Entity\Grund $grund
   */
  private function calculateSalgstatus(Grund $grund) {
    $today = new \DateTime();
    $today->setTime(12,0);

    if($grund->getBeloebanvist()) {
      $grund->setSalgstatus(GrundSalgStatus::SOLGT);
    } elseif ($grund->getSkoederekv()) {
      $grund->setSalgstatus(GrundSalgStatus::SKOEDE_REKVIRERET);
    } elseif ($grund->getAccept()) {
      $grund->setSalgstatus(GrundSalgStatus::ACCEPTERET);
    } elseif ($grund->getAuktionstartdato() && $grund->getAuktionslutdato() && $grund->getAuktionslutdato() < $today) {
      $grund->setSalgstatus(GrundSalgStatus::AUKTION_SLUT);
    } elseif ($grund->getTilbudstart()) {
      $grund->setSalgstatus(GrundSalgStatus::TILBUD_SENDT);
    } elseif ($grund->getResstart()) {
      $grund->setSalgstatus(GrundSalgStatus::RESERVERET);
    } else {
      $grund->setSalgstatus(GrundSalgStatus::LEDIG);
    }
  }

  /**
   * If the are not set, update 'til og med' dates base on their respective 'fra' dates
   *
   * "Copy-paste" from legacy system
   *
   * @param \AppBundle\Entity\Grund $grund
   */
  private function calculateToDates(Grund $grund) {

    // Default reservation is 14 days
    if($grund->getResstart() && !$grund->getResslut()) {
      $endDay = clone $grund->getResstart();
      $endDay->add(new \DateInterval('P14D'));

      $grund->setResslut($endDay);
    }

    // Default 'tilbud' is 28 days
    if($grund->getTilbudstart() && !$grund->getTilbudslut()) {
      $endDay = clone $grund->getTilbudstart();
      $endDay->add(new \DateInterval('P28D'));

      $grund->setTilbudslut($endDay);
    }

  }

}