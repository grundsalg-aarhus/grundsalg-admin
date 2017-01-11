<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Salgsomraade
 *
 * @ORM\Table(name="Salgsomraade", indexes={@ORM\Index(name="fk_Salgsomraade_postById", columns={"postById"}), @ORM\Index(name="fk_Salgsomraade_delomraadeId", columns={"delomraadeId"}), @ORM\Index(name="fk_Salgsomraade_lokalplanId", columns={"lokalplanId"}), @ORM\Index(name="fk_Salgsomraade_landinspektorId", columns={"landinspektorId"})})
 * @ORM\Entity
 */
class Salgsomraade
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
   * @ORM\Column(name="nr", type="string", length=50, nullable=false)
   */
  private $nr;

  /**
   * @var string
   *
   * @ORM\Column(name="titel", type="text", nullable=false)
   */
  private $titel;

  /**
   * @var string
   *
   * @ORM\Column(name="type", type="text", nullable=false)
   */
  private $type;

  /**
   * @var string
   *
   * @ORM\Column(name="matrikkel1", type="text", nullable=false)
   */
  private $matrikkel1;

  /**
   * @var string
   *
   * @ORM\Column(name="matrikkel2", type="text", nullable=false)
   */
  private $matrikkel2;

  /**
   * @var string
   *
   * @ORM\Column(name="ejerlav", type="text", nullable=false)
   */
  private $ejerlav;

  /**
   * @var string
   *
   * @ORM\Column(name="vej", type="text", nullable=false)
   */
  private $vej;

  /**
   * @var string
   *
   * @ORM\Column(name="gisUrl", type="text", nullable=false)
   */
  private $gisurl;

  /**
   * @var string
   *
   * @ORM\Column(name="tilsluttet", type="text", nullable=false)
   */
  private $tilsluttet;

  /**
   * @var string
   *
   * @ORM\Column(name="sagsNr", type="text", nullable=false)
   */
  private $sagsnr;

  /**
   * @var integer
   *
   * @ORM\Column(name="lpLoebeNummer", type="bigint", nullable=false)
   */
  private $lploebenummer;

  /**
   * @var \Landinspektoer
   *
   * @ORM\ManyToOne(targetEntity="Landinspektoer")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="landinspektorId", referencedColumnName="id")
   * })
   */
  private $landinspektorid;

  /**
   * @var \Delomraade
   *
   * @ORM\ManyToOne(targetEntity="Delomraade")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="delomraadeId", referencedColumnName="id")
   * })
   */
  private $delomraadeid;

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
   * @var \Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="postById", referencedColumnName="id")
   * })
   */
  private $postbyid;


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
   * @return Salgsomraade
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
   * Set titel
   *
   * @param string $titel
   *
   * @return Salgsomraade
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
   * Set type
   *
   * @param string $type
   *
   * @return Salgsomraade
   */
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }

  /**
   * Get type
   *
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set matrikkel1
   *
   * @param string $matrikkel1
   *
   * @return Salgsomraade
   */
  public function setMatrikkel1($matrikkel1)
  {
    $this->matrikkel1 = $matrikkel1;

    return $this;
  }

  /**
   * Get matrikkel1
   *
   * @return string
   */
  public function getMatrikkel1()
  {
    return $this->matrikkel1;
  }

  /**
   * Set matrikkel2
   *
   * @param string $matrikkel2
   *
   * @return Salgsomraade
   */
  public function setMatrikkel2($matrikkel2)
  {
    $this->matrikkel2 = $matrikkel2;

    return $this;
  }

  /**
   * Get matrikkel2
   *
   * @return string
   */
  public function getMatrikkel2()
  {
    return $this->matrikkel2;
  }

  /**
   * Set ejerlav
   *
   * @param string $ejerlav
   *
   * @return Salgsomraade
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
   * Set vej
   *
   * @param string $vej
   *
   * @return Salgsomraade
   */
  public function setVej($vej)
  {
    $this->vej = $vej;

    return $this;
  }

  /**
   * Get vej
   *
   * @return string
   */
  public function getVej()
  {
    return $this->vej;
  }

  /**
   * Set gisurl
   *
   * @param string $gisurl
   *
   * @return Salgsomraade
   */
  public function setGisurl($gisurl)
  {
    $this->gisurl = $gisurl;

    return $this;
  }

  /**
   * Get gisurl
   *
   * @return string
   */
  public function getGisurl()
  {
    return $this->gisurl;
  }

  /**
   * Set tilsluttet
   *
   * @param string $tilsluttet
   *
   * @return Salgsomraade
   */
  public function setTilsluttet($tilsluttet)
  {
    $this->tilsluttet = $tilsluttet;

    return $this;
  }

  /**
   * Get tilsluttet
   *
   * @return string
   */
  public function getTilsluttet()
  {
    return $this->tilsluttet;
  }

  /**
   * Set sagsnr
   *
   * @param string $sagsnr
   *
   * @return Salgsomraade
   */
  public function setSagsnr($sagsnr)
  {
    $this->sagsnr = $sagsnr;

    return $this;
  }

  /**
   * Get sagsnr
   *
   * @return string
   */
  public function getSagsnr()
  {
    return $this->sagsnr;
  }

  /**
   * Set lploebenummer
   *
   * @param integer $lploebenummer
   *
   * @return Salgsomraade
   */
  public function setLploebenummer($lploebenummer)
  {
    $this->lploebenummer = $lploebenummer;

    return $this;
  }

  /**
   * Get lploebenummer
   *
   * @return integer
   */
  public function getLploebenummer()
  {
    return $this->lploebenummer;
  }

  /**
   * Set landinspektorid
   *
   * @param \AppBundle\Entity\Landinspektoer $landinspektorid
   *
   * @return Salgsomraade
   */
  public function setLandinspektorid(\AppBundle\Entity\Landinspektoer $landinspektorid = null)
  {
    $this->landinspektorid = $landinspektorid;

    return $this;
  }

  /**
   * Get landinspektorid
   *
   * @return \AppBundle\Entity\Landinspektoer
   */
  public function getLandinspektorid()
  {
    return $this->landinspektorid;
  }

  /**
   * Set delomraadeid
   *
   * @param \AppBundle\Entity\Delomraade $delomraadeid
   *
   * @return Salgsomraade
   */
  public function setDelomraadeid(\AppBundle\Entity\Delomraade $delomraadeid = null)
  {
    $this->delomraadeid = $delomraadeid;

    return $this;
  }

  /**
   * Get delomraadeid
   *
   * @return \AppBundle\Entity\Delomraade
   */
  public function getDelomraadeid()
  {
    return $this->delomraadeid;
  }

  /**
   * Set lokalplanid
   *
   * @param \AppBundle\Entity\Lokalplan $lokalplanid
   *
   * @return Salgsomraade
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

  /**
   * Set postbyid
   *
   * @param \AppBundle\Entity\Postby $postbyid
   *
   * @return Salgsomraade
   */
  public function setPostbyid(\AppBundle\Entity\Postby $postbyid = null)
  {
    $this->postbyid = $postbyid;

    return $this;
  }

  /**
   * Get postbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getPostbyid()
  {
    return $this->postbyid;
  }

  public function __toString()
  {
    return $this->titel;
  }
}
