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
   * @var string
   *
   * @ORM\Column(name="titel", type="string", length=255, nullable=false)
   */
  private $titel;

  /**
   * @var string
   *
   * @ORM\Column(name="type", type="string", length=30, nullable=false)
   */
  private $type;

  /**
   * @var string
   *
   * @ORM\Column(name="matrikkel1", type="string", length=20, nullable=true)
   */
  private $matrikkel1;

  /**
   * @var string
   *
   * @ORM\Column(name="matrikkel2", type="string", length=20, nullable=true)
   */
  private $matrikkel2;

  /**
   * @var string
   *
   * @ORM\Column(name="ejerlav", type="string", length=60, nullable=true)
   */
  private $ejerlav;

  /**
   * @var string
   *
   * @ORM\Column(name="vej", type="string", length=60, nullable=true)
   */
  private $vej;

  /**
   * @var string
   *
   * @ORM\Column(name="gisUrl", type="string", length=255, nullable=true)
   */
  private $gisurl;

  /**
   * @var string
   *
   * @ORM\Column(name="tilsluttet", type="string", length=50, nullable=true)
   */
  private $tilsluttet;

  /**
   * @var string
   *
   * @ORM\Column(name="sagsNr", type="string", length=50, nullable=true)
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
  private $landinspektoer;

  /**
   * @var \Delomraade
   *
   * @ORM\ManyToOne(targetEntity="Delomraade")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="delomraadeId", referencedColumnName="id")
   * })
   */
  private $delomraade;

  /**
   * @var \Lokalplan
   *
   * @ORM\ManyToOne(targetEntity="Lokalplan")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="lokalplanId", referencedColumnName="id")
   * })
   */
  private $lokalplan;

  /**
   * @var \Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="postById", referencedColumnName="id")
   * })
   */
  private $postby;

  /**
   * @var \CrEOF\Spatial\DBAL\Types\Geography
   *
   * @ORM\Column(name="SP_GEOMETRY", type="geometry", nullable=true)
   */
  private $sp_geometry;

  /**
   * @var integer
   *
   * @ORM\Column(name="srid", type="integer", nullable=true)
   */
  private $srid;

  /**
   * @var string
   *
   * @ORM\Column(name="MI_STYLE", type="string", length=255, nullable=true)
   */
  private $miStyle;

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
   * @param \AppBundle\Entity\Landinspektoer $landinspektoer
   *
   * @return Salgsomraade
   */
  public function setLandinspektoer(\AppBundle\Entity\Landinspektoer $landinspektoer = null)
  {
    $this->landinspektoer = $landinspektoer;

    return $this;
  }

  /**
   * Get landinspektorid
   *
   * @return \AppBundle\Entity\Landinspektoer
   */
  public function getLandinspektoer()
  {
    return $this->landinspektoer;
  }

  /**
   * Set delomraadeid
   *
   * @param \AppBundle\Entity\Delomraade $delomraade
   *
   * @return Salgsomraade
   */
  public function setDelomraade(\AppBundle\Entity\Delomraade $delomraade = null)
  {
    $this->delomraade = $delomraade;

    return $this;
  }

  /**
   * Get delomraadeid
   *
   * @return \AppBundle\Entity\Delomraade
   */
  public function getDelomraade()
  {
    return $this->delomraade;
  }

  /**
   * Set lokalplanid
   *
   * @param \AppBundle\Entity\Lokalplan $lokalplan
   *
   * @return Salgsomraade
   */
  public function setLokalplan(\AppBundle\Entity\Lokalplan $lokalplan = null)
  {
    $this->lokalplan = $lokalplan;

    return $this;
  }

  /**
   * Get lokalplanid
   *
   * @return \AppBundle\Entity\Lokalplan
   */
  public function getLokalplan()
  {
    return $this->lokalplan;
  }

  /**
   * Set postbyid
   *
   * @param \AppBundle\Entity\Postby $postby
   *
   * @return Salgsomraade
   */
  public function setPostby(\AppBundle\Entity\Postby $postby = null)
  {
    $this->postby = $postby;

    return $this;
  }

  /**
   * Get postbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getPostby()
  {
    return $this->postby;
  }

  /**
   * @return \CrEOF\Spatial\DBAL\Types\Geography
   */
  public function getSpGeometry()
  {
    return $this->sp_geometry;
  }

  /**
   * @param \CrEOF\Spatial\DBAL\Types\Geography $sp_geometry
   */
  public function setSpGeometry($sp_geometry)
  {
    $this->sp_geometry = $sp_geometry;
  }

  /**
   * @return int
   */
  public function getSrid()
  {
    return $this->srid;
  }

  /**
   * @param int $srid
   */
  public function setSrid(int $srid)
  {
    $this->srid = $srid;
  }

  public function __toString()
  {
    return $this->titel;
  }
}
