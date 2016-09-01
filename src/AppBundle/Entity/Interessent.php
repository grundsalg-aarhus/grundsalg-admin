<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interessent
 *
 * @ORM\Table(name="Interessent", indexes={@ORM\Index(name="fk_Interessent_koeberPostById", columns={"koeberPostById"}), @ORM\Index(name="fk_Interessent_medKoeberPostById", columns={"medKoeberPostById"})})
 * @ORM\Entity
 */
class Interessent
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
     * @var string
     *
     * @ORM\Column(name="navn", type="text", nullable=true)
     */
    private $navn;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text", nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="land", type="text", nullable=true)
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
     * @ORM\Column(name="koeberEmail", type="text", nullable=true)
     */
    private $koeberemail;

    /**
     * @var string
     *
     * @ORM\Column(name="navn1", type="text", nullable=true)
     */
    private $navn1;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse1", type="text", nullable=true)
     */
    private $adresse1;

    /**
     * @var string
     *
     * @ORM\Column(name="land1", type="text", nullable=true)
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
     * @ORM\Column(name="medKoeberEmail", type="text", nullable=true)
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
    private $medkoeberpostbyid;

    /**
     * @var \Postby
     *
     * @ORM\ManyToOne(targetEntity="Postby")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="koeberPostById", referencedColumnName="id")
     * })
     */
    private $koeberpostbyid;



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
     * Set createdby
     *
     * @param string $createdby
     *
     * @return Interessent
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
     * @return Interessent
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
     * @return Interessent
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
     * @return Interessent
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
     * @param \AppBundle\Entity\Postby $medkoeberpostbyid
     *
     * @return Interessent
     */
    public function setMedkoeberpostbyid(\AppBundle\Entity\Postby $medkoeberpostbyid = null)
    {
        $this->medkoeberpostbyid = $medkoeberpostbyid;

        return $this;
    }

    /**
     * Get medkoeberpostbyid
     *
     * @return \AppBundle\Entity\Postby
     */
    public function getMedkoeberpostbyid()
    {
        return $this->medkoeberpostbyid;
    }

    /**
     * Set koeberpostbyid
     *
     * @param \AppBundle\Entity\Postby $koeberpostbyid
     *
     * @return Interessent
     */
    public function setKoeberpostbyid(\AppBundle\Entity\Postby $koeberpostbyid = null)
    {
        $this->koeberpostbyid = $koeberpostbyid;

        return $this;
    }

    /**
     * Get koeberpostbyid
     *
     * @return \AppBundle\Entity\Postby
     */
    public function getKoeberpostbyid()
    {
        return $this->koeberpostbyid;
    }
public function __toString() { return __CLASS__; }}
