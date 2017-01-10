<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Interessent
 *
 * @ORM\Table(name="Interessent", indexes={@ORM\Index(name="fk_Interessent_koeberPostById", columns={"koeberPostById"}), @ORM\Index(name="fk_Interessent_medKoeberPostById", columns={"medKoeberPostById"})})
 * @ORM\Entity
 */
class Interessent
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
   * @ORM\Column(name="navn", type="string", length=255, nullable=true)
   */
  private $navn;

  /**
   * @var string
   *
   * @ORM\Column(name="adresse", type="string", length=100, nullable=true)
   */
  private $adresse;

  /**
   * @var string
   *
   * @ORM\Column(name="land", type="string", length=50, nullable=true)
   */
  private $land;

  /**
   * @var string
   *
   * @ORM\Column(name="telefon", type="string", length=50, nullable=true)
   */
  private $telefon;

  /**
   * @var string
   *
   * @ORM\Column(name="mobil", type="string", length=50, nullable=true)
   */
  private $mobil;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberEmail", type="string", length=120, nullable=true)
   */
  private $koeberemail;

  /**
   * @var string
   *
   * @ORM\Column(name="navn1", type="string", length=120, nullable=true)
   */
  private $navn1;

  /**
   * @var string
   *
   * @ORM\Column(name="adresse1", type="string", length=120, nullable=true)
   */
  private $adresse1;

  /**
   * @var string
   *
   * @ORM\Column(name="land1", type="string", length=50, nullable=true)
   */
  private $land1;

  /**
   * @var string
   *
   * @ORM\Column(name="telefon1", type="string", length=50, nullable=true)
   */
  private $telefon1;

  /**
   * @var string
   *
   * @ORM\Column(name="mobil1", type="string", length=50, nullable=true)
   */
  private $mobil1;

  /**
   * @var string
   *
   * @ORM\Column(name="medKoeberEmail", type="string", length=120, nullable=true)
   */
  private $medkoeberemail;

  /**
   * @var string
   *
   * @ORM\Column(name="notat", type="text", nullable=true)
   */
  private $notat;

  /**
   * @var \Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="medKoeberPostById", referencedColumnName="id")
   * })
   */
  private $medkoeberPostby;

  /**
   * @var \Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="koeberPostById", referencedColumnName="id")
   * })
   */
  private $koeberPostby;


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
   * Set navn
   *
   * @param string $navn
   *
   * @return Interessent
   */
  public function setNavn($navn)
  {
    $this->navn = $navn;

    return $this;
  }

  /**
   * Get navn
   *
   * @return string
   */
  public function getNavn()
  {
    return $this->navn;
  }

  /**
   * Set adresse
   *
   * @param string $adresse
   *
   * @return Interessent
   */
  public function setAdresse($adresse)
  {
    $this->adresse = $adresse;

    return $this;
  }

  /**
   * Get adresse
   *
   * @return string
   */
  public function getAdresse()
  {
    return $this->adresse;
  }

  /**
   * Set land
   *
   * @param string $land
   *
   * @return Interessent
   */
  public function setLand($land)
  {
    $this->land = $land;

    return $this;
  }

  /**
   * Get land
   *
   * @return string
   */
  public function getLand()
  {
    return $this->land;
  }

  /**
   * Set telefon
   *
   * @param string $telefon
   *
   * @return Interessent
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
   * Set mobil
   *
   * @param string $mobil
   *
   * @return Interessent
   */
  public function setMobil($mobil)
  {
    $this->mobil = $mobil;

    return $this;
  }

  /**
   * Get mobil
   *
   * @return string
   */
  public function getMobil()
  {
    return $this->mobil;
  }

  /**
   * Set koeberemail
   *
   * @param string $koeberemail
   *
   * @return Interessent
   */
  public function setKoeberemail($koeberemail)
  {
    $this->koeberemail = $koeberemail;

    return $this;
  }

  /**
   * Get koeberemail
   *
   * @return string
   */
  public function getKoeberemail()
  {
    return $this->koeberemail;
  }

  /**
   * Set navn1
   *
   * @param string $navn1
   *
   * @return Interessent
   */
  public function setNavn1($navn1)
  {
    $this->navn1 = $navn1;

    return $this;
  }

  /**
   * Get navn1
   *
   * @return string
   */
  public function getNavn1()
  {
    return $this->navn1;
  }

  /**
   * Set adresse1
   *
   * @param string $adresse1
   *
   * @return Interessent
   */
  public function setAdresse1($adresse1)
  {
    $this->adresse1 = $adresse1;

    return $this;
  }

  /**
   * Get adresse1
   *
   * @return string
   */
  public function getAdresse1()
  {
    return $this->adresse1;
  }

  /**
   * Set land1
   *
   * @param string $land1
   *
   * @return Interessent
   */
  public function setLand1($land1)
  {
    $this->land1 = $land1;

    return $this;
  }

  /**
   * Get land1
   *
   * @return string
   */
  public function getLand1()
  {
    return $this->land1;
  }

  /**
   * Set telefon1
   *
   * @param string $telefon1
   *
   * @return Interessent
   */
  public function setTelefon1($telefon1)
  {
    $this->telefon1 = $telefon1;

    return $this;
  }

  /**
   * Get telefon1
   *
   * @return string
   */
  public function getTelefon1()
  {
    return $this->telefon1;
  }

  /**
   * Set mobil1
   *
   * @param string $mobil1
   *
   * @return Interessent
   */
  public function setMobil1($mobil1)
  {
    $this->mobil1 = $mobil1;

    return $this;
  }

  /**
   * Get mobil1
   *
   * @return string
   */
  public function getMobil1()
  {
    return $this->mobil1;
  }

  /**
   * Set medkoeberemail
   *
   * @param string $medkoeberemail
   *
   * @return Interessent
   */
  public function setMedkoeberemail($medkoeberemail)
  {
    $this->medkoeberemail = $medkoeberemail;

    return $this;
  }

  /**
   * Get medkoeberemail
   *
   * @return string
   */
  public function getMedkoeberemail()
  {
    return $this->medkoeberemail;
  }

  /**
   * Set notat
   *
   * @param string $notat
   *
   * @return Interessent
   */
  public function setNotat($notat)
  {
    $this->notat = $notat;

    return $this;
  }

  /**
   * Get notat
   *
   * @return string
   */
  public function getNotat()
  {
    return $this->notat;
  }

  /**
   * Set medkoeberpostbyid
   *
   * @param \AppBundle\Entity\Postby $medkoeberPostby
   *
   * @return Interessent
   */
  public function setMedkoeberPostby(\AppBundle\Entity\Postby $medkoeberPostby = null)
  {
    $this->medkoeberPostby = $medkoeberPostby;

    return $this;
  }

  /**
   * Get medkoeberpostbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getMedkoeberPostby()
  {
    return $this->medkoeberPostby;
  }

  /**
   * Set koeberpostbyid
   *
   * @param \AppBundle\Entity\Postby $koeberPostby
   *
   * @return Interessent
   */
  public function setKoeberPostby(\AppBundle\Entity\Postby $koeberPostby = null)
  {
    $this->koeberPostby = $koeberPostby;

    return $this;
  }

  /**
   * Get koeberpostbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getKoeberPostby()
  {
    return $this->koeberPostby;
  }

  public function __toString()
  {
    return $this->navn . " / " . $this->navn1;
  }
}
