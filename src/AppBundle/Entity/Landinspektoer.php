<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Landinspektoer
 *
 * @ORM\Table(name="Landinspektoer", indexes={
 *   @ORM\Index(name="search_Landinspektoer_active", columns={"active"}),
 *   @ORM\Index(name="search_Landinspektoer_navn", columns={"navn"}),
 *   @ORM\Index(name="search_Landinspektoer_email", columns={"email"}),
 *   @ORM\Index(name="search_Landinspektoer_telefon", columns={"telefon"})
 * })
 * @ORM\Entity
 *
 * @Gedmo\Loggable
 */
class Landinspektoer
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
   * @ORM\Column(name="adresse", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $adresse;

  /**
   * @var string
   *
   * @ORM\Column(name="email", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $email;

  /**
   * @var string
   *
   * @ORM\Column(name="mobil", type="string", length=20, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $mobil;

  /**
   * @var string
   *
   * @ORM\Column(name="navn", type="string", length=100, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $navn;

  /**
   * @var string
   *
   * @ORM\Column(name="notat", type="text", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $notat;

  /**
   * @var string
   *
   * @ORM\Column(name="telefon", type="string", length=20, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $telefon;

  /**
   * @var \Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="postbyId", referencedColumnName="id")
   * })
   * @ORM\OrderBy({"postalcode" = "ASC"})
   *
   * @Gedmo\Versioned
   */
  private $postby;

  /**
   * @var integer
   *
   * @ORM\Column(name="active", type="boolean", nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $active;


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
   * Set adresse
   *
   * @param string $adresse
   *
   * @return Landinspektoer
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
   * Set email
   *
   * @param string $email
   *
   * @return Landinspektoer
   */
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get email
   *
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set mobil
   *
   * @param string $mobil
   *
   * @return Landinspektoer
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
   * Set navn
   *
   * @param string $navn
   *
   * @return Landinspektoer
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
   * Set notat
   *
   * @param string $notat
   *
   * @return Landinspektoer
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
   * Set telefon
   *
   * @param string $telefon
   *
   * @return Landinspektoer
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
   * Set postbyid
   *
   * @param \AppBundle\Entity\Postby $postby
   *
   * @return Grund
   */
  public function setPostby($postby)
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
   * Set active
   *
   * @param integer $active
   *
   * @return Landinspektoer
   */
  public function setActive($active)
  {
    $this->active = $active;

    return $this;
  }

  /**
   * Get active
   *
   * @return integer
   */
  public function getActive()
  {
    return $this->active;
  }

  public function __toString()
  {
    return $this->navn;
  }
}
