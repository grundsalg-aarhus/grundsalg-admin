<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interessentgrundmapping
 *
 * @ORM\Table(name="InteressentGrundMapping", indexes={@ORM\Index(name="fk_InteressentGrundMapping_interessentId", columns={"interessentId"}), @ORM\Index(name="fk_InteressentGrundMapping_grundId", columns={"grundId"})})
 * @ORM\Entity
 */
class Interessentgrundmapping
{
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="bigint", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="annulleret", type="string", length=50, nullable=false)
   */
  private $annulleret;

  /**
   * @var \Grund
   *
   * @ORM\ManyToOne(targetEntity="Grund")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="grundId", referencedColumnName="id")
   * })
   */
  private $grundid;

  /**
   * @var \Interessent
   *
   * @ORM\ManyToOne(targetEntity="Interessent")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="interessentId", referencedColumnName="id")
   * })
   */
  private $interessentid;


  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set annulleret
   *
   * @param string $annulleret
   *
   * @return Interessentgrundmapping
   */
  public function setAnnulleret($annulleret)
  {
    $this->annulleret = $annulleret;

    return $this;
  }

  /**
   * Get annulleret
   *
   * @return string
   */
  public function getAnnulleret()
  {
    return $this->annulleret;
  }

  /**
   * Set grundid
   *
   * @param \AppBundle\Entity\Grund $grundid
   *
   * @return Interessentgrundmapping
   */
  public function setGrundid(\AppBundle\Entity\Grund $grundid = null)
  {
    $this->grundid = $grundid;

    return $this;
  }

  /**
   * Get grundid
   *
   * @return \AppBundle\Entity\Grund
   */
  public function getGrundid()
  {
    return $this->grundid;
  }

  /**
   * Set interessentid
   *
   * @param \AppBundle\Entity\Interessent $interessentid
   *
   * @return Interessentgrundmapping
   */
  public function setInteressentid(\AppBundle\Entity\Interessent $interessentid = null)
  {
    $this->interessentid = $interessentid;

    return $this;
  }

  /**
   * Get interessentid
   *
   * @return \AppBundle\Entity\Interessent
   */
  public function getInteressentid()
  {
    return $this->interessentid;
  }

  public function __toString()
  {
    return __CLASS__;
  }
}
