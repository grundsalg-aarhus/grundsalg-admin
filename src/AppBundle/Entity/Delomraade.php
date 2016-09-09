<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Delomraade
 *
 * @ORM\Table(name="Delomraade", indexes={@ORM\Index(name="fk_Delomraade_lokalplanId", columns={"lokalplanId"})})
 * @ORM\Entity
 */
class Delomraade
{
  use BlameableEntity;
  use TimestampableEntity;

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
   * @ORM\Column(name="kpl1", type="string", length=50, nullable=false)
   */
  private $kpl1;

  /**
   * @var string
   *
   * @ORM\Column(name="kpl2", type="string", length=50, nullable=false)
   */
  private $kpl2;

  /**
   * @var string
   *
   * @ORM\Column(name="kpl3", type="string", length=50, nullable=false)
   */
  private $kpl3;

  /**
   * @var string
   *
   * @ORM\Column(name="kpl4", type="string", length=50, nullable=false)
   */
  private $kpl4;

  /**
   * @var string
   *
   * @ORM\Column(name="o1", type="string", length=50, nullable=false)
   */
  private $o1;

  /**
   * @var string
   *
   * @ORM\Column(name="o2", type="string", length=50, nullable=false)
   */
  private $o2;

  /**
   * @var string
   *
   * @ORM\Column(name="o3", type="string", length=50, nullable=false)
   */
  private $o3;

  /**
   * @var string
   *
   * @ORM\Column(name="anvendelse", type="text", nullable=false)
   */
  private $anvendelse;

  /**
   * @var string
   *
   * @ORM\Column(name="mulighedFor", type="text", nullable=false)
   */
  private $mulighedfor;

  /**
   * @var string
   *
   * @ORM\Column(name="createdBy", type="text", nullable=false)
   */
  private $createdby;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="createdDate", type="date", nullable=false)
   */
  private $createddate;

  /**
   * @var string
   *
   * @ORM\Column(name="modifiedBy", type="text", nullable=false)
   */
  private $modifiedby;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="modifiedDate", type="date", nullable=false)
   */
  private $modifieddate;

  /**
   * @var \Lokalplan
   *
   * @ORM\ManyToOne(targetEntity="Lokalplan")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="lokalplanId", referencedColumnName="id")
   * })
   */
  private $lokalplanid;


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
   * Set kpl1
   *
   * @param string $kpl1
   *
   * @return Delomraade
   */
  public function setKpl1($kpl1)
  {
    $this->kpl1 = $kpl1;

    return $this;
  }

  /**
   * Get kpl1
   *
   * @return string
   */
  public function getKpl1()
  {
    return $this->kpl1;
  }

  /**
   * Set kpl2
   *
   * @param string $kpl2
   *
   * @return Delomraade
   */
  public function setKpl2($kpl2)
  {
    $this->kpl2 = $kpl2;

    return $this;
  }

  /**
   * Get kpl2
   *
   * @return string
   */
  public function getKpl2()
  {
    return $this->kpl2;
  }

  /**
   * Set kpl3
   *
   * @param string $kpl3
   *
   * @return Delomraade
   */
  public function setKpl3($kpl3)
  {
    $this->kpl3 = $kpl3;

    return $this;
  }

  /**
   * Get kpl3
   *
   * @return string
   */
  public function getKpl3()
  {
    return $this->kpl3;
  }

  /**
   * Set kpl4
   *
   * @param string $kpl4
   *
   * @return Delomraade
   */
  public function setKpl4($kpl4)
  {
    $this->kpl4 = $kpl4;

    return $this;
  }

  /**
   * Get kpl4
   *
   * @return string
   */
  public function getKpl4()
  {
    return $this->kpl4;
  }

  /**
   * Set o1
   *
   * @param string $o1
   *
   * @return Delomraade
   */
  public function setO1($o1)
  {
    $this->o1 = $o1;

    return $this;
  }

  /**
   * Get o1
   *
   * @return string
   */
  public function getO1()
  {
    return $this->o1;
  }

  /**
   * Set o2
   *
   * @param string $o2
   *
   * @return Delomraade
   */
  public function setO2($o2)
  {
    $this->o2 = $o2;

    return $this;
  }

  /**
   * Get o2
   *
   * @return string
   */
  public function getO2()
  {
    return $this->o2;
  }

  /**
   * Set o3
   *
   * @param string $o3
   *
   * @return Delomraade
   */
  public function setO3($o3)
  {
    $this->o3 = $o3;

    return $this;
  }

  /**
   * Get o3
   *
   * @return string
   */
  public function getO3()
  {
    return $this->o3;
  }

  /**
   * Set anvendelse
   *
   * @param string $anvendelse
   *
   * @return Delomraade
   */
  public function setAnvendelse($anvendelse)
  {
    $this->anvendelse = $anvendelse;

    return $this;
  }

  /**
   * Get anvendelse
   *
   * @return string
   */
  public function getAnvendelse()
  {
    return $this->anvendelse;
  }

  /**
   * Set mulighedfor
   *
   * @param string $mulighedfor
   *
   * @return Delomraade
   */
  public function setMulighedfor($mulighedfor)
  {
    $this->mulighedfor = $mulighedfor;

    return $this;
  }

  /**
   * Get mulighedfor
   *
   * @return string
   */
  public function getMulighedfor()
  {
    return $this->mulighedfor;
  }

  /**
   * Set createdby
   *
   * @param string $createdby
   *
   * @return Delomraade
   */
  public function setCreatedby($createdby)
  {
    $this->createdby = $createdby;

    return $this;
  }

  /**
   * Get createdby
   *
   * @return string
   */
  public function getCreatedby()
  {
    return $this->createdby;
  }

  /**
   * Set createddate
   *
   * @param \DateTime $createddate
   *
   * @return Delomraade
   */
  public function setCreateddate($createddate)
  {
    $this->createddate = $createddate;

    return $this;
  }

  /**
   * Get createddate
   *
   * @return \DateTime
   */
  public function getCreateddate()
  {
    return $this->createddate;
  }

  /**
   * Set modifiedby
   *
   * @param string $modifiedby
   *
   * @return Delomraade
   */
  public function setModifiedby($modifiedby)
  {
    $this->modifiedby = $modifiedby;

    return $this;
  }

  /**
   * Get modifiedby
   *
   * @return string
   */
  public function getModifiedby()
  {
    return $this->modifiedby;
  }

  /**
   * Set modifieddate
   *
   * @param \DateTime $modifieddate
   *
   * @return Delomraade
   */
  public function setModifieddate($modifieddate)
  {
    $this->modifieddate = $modifieddate;

    return $this;
  }

  /**
   * Get modifieddate
   *
   * @return \DateTime
   */
  public function getModifieddate()
  {
    return $this->modifieddate;
  }

  /**
   * Set lokalplanid
   *
   * @param \AppBundle\Entity\Lokalplan $lokalplanid
   *
   * @return Delomraade
   */
  public function setLokalplanid(\AppBundle\Entity\Lokalplan $lokalplanid = null)
  {
    $this->lokalplanid = $lokalplanid;

    return $this;
  }

  /**
   * Get lokalplanid
   *
   * @return \AppBundle\Entity\Lokalplan
   */
  public function getLokalplanid()
  {
    return $this->lokalplanid;
  }

  public function __toString()
  {
    return $this->kpl1 . "-" . $this->kpl2 . "-" . $this->kpl3 . "-" . $this->kpl4 . " " . $this->o1 . " " . $this->o2 . " " . $this->o3;
  }
}
