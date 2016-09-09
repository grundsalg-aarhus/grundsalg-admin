<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Salgshistorik
 *
 * @ORM\Table(name="Salgshistorik", indexes={@ORM\Index(name="fk_Salgshistorik_grundId", columns={"grundId"}), @ORM\Index(name="fk_Salgshistorik_koeberPostById", columns={"koeberPostById"}), @ORM\Index(name="fk_Salgshistorik_medKoeberPostById", columns={"medKoeberPostById"})})
 * @ORM\Entity
 */
class Salgshistorik
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
     * @ORM\Column(name="aarsag", type="text", nullable=true)
     */
    private $aarsag;

    /**
     * @var string
     *
     * @ORM\Column(name="salgsType", type="text", nullable=true)
     */
    private $salgstype;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="text", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="resStart", type="date", nullable=true)
     */
    private $resstart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="resSlut", type="date", nullable=true)
     */
    private $resslut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tilbudStart", type="date", nullable=true)
     */
    private $tilbudstart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tilbudSlut", type="date", nullable=true)
     */
    private $tilbudslut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="accept", type="date", nullable=true)
     */
    private $accept;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="overtagelse", type="date", nullable=true)
     */
    private $overtagelse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="skoedeRekv", type="date", nullable=true)
     */
    private $skoederekv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="beloebAnvist", type="date", nullable=true)
     */
    private $beloebanvist;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="auktionStartDato", type="date", nullable=true)
     */
    private $auktionstartdato;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="auktionSlutDato", type="date", nullable=true)
     */
    private $auktionslutdato;

    /**
     * @var float
     *
     * @ORM\Column(name="minBud", type="float", precision=10, scale=0, nullable=true)
     */
    private $minbud;

    /**
     * @var float
     *
     * @ORM\Column(name="antagetBud", type="float", precision=10, scale=0, nullable=true)
     */
    private $antagetbud;

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
     * @var \Grund
     *
     * @ORM\ManyToOne(targetEntity="Grund")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grundId", referencedColumnName="id")
     * })
     */
    private $grundid;

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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * Set aarsag
     *
     * @param string $aarsag
     *
     * @return Salgshistorik
     */
    public function setAarsag($aarsag)
    {
        $this->aarsag = $aarsag;

        return $this;
    }

    /**
     * Get aarsag
     *
     * @return string
     */
    public function getAarsag()
    {
        return $this->aarsag;
    }

    /**
     * Set salgstype
     *
     * @param string $salgstype
     *
     * @return Salgshistorik
     */
    public function setSalgstype($salgstype)
    {
        $this->salgstype = $salgstype;

        return $this;
    }

    /**
     * Get salgstype
     *
     * @return string
     */
    public function getSalgstype()
    {
        return $this->salgstype;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Salgshistorik
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set resstart
     *
     * @param \DateTime $resstart
     *
     * @return Salgshistorik
     */
    public function setResstart($resstart)
    {
        $this->resstart = $resstart;

        return $this;
    }

    /**
     * Get resstart
     *
     * @return \DateTime
     */
    public function getResstart()
    {
        return $this->resstart;
    }

    /**
     * Set resslut
     *
     * @param \DateTime $resslut
     *
     * @return Salgshistorik
     */
    public function setResslut($resslut)
    {
        $this->resslut = $resslut;

        return $this;
    }

    /**
     * Get resslut
     *
     * @return \DateTime
     */
    public function getResslut()
    {
        return $this->resslut;
    }

    /**
     * Set tilbudstart
     *
     * @param \DateTime $tilbudstart
     *
     * @return Salgshistorik
     */
    public function setTilbudstart($tilbudstart)
    {
        $this->tilbudstart = $tilbudstart;

        return $this;
    }

    /**
     * Get tilbudstart
     *
     * @return \DateTime
     */
    public function getTilbudstart()
    {
        return $this->tilbudstart;
    }

    /**
     * Set tilbudslut
     *
     * @param \DateTime $tilbudslut
     *
     * @return Salgshistorik
     */
    public function setTilbudslut($tilbudslut)
    {
        $this->tilbudslut = $tilbudslut;

        return $this;
    }

    /**
     * Get tilbudslut
     *
     * @return \DateTime
     */
    public function getTilbudslut()
    {
        return $this->tilbudslut;
    }

    /**
     * Set accept
     *
     * @param \DateTime $accept
     *
     * @return Salgshistorik
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Get accept
     *
     * @return \DateTime
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Set overtagelse
     *
     * @param \DateTime $overtagelse
     *
     * @return Salgshistorik
     */
    public function setOvertagelse($overtagelse)
    {
        $this->overtagelse = $overtagelse;

        return $this;
    }

    /**
     * Get overtagelse
     *
     * @return \DateTime
     */
    public function getOvertagelse()
    {
        return $this->overtagelse;
    }

    /**
     * Set skoederekv
     *
     * @param \DateTime $skoederekv
     *
     * @return Salgshistorik
     */
    public function setSkoederekv($skoederekv)
    {
        $this->skoederekv = $skoederekv;

        return $this;
    }

    /**
     * Get skoederekv
     *
     * @return \DateTime
     */
    public function getSkoederekv()
    {
        return $this->skoederekv;
    }

    /**
     * Set beloebanvist
     *
     * @param \DateTime $beloebanvist
     *
     * @return Salgshistorik
     */
    public function setBeloebanvist($beloebanvist)
    {
        $this->beloebanvist = $beloebanvist;

        return $this;
    }

    /**
     * Get beloebanvist
     *
     * @return \DateTime
     */
    public function getBeloebanvist()
    {
        return $this->beloebanvist;
    }

    /**
     * Set auktionstartdato
     *
     * @param \DateTime $auktionstartdato
     *
     * @return Salgshistorik
     */
    public function setAuktionstartdato($auktionstartdato)
    {
        $this->auktionstartdato = $auktionstartdato;

        return $this;
    }

    /**
     * Get auktionstartdato
     *
     * @return \DateTime
     */
    public function getAuktionstartdato()
    {
        return $this->auktionstartdato;
    }

    /**
     * Set auktionslutdato
     *
     * @param \DateTime $auktionslutdato
     *
     * @return Salgshistorik
     */
    public function setAuktionslutdato($auktionslutdato)
    {
        $this->auktionslutdato = $auktionslutdato;

        return $this;
    }

    /**
     * Get auktionslutdato
     *
     * @return \DateTime
     */
    public function getAuktionslutdato()
    {
        return $this->auktionslutdato;
    }

    /**
     * Set minbud
     *
     * @param float $minbud
     *
     * @return Salgshistorik
     */
    public function setMinbud($minbud)
    {
        $this->minbud = $minbud;

        return $this;
    }

    /**
     * Get minbud
     *
     * @return float
     */
    public function getMinbud()
    {
        return $this->minbud;
    }

    /**
     * Set antagetbud
     *
     * @param float $antagetbud
     *
     * @return Salgshistorik
     */
    public function setAntagetbud($antagetbud)
    {
        $this->antagetbud = $antagetbud;

        return $this;
    }

    /**
     * Get antagetbud
     *
     * @return float
     */
    public function getAntagetbud()
    {
        return $this->antagetbud;
    }

    /**
     * Set navn
     *
     * @param string $navn
     *
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * @return Salgshistorik
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
     * Set grundid
     *
     * @param \AppBundle\Entity\Grund $grundid
     *
     * @return Salgshistorik
     */
    public function setGrundid(\AppBundle\Entity\Grund $grundid = null)
    {
        $this->grundid = $grundid;

        return $this;
    }

    /**
     * Get grundid
     *
     * @return \AppBundle\Entity\Grund
     */
    public function getGrundid()
    {
        return $this->grundid;
    }

    /**
     * Set koeberpostbyid
     *
     * @param \AppBundle\Entity\Postby $koeberpostbyid
     *
     * @return Salgshistorik
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
