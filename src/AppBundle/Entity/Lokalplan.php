<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lokalplan
 *
 * @ORM\Table(name="Lokalplan", indexes={
 *   @ORM\Index(name="search_Lokalplan_nr", columns={"nr"}),
 *   @ORM\Index(name="search_Lokalplan_titel", columns={"titel"}),
 *   @ORM\Index(name="search_Lokalplan_samletAreal", columns={"samletAreal"}),
 *   @ORM\Index(name="search_Lokalplan_salgbartAreal", columns={"salgbartAreal"}),
 *   @ORM\Index(name="search_Lokalplan_forbrugsAndel", columns={"forbrugsAndel"})
 * })
 * @ORM\Entity
 */
class Lokalplan
{
  use BlameableEntity;
  use TimestampableEntity;


  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="nr", type="string", length=50, nullable=false)
   */
  private $nr;

  /**
   * @var \Lokalsamfund
   *
   * @ORM\ManyToOne(targetEntity="Lokalsamfund", fetch="EAGER")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="lokalsamfundId", referencedColumnName="id")
   * })
   */
  private $lokalsamfund;

  /**
   * @var string
   *
   * @ORM\Column(name="titel", type="string", length=255, nullable=false)
   */
  private $titel;

  /**
   * @var string
   *
   * @ORM\Column(name="projektLeder", type="string", length=50, nullable=false)
   */
  private $projektleder;

  /**
   * @var string
   *
   * @ORM\Column(name="telefon", type="string", length=20, nullable=false)
   */
  private $telefon;

  /**
   * @var string
   *
   * @ORM\Column(name="lokalPlanLink", type="string", length=255, nullable=true)
   */
  private $lokalplanlink;

  /**
   * @var integer
   *
   * @ORM\Column(name="samletAreal", type="integer", nullable=true)
   */
  private $samletareal;

  /**
   * @var integer
   *
   * @ORM\Column(name="salgbartAreal", type="integer", nullable=true)
   */
  private $salgbartareal;

  /**
   * @var string
   *
   * @ORM\Column(name="forbrugsAndel", type="float", precision=18, scale=12, nullable=false)
   */
  private $forbrugsandel;


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
   * Set nr
   *
   * @param string $nr
   *
   * @return Lokalplan
   */
  public function setNr($nr)
  {
    $this->nr = $nr;

    return $this;
  }

  /**
   * Get nr
   *
   * @return string
   */
  public function getNr()
  {
    return $this->nr;
  }

  /**
   * Set lokalsamfund
   *
   * @param \AppBundle\Entity\Lokalsamfund $lokalsamfund
   *
   * @return Grund
   */
  public function setLokalsamfund(\AppBundle\Entity\Lokalsamfund $lokalsamfund = null)
  {
    $this->lokalsamfund = $lokalsamfund;

    return $this;
  }

  /**
   * Get lokalsamfund
   *
   * @return \AppBundle\Entity\Lokalsamfund
   */
  public function getLokalsamfund()
  {
    return $this->lokalsamfund;
  }

  /**
   * Set titel
   *
   * @param string $titel
   *
   * @return Lokalplan
   */
  public function setTitel($titel)
  {
    $this->titel = $titel;

    return $this;
  }

  /**
   * Get titel
   *
   * @return string
   */
  public function getTitel()
  {
    return $this->titel;
  }

  /**
   * Set projektleder
   *
   * @param string $projektleder
   *
   * @return Lokalplan
   */
  public function setProjektleder($projektleder)
  {
    $this->projektleder = $projektleder;

    return $this;
  }

  /**
   * Get projektleder
   *
   * @return string
   */
  public function getProjektleder()
  {
    return $this->projektleder;
  }

  /**
   * Set telefon
   *
   * @param string $telefon
   *
   * @return Lokalplan
   */
  public function setTelefon($telefon)
  {
    $this->telefon = $telefon;

    return $this;
  }

  /**
   * Get telefon
   *
   * @return string
   */
  public function getTelefon()
  {
    return $this->telefon;
  }

  /**
   * Set lokalplanlink
   *
   * @param string $lokalplanlink
   *
   * @return Lokalplan
   */
  public function setLokalplanlink($lokalplanlink)
  {
    $this->lokalplanlink = $lokalplanlink;

    return $this;
  }

  /**
   * Get lokalplanlink
   *
   * @return string
   */
  public function getLokalplanlink()
  {
    return $this->lokalplanlink;
  }

  /**
   * Set samletareal
   *
   * @param string $samletareal
   *
   * @return Lokalplan
   */
  public function setSamletareal($samletareal)
  {
    $this->samletareal = $samletareal;

    return $this;
  }

  /**
   * Get samletareal
   *
   * @return string
   */
  public function getSamletareal()
  {
    return $this->samletareal;
  }

  /**
   * Set salgbartareal
   *
   * @param integer $salgbartareal
   *
   * @return Lokalplan
   */
  public function setSalgbartareal($salgbartareal)
  {
    $this->salgbartareal = $salgbartareal;

    return $this;
  }

  /**
   * Get salgbartareal
   *
   * @return integer
   */
  public function getSalgbartareal()
  {
    return $this->salgbartareal;
  }

  /**
   * Set forbrugsandel
   *
   * @param integer $forbrugsandel
   *
   * @return Lokalplan
   */
  public function setForbrugsandel($forbrugsandel)
  {
    $this->forbrugsandel = $forbrugsandel;

    return $this;
  }

  /**
   * Get forbrugsandel
   *
   * @return integer
   */
  public function getForbrugsandel()
  {
    return $this->forbrugsandel;
  }

  public function __toString()
  {
    return $this->getNr() . ' - ' . $this->getTitel();
  }
}
