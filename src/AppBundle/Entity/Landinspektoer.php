<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Landinspektoer
 *
 * @ORM\Table(name="Landinspektoer")
 * @ORM\Entity
 */
class Landinspektoer
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
     * @ORM\Column(name="adresse", type="text", nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="text", nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mobil", type="text", nullable=false)
     */
    private $mobil;

    /**
     * @var string
     *
     * @ORM\Column(name="navn", type="text", nullable=false)
     */
    private $navn;

    /**
     * @var string
     *
     * @ORM\Column(name="notat", type="text", nullable=false)
     */
    private $notat;

    /**
     * @var string
     *
     * @ORM\Column(name="telefon", type="text", nullable=false)
     */
    private $telefon;

    /**
     * @var integer
     *
     * @ORM\Column(name="postnrId", type="bigint", nullable=false)
     */
    private $postnrid;

    /**
     * @var integer
     *
     * @ORM\Column(name="active", type="integer", nullable=false)
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
     * Set postnrid
     *
     * @param integer $postnrid
     *
     * @return Landinspektoer
     */
    public function setPostnrid($postnrid)
    {
        $this->postnrid = $postnrid;

        return $this;
    }

    /**
     * Get postnrid
     *
     * @return integer
     */
    public function getPostnrid()
    {
        return $this->postnrid;
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
public function __toString() { return $this->navn; }}
