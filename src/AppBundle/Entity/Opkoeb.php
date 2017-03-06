<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * Opkoeb
 *
 * @ORM\Table(name="Opkoeb", indexes={@ORM\Index(name="fk_Opkoeb_lokalplanId", columns={"lokalplanId"})})
 * @ORM\Entity
 */
class Opkoeb
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
   * @ORM\Column(name="matrik1", type="string", length=50, nullable=true)
   */
  private $matrik1;

  /**
   * @var string
   *
   * @ORM\Column(name="matrik2", type="string", length=50, nullable=true)
   */
  private $matrik2;

  /**
   * @var string
   *
   * @ORM\Column(name="ejerlav", type="string", length=60, nullable=true)
   */
  private $ejerlav;

  /**
   * @var integer
   *
   * @ORM\Column(name="m2", type="integer", nullable=true)
   */
  private $m2;

  /**
   * @var string
   *
   * @ORM\Column(name="bemaerkning", type="text", nullable=true)
   */
  private $bemaerkning;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="opkoebDato", type="date", nullable=true)
   */
  private $opkoebdato;

  /**
   * @var string
   *
   * @ORM\Column(name="pris", type="float", precision=16, scale=2, nullable=true)
   */
  private $pris;

  /**
   * @var string
   *
   * @ORM\Column(name="procentAfLP", type="float", precision=12, scale=2, nullable=true)
   */
  private $procentaflp;

  /**
   * @var \Lokalplan
   *
   * @ORM\ManyToOne(targetEntity="Lokalplan")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="lokalplanId", referencedColumnName="id")
   * })
   * @OrderBy({"nr" = "ASC"})
   */
  private $lokalplan;


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
   * Set matrik1
   *
   * @param string $matrik1
   *
   * @return Opkoeb
   */
  public function setMatrik1($matrik1)
  {
    $this->matrik1 = $matrik1;

    return $this;
  }

  /**
   * Get matrik1
   *
   * @return string
   */
  public function getMatrik1()
  {
    return $this->matrik1;
  }

  /**
   * Set matrik2
   *
   * @param string $matrik2
   *
   * @return Opkoeb
   */
  public function setMatrik2($matrik2)
  {
    $this->matrik2 = $matrik2;

    return $this;
  }

  /**
   * Get matrik2
   *
   * @return string
   */
  public function getMatrik2()
  {
    return $this->matrik2;
  }

  /**
   * Set ejerlav
   *
   * @param string $ejerlav
   *
   * @return Opkoeb
   */
  public function setEjerlav($ejerlav)
  {
    $this->ejerlav = $ejerlav;

    return $this;
  }

  /**
   * Get ejerlav
   *
   * @return string
   */
  public function getEjerlav()
  {
    return $this->ejerlav;
  }

  /**
   * Set m2
   *
   * @param integer $m2
   *
   * @return Opkoeb
   */
  public function setM2($m2)
  {
    $this->m2 = $m2;

    return $this;
  }

  /**
   * Get m2
   *
   * @return integer
   */
  public function getM2()
  {
    return $this->m2;
  }

  /**
   * Set bemaerkning
   *
   * @param string $bemaerkning
   *
   * @return Opkoeb
   */
  public function setBemaerkning($bemaerkning)
  {
    $this->bemaerkning = $bemaerkning;

    return $this;
  }

  /**
   * Get bemaerkning
   *
   * @return string
   */
  public function getBemaerkning()
  {
    return $this->bemaerkning;
  }

  /**
   * Set opkoebdato
   *
   * @param \DateTime $opkoebdato
   *
   * @return Opkoeb
   */
  public function setOpkoebdato($opkoebdato)
  {
    $this->opkoebdato = $opkoebdato;

    return $this;
  }

  /**
   * Get opkoebdato
   *
   * @return \DateTime
   */
  public function getOpkoebdato()
  {
    return $this->opkoebdato;
  }

  /**
   * Set pris
   *
   * @param string $pris
   *
   * @return Opkoeb
   */
  public function setPris($pris)
  {
    $this->pris = $pris;

    return $this;
  }

  /**
   * Get pris
   *
   * @return string
   */
  public function getPris()
  {
    return $this->pris;
  }

  /**
   * Set procentaflp
   *
   * @param string $procentaflp
   *
   * @return Opkoeb
   */
  public function setProcentaflp($procentaflp)
  {
    $this->procentaflp = $procentaflp;

    return $this;
  }

  /**
   * Get procentaflp
   *
   * @return string
   */
  public function getProcentaflp()
  {
    return $this->procentaflp;
  }

  /**
   * Set lpid
   *
   * @param \AppBundle\Entity\Lokalplan $lokalplan
   *
   * @return Opkoeb
   */
  public function setLokalplan(\AppBundle\Entity\Lokalplan $lokalplan = null)
  {
    $this->lokalplan = $lokalplan;

    return $this;
  }

  /**
   * Get lpid
   *
   * @return \AppBundle\Entity\Lokalplan
   */
  public function getLokalplan()
  {
    return $this->lokalplan;
  }

  public function getMatrikel()
  {
    return $this->getMatrik1().$this->getMatrik2();
  }

  public function __toString()
  {
    return $this->matrik1 . " " . $this->matrik2;
  }
}
