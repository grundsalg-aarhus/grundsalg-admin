<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Interessent
 *
 * @ORM\Table(name="Interessent", indexes={
 *   @ORM\Index(name="fk_Interessent_koeberPostById", columns={"koeberPostById"}),
 *   @ORM\Index(name="fk_Interessent_medKoeberPostById", columns={"medKoeberPostById"}),
 *
 *   @ORM\Index(name="search_Interessent_koeberNavn", columns={"koeberNavn"}),
 *   @ORM\Index(name="search_Interessent_koeberEmail", columns={"koeberEmail"}),
 *   @ORM\Index(name="search_Interessent_koeberLand", columns={"koeberLand"})
 * })
 * @ORM\Entity
 */
class Interessent
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
   * @ORM\Column(name="koeberNavn", type="string", length=255, nullable=true)
   */
  private $koeberNavn;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberAdresse", type="string", length=120, nullable=true)
   */
  private $koeberAdresse;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberLand", type="string", length=50, nullable=true)
   */
  private $koeberLand;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberTelefon", type="string", length=50, nullable=true)
   */
  private $koeberTelefon;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberMobil", type="string", length=50, nullable=true)
   */
  private $koeberMobil;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberEmail", type="string", length=120, nullable=true)
   */
  private $koeberEmail;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberNavn", type="string", length=255, nullable=true)
   */
  private $medkoeberNavn;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberAdresse", type="string", length=120, nullable=true)
   */
  private $medkoeberAdresse;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberLand", type="string", length=50, nullable=true)
   */
  private $medkoeberLand;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberTelefon", type="string", length=50, nullable=true)
   */
  private $medkoeberTelefon;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberMobil", type="string", length=50, nullable=true)
   */
  private $medkoeberMobil;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberEmail", type="string", length=120, nullable=true)
   */
  private $medkoeberEmail;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberNotat", type="text", nullable=true)
   */
  private $koeberNotat;

  /**
   * @var \AppBundle\Entity\Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="medKoeberPostById", referencedColumnName="id")
   * })
   */
  private $medkoeberPostby;

  /**
   * @var \AppBundle\Entity\Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="koeberPostById", referencedColumnName="id")
   * })
   */
  private $koeberPostby;

  /**
   * @var \AppBundle\Entity\Reservation
   *
   * @OneToMany(targetEntity="Reservation", mappedBy="interessent")
   */
  private $reservationer;

  public function __construct() {
    $this->reservationer = new ArrayCollection();
  }

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
   * @param string $koeberNavn
   *
   * @return Grund
   */
  public function setKoeberNavn($koeberNavn) {
    $this->koeberNavn = $koeberNavn;

    return $this;
  }

  /**
   * Get navn
   *
   * @return string
   */
  public function getKoeberNavn() {
    return $this->koeberNavn;
  }

  /**
   * Set adresse
   *
   * @param string $koeberAdresse
   *
   * @return Grund
   */
  public function setKoeberAdresse($koeberAdresse) {
    $this->koeberAdresse = $koeberAdresse;

    return $this;
  }

  /**
   * Get adresse
   *
   * @return string
   */
  public function getKoeberAdresse() {
    return $this->koeberAdresse;
  }

  /**
   * Set land
   *
   * @param string $koeberLand
   *
   * @return Grund
   */
  public function setKoeberLand($koeberLand) {
    $this->koeberLand = $koeberLand;

    return $this;
  }

  /**
   * Get land
   *
   * @return string
   */
  public function getKoeberLand() {
    return $this->koeberLand;
  }

  /**
   * Set telefon
   *
   * @param string $koeberTelefon
   *
   * @return Grund
   */
  public function setKoeberTelefon($koeberTelefon) {
    $this->koeberTelefon = $koeberTelefon;

    return $this;
  }

  /**
   * Get telefon
   *
   * @return string
   */
  public function getKoeberTelefon() {
    return $this->koeberTelefon;
  }

  /**
   * Set mobil
   *
   * @param string $koeberMobil
   *
   * @return Grund
   */
  public function setKoeberMobil($koeberMobil) {
    $this->koeberMobil = $koeberMobil;

    return $this;
  }

  /**
   * Get mobil
   *
   * @return string
   */
  public function getKoeberMobil() {
    return $this->koeberMobil;
  }

  /**
   * Set koeberemail
   *
   * @param string $koeberEmail
   *
   * @return Grund
   */
  public function setKoeberEmail($koeberEmail) {
    $this->koeberEmail = $koeberEmail;

    return $this;
  }

  /**
   * Get koeberemail
   *
   * @return string
   */
  public function getKoeberEmail() {
    return $this->koeberEmail;
  }

  /**
   * Set navn1
   *
   * @param string $medkoeberNavn
   *
   * @return Grund
   */
  public function setMedkoeberNavn($medkoeberNavn) {
    $this->medkoeberNavn = $medkoeberNavn;

    return $this;
  }

  /**
   * Get navn1
   *
   * @return string
   */
  public function getMedkoeberNavn() {
    return $this->medkoeberNavn;
  }

  /**
   * Set adresse1
   *
   * @param string $medkoeberAdresse
   *
   * @return Grund
   */
  public function setMedkoeberAdresse($medkoeberAdresse) {
    $this->medkoeberAdresse = $medkoeberAdresse;

    return $this;
  }

  /**
   * Get adresse1
   *
   * @return string
   */
  public function getMedkoeberAdresse() {
    return $this->medkoeberAdresse;
  }

  /**
   * Set land1
   *
   * @param string $medkoeberLand
   *
   * @return Grund
   */
  public function setMedkoeberLand($medkoeberLand) {
    $this->medkoeberLand = $medkoeberLand;

    return $this;
  }

  /**
   * Get land1
   *
   * @return string
   */
  public function getMedkoeberLand() {
    return $this->medkoeberLand;
  }

  /**
   * Set telefon1
   *
   * @param string $medkoeberTelefon
   *
   * @return Grund
   */
  public function setMedkoeberTelefon($medkoeberTelefon) {
    $this->medkoeberTelefon = $medkoeberTelefon;

    return $this;
  }

  /**
   * Get telefon1
   *
   * @return string
   */
  public function getMedkoeberTelefon() {
    return $this->medkoeberTelefon;
  }

  /**
   * Set mobil1
   *
   * @param string $medkoeberMobil
   *
   * @return Grund
   */
  public function setMedkoeberMobil($medkoeberMobil) {
    $this->medkoeberMobil = $medkoeberMobil;

    return $this;
  }

  /**
   * Get mobil1
   *
   * @return string
   */
  public function getMedkoeberMobil() {
    return $this->medkoeberMobil;
  }

  /**
   * Set medkoeberemail
   *
   * @param string $medkoeberEmail
   *
   * @return Grund
   */
  public function setMedkoeberEmail($medkoeberEmail) {
    $this->medkoeberEmail = $medkoeberEmail;

    return $this;
  }

  /**
   * Get medkoeberemail
   *
   * @return string
   */
  public function getMedkoeberEmail() {
    return $this->medkoeberEmail;
  }

  /**
   * Set notat
   *
   * @param string $koeberNotat
   *
   * @return Grund
   */
  public function setKoeberNotat($koeberNotat) {
    $this->koeberNotat = $koeberNotat;

    return $this;
  }

  /**
   * Get notat
   *
   * @return string
   */
  public function getKoeberNotat() {
    return $this->koeberNotat;
  }

  /**
   * Set medkoeberpostbyid
   *
   * @param \AppBundle\Entity\Postby $medkoeberPostby
   *
   * @return Grund
   */
  public function setMedkoeberPostby(\AppBundle\Entity\Postby $medkoeberPostby = NULL) {
    $this->medkoeberPostby = $medkoeberPostby;

    return $this;
  }

  /**
   * Get medkoeberpostbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getMedkoeberPostby() {
    return $this->medkoeberPostby;
  }

  /**
   * Set koeberpostbyid
   *
   * @param \AppBundle\Entity\Postby $koeberPostby
   *
   * @return Grund
   */
  public function setKoeberPostby(\AppBundle\Entity\Postby $koeberPostby = NULL) {
    $this->koeberPostby = $koeberPostby;

    return $this;
  }

  /**
   * Get koeberpostbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getKoeberPostby() {
    return $this->koeberPostby;
  }

  public function __toString()
  {
    return $this->koeberNotat . " / " . $this->medkoeberNavn;
  }

  /**
   * @return mixed
   */
  public function getReservationer() {
    return $this->reservationer;
  }

  /**
   * @param mixed $reservationer
   */
  public function setReservationer($reservationer) {
    $this->reservationer = $reservationer;
  }

}
