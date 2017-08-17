<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use AppBundle\Validator\Constraints as GrundAssert;

/**
 * GrundCollection
 *
 * This entity is a hack to support 'multiple create' forms for 'Grund' through EasyAdmins
 * standard config and functions.
 *
 * We have to map it to the database to avoid EasyAdmin throwing an exception even though
 * it gives us an empty/redundant db-column.
 *
 * When a new collection is persisted we catch the event in AppBundle\EventSubscriber\GrundBulkCreateSubscriber
 * and handle saving the individual 'grunde' there.
 *
 * AppBundle\EventSubscriber\GrundBulkCreateSubscriber also handles cleanup so that all collections
 * are deleted once they become redundant.
 *
 * @ORM\Entity
 *
 * @GrundAssert\LokalsamfundNotNull
 */
class GrundCollection extends Grund {

  /**
   * @var \AppBundle\Entity\Grund
   */
  private $grunde;

  /**
   * @var \AppBundle\Entity\Salgsomraade
   *
   * @ORM\ManyToOne(targetEntity="Salgsomraade", fetch="EAGER")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="salgsomraadeId", referencedColumnName="id", nullable=false)
   * })
   */
  private $salgsomraade;

  public function __construct() {
    parent::__construct();

    $this->grunde = new ArrayCollection();
    $this->grunde->add(new Grund());
    $this->setStatus(GrundStatus::FREMTIDIG);
    $this->setSalgstatus(GrundSalgStatus::LEDIG);

    // Set values for requried fields to avoid validation errors.
    $this->setMnr('abc');
    $this->setMnr2('abc');
  }

  /**
   * @return mixed
   */
  public function getGrunde() {
    return $this->grunde;
  }

  public function setGrunde(ArrayCollection $grunde) {
    $this->grunde = $grunde;
  }

}
