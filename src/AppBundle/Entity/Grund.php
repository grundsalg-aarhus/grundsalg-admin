<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Grund
 *
 * @ORM\Table(name="Grund", indexes={@ORM\Index(name="fk_Grund_lokalsamfundId", columns={"lokalsamfundId"}), @ORM\Index(name="fk_Grund_salgsomraadeId", columns={"salgsomraadeId"}), @ORM\Index(name="fk_Grund_koeberPostById", columns={"koeberPostById"}), @ORM\Index(name="fk_Grund_medKoeberPostById", columns={"medKoeberPostById"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GrundRepository")
 */
class Grund
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
     * @ORM\Column(name="status", type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="salgStatus", type="string", length=50, nullable=true)
     */
    private $salgstatus;

    /**
     * @var string
     *
     * @ORM\Column(name="gid", type="string", length=50, nullable=true)
     */
    private $gid;

    /**
     * @var string
     *
     * @ORM\Column(name="grundType", type="string", length=50, nullable=true)
     */
    private $grundtype;

    /**
     * @var string
     *
     * @ORM\Column(name="mnr", type="text", nullable=true)
     */
    private $mnr;

    /**
     * @var string
     *
     * @ORM\Column(name="mnr2", type="text", nullable=true)
     */
    private $mnr2;

    /**
     * @var string
     *
     * @ORM\Column(name="delAreal", type="text", nullable=true)
     */
    private $delareal;

    /**
     * @var string
     *
     * @ORM\Column(name="ejerlav", type="text", nullable=true)
     */
    private $ejerlav;

    /**
     * @var string
     *
     * @ORM\Column(name="vej", type="text", nullable=true)
     */
    private $vej;

    /**
     * @var string
     *
     * @ORM\Column(name="husNummer", type="text", nullable=true)
     */
    private $husnummer;

    /**
     * @var string
     *
     * @ORM\Column(name="bogstav", type="text", nullable=true)
     */
    private $bogstav;

    /**
     * @var \Postby
     *
     * @ORM\ManyToOne(targetEntity="Postby")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="postbyId", referencedColumnName="id")
     * })
     */
    private $postbyid;

    /**
     * @var string
     *
     * @ORM\Column(name="urlGIS", type="text", nullable=true)
     */
    private $urlgis;

    /**
     * @var string
     *
     * @ORM\Column(name="salgsType", type="text", nullable=true)
     */
    private $salgstype;

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
     * @var string
     *
     * @ORM\Column(name="annonceresEj", type="string", length=50, nullable=true)
     */
    private $annonceresej;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datoAnnonce", type="date", nullable=true)
     */
    private $datoannonce;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datoAnnonce1", type="date", nullable=true)
     */
    private $datoannonce1;

    /**
     * @var string
     *
     * @ORM\Column(name="tilsluttet", type="text", nullable=true)
     */
    private $tilsluttet;

    /**
     * @var float
     *
     * @ORM\Column(name="maxEtageM2", type="float", precision=10, scale=0, nullable=true)
     */
    private $maxetagem2;

    /**
     * @var float
     *
     * @ORM\Column(name="areal", type="float", precision=10, scale=0, nullable=true)
     */
    private $areal;

    /**
     * @var float
     *
     * @ORM\Column(name="arealVej", type="float", precision=10, scale=0, nullable=true)
     */
    private $arealvej;

    /**
     * @var float
     *
     * @ORM\Column(name="arealKotelet", type="float", precision=10, scale=0, nullable=true)
     */
    private $arealkotelet;

    /**
     * @var float
     *
     * @ORM\Column(name="bruttoAreal", type="float", precision=10, scale=0, nullable=true)
     */
    private $bruttoareal;

    /**
     * @var float
     *
     * @ORM\Column(name="prisM2", type="float", precision=10, scale=0, nullable=true)
     */
    private $prism2;

    /**
     * @var float
     *
     * @ORM\Column(name="prisFoerKorrektion", type="float", precision=10, scale=0, nullable=true)
     */
    private $prisfoerkorrektion;

    /**
     * @var string
     *
     * @ORM\Column(name="prisKorrektion1", type="text", nullable=true)
     */
    private $priskorrektion1;

    /**
     * @var float
     *
     * @ORM\Column(name="antalKorr1", type="float", precision=10, scale=0, nullable=true)
     */
    private $antalkorr1;

    /**
     * @var float
     *
     * @ORM\Column(name="aKrKorr1", type="float", precision=10, scale=0, nullable=true)
     */
    private $akrkorr1;

    /**
     * @var float
     *
     * @ORM\Column(name="prisKoor1", type="float", precision=10, scale=0, nullable=true)
     */
    private $priskoor1;

    /**
     * @var string
     *
     * @ORM\Column(name="prisKorrektion2", type="text", nullable=true)
     */
    private $priskorrektion2;

    /**
     * @var float
     *
     * @ORM\Column(name="antalKorr2", type="float", precision=10, scale=0, nullable=true)
     */
    private $antalkorr2;

    /**
     * @var float
     *
     * @ORM\Column(name="aKrKorr2", type="float", precision=10, scale=0, nullable=true)
     */
    private $akrkorr2;

    /**
     * @var float
     *
     * @ORM\Column(name="prisKoor2", type="float", precision=10, scale=0, nullable=true)
     */
    private $priskoor2;

    /**
     * @var string
     *
     * @ORM\Column(name="prisKorrektion3", type="text", nullable=true)
     */
    private $priskorrektion3;

    /**
     * @var float
     *
     * @ORM\Column(name="antalKorr3", type="float", precision=10, scale=0, nullable=true)
     */
    private $antalkorr3;

    /**
     * @var float
     *
     * @ORM\Column(name="aKrKorr3", type="float", precision=10, scale=0, nullable=true)
     */
    private $akrkorr3;

    /**
     * @var float
     *
     * @ORM\Column(name="prisKoor3", type="float", precision=10, scale=0, nullable=true)
     */
    private $priskoor3;

    /**
     * @var float
     *
     * @ORM\Column(name="pris", type="float", precision=10, scale=0, nullable=true)
     */
    private $pris;

    /**
     * @var float
     *
     * @ORM\Column(name="fastPris", type="float", precision=10, scale=0, nullable=true)
     */
    private $fastpris;

    /**
     * @var float
     *
     * @ORM\Column(name="minBud", type="float", precision=10, scale=0, nullable=true)
     */
    private $minbud;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="landInspektoerId", type="string", length=50, nullable=true)
     */
    private $landinspektoerid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="resStart", type="date", nullable=true)
     */
    private $resstart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tilbudStart", type="date", nullable=true)
     */
    private $tilbudstart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="accept", type="date", nullable=true)
     */
    private $accept;

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
     * @ORM\Column(name="resSlut", type="date", nullable=true)
     */
    private $resslut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tilbudSlut", type="date", nullable=true)
     */
    private $tilbudslut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="overtagelse", type="date", nullable=true)
     */
    private $overtagelse;

    /**
     * @var float
     *
     * @ORM\Column(name="antagetBud", type="float", precision=10, scale=0, nullable=true)
     */
    private $antagetbud;

    /**
     * @var float
     *
     * @ORM\Column(name="salgsPrisUMoms", type="float", precision=10, scale=0, nullable=true)
     */
    private $salgsprisumoms;

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
     * @var \Lokalsamfund
     *
     * @ORM\ManyToOne(targetEntity="Lokalsamfund")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lokalsamfundId", referencedColumnName="id")
     * })
     */
    private $lokalsamfundid;

    /**
     * @var \Salgsomraade
     *
     * @ORM\ManyToOne(targetEntity="Salgsomraade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="salgsomraadeId", referencedColumnName="id")
     * })
     */
    private $salgsomraadeid;



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
     * Set status
     *
     * @param string $status
     *
     * @return Grund
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
     * Set salgstatus
     *
     * @param string $salgstatus
     *
     * @return Grund
     */
    public function setSalgstatus($salgstatus)
    {
        $this->salgstatus = $salgstatus;

        return $this;
    }

    /**
     * Get salgstatus
     *
     * @return string
     */
    public function getSalgstatus()
    {
        return $this->salgstatus;
    }

    /**
     * Set gid
     *
     * @param string $gid
     *
     * @return Grund
     */
    public function setGid($gid)
    {
        $this->gid = $gid;

        return $this;
    }

    /**
     * Get gid
     *
     * @return string
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * Set grundtype
     *
     * @param string $grundtype
     *
     * @return Grund
     */
    public function setGrundtype($grundtype)
    {
        $this->grundtype = $grundtype;

        return $this;
    }

    /**
     * Get grundtype
     *
     * @return string
     */
    public function getGrundtype()
    {
        return $this->grundtype;
    }

    /**
     * Set mnr
     *
     * @param string $mnr
     *
     * @return Grund
     */
    public function setMnr($mnr)
    {
        $this->mnr = $mnr;

        return $this;
    }

    /**
     * Get mnr
     *
     * @return string
     */
    public function getMnr()
    {
        return $this->mnr;
    }

    /**
     * Set mnr2
     *
     * @param string $mnr2
     *
     * @return Grund
     */
    public function setMnr2($mnr2)
    {
        $this->mnr2 = $mnr2;

        return $this;
    }

    /**
     * Get mnr2
     *
     * @return string
     */
    public function getMnr2()
    {
        return $this->mnr2;
    }

    /**
     * Set delareal
     *
     * @param string $delareal
     *
     * @return Grund
     */
    public function setDelareal($delareal)
    {
        $this->delareal = $delareal;

        return $this;
    }

    /**
     * Get delareal
     *
     * @return string
     */
    public function getDelareal()
    {
        return $this->delareal;
    }

    /**
     * Set ejerlav
     *
     * @param string $ejerlav
     *
     * @return Grund
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
     * @return Grund
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
     * Set husnummer
     *
     * @param string $husnummer
     *
     * @return Grund
     */
    public function setHusnummer($husnummer)
    {
        $this->husnummer = $husnummer;

        return $this;
    }

    /**
     * Get husnummer
     *
     * @return string
     */
    public function getHusnummer()
    {
        return $this->husnummer;
    }

    /**
     * Set bogstav
     *
     * @param string $bogstav
     *
     * @return Grund
     */
    public function setBogstav($bogstav)
    {
        $this->bogstav = $bogstav;

        return $this;
    }

    /**
     * Get bogstav
     *
     * @return string
     */
    public function getBogstav()
    {
        return $this->bogstav;
    }

    /**
     * Set postbyid
     *
     * @param string $postbyid
     *
     * @return Grund
     */
    public function setPostbyid($postbyid)
    {
        $this->postbyid = $postbyid;

        return $this;
    }

    /**
     * Get postbyid
     *
     * @return string
     */
    public function getPostbyid()
    {
        return $this->postbyid;
    }

    /**
     * Set urlgis
     *
     * @param string $urlgis
     *
     * @return Grund
     */
    public function setUrlgis($urlgis)
    {
        $this->urlgis = $urlgis;

        return $this;
    }

    /**
     * Get urlgis
     *
     * @return string
     */
    public function getUrlgis()
    {
        return $this->urlgis;
    }

    /**
     * Set salgstype
     *
     * @param string $salgstype
     *
     * @return Grund
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
     * Set auktionstartdato
     *
     * @param \DateTime $auktionstartdato
     *
     * @return Grund
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
     * @return Grund
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
     * Set annonceresej
     *
     * @param string $annonceresej
     *
     * @return Grund
     */
    public function setAnnonceresej($annonceresej)
    {
        $this->annonceresej = $annonceresej;

        return $this;
    }

    /**
     * Get annonceresej
     *
     * @return string
     */
    public function getAnnonceresej()
    {
        return $this->annonceresej;
    }

    /**
     * Set datoannonce
     *
     * @param \DateTime $datoannonce
     *
     * @return Grund
     */
    public function setDatoannonce($datoannonce)
    {
        $this->datoannonce = $datoannonce;

        return $this;
    }

    /**
     * Get datoannonce
     *
     * @return \DateTime
     */
    public function getDatoannonce()
    {
        return $this->datoannonce;
    }

    /**
     * Set datoannonce1
     *
     * @param \DateTime $datoannonce1
     *
     * @return Grund
     */
    public function setDatoannonce1($datoannonce1)
    {
        $this->datoannonce1 = $datoannonce1;

        return $this;
    }

    /**
     * Get datoannonce1
     *
     * @return \DateTime
     */
    public function getDatoannonce1()
    {
        return $this->datoannonce1;
    }

    /**
     * Set tilsluttet
     *
     * @param string $tilsluttet
     *
     * @return Grund
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
     * Set maxetagem2
     *
     * @param float $maxetagem2
     *
     * @return Grund
     */
    public function setMaxetagem2($maxetagem2)
    {
        $this->maxetagem2 = $maxetagem2;

        return $this;
    }

    /**
     * Get maxetagem2
     *
     * @return float
     */
    public function getMaxetagem2()
    {
        return $this->maxetagem2;
    }

    /**
     * Set areal
     *
     * @param float $areal
     *
     * @return Grund
     */
    public function setAreal($areal)
    {
        $this->areal = $areal;

        return $this;
    }

    /**
     * Get areal
     *
     * @return float
     */
    public function getAreal()
    {
        return $this->areal;
    }

    /**
     * Set arealvej
     *
     * @param float $arealvej
     *
     * @return Grund
     */
    public function setArealvej($arealvej)
    {
        $this->arealvej = $arealvej;

        return $this;
    }

    /**
     * Get arealvej
     *
     * @return float
     */
    public function getArealvej()
    {
        return $this->arealvej;
    }

    /**
     * Set arealkotelet
     *
     * @param float $arealkotelet
     *
     * @return Grund
     */
    public function setArealkotelet($arealkotelet)
    {
        $this->arealkotelet = $arealkotelet;

        return $this;
    }

    /**
     * Get arealkotelet
     *
     * @return float
     */
    public function getArealkotelet()
    {
        return $this->arealkotelet;
    }

    /**
     * Set bruttoareal
     *
     * @param float $bruttoareal
     *
     * @return Grund
     */
    public function setBruttoareal($bruttoareal)
    {
        $this->bruttoareal = $bruttoareal;

        return $this;
    }

    /**
     * Get bruttoareal
     *
     * @return float
     */
    public function getBruttoareal()
    {
        return $this->bruttoareal;
    }

    /**
     * Set prism2
     *
     * @param float $prism2
     *
     * @return Grund
     */
    public function setPrism2($prism2)
    {
        $this->prism2 = $prism2;

        return $this;
    }

    /**
     * Get prism2
     *
     * @return float
     */
    public function getPrism2()
    {
        return $this->prism2;
    }

    /**
     * Set prisfoerkorrektion
     *
     * @param float $prisfoerkorrektion
     *
     * @return Grund
     */
    public function setPrisfoerkorrektion($prisfoerkorrektion)
    {
        $this->prisfoerkorrektion = $prisfoerkorrektion;

        return $this;
    }

    /**
     * Get prisfoerkorrektion
     *
     * @return float
     */
    public function getPrisfoerkorrektion()
    {
        return $this->prisfoerkorrektion;
    }

    /**
     * Set priskorrektion1
     *
     * @param string $priskorrektion1
     *
     * @return Grund
     */
    public function setPriskorrektion1($priskorrektion1)
    {
        $this->priskorrektion1 = $priskorrektion1;

        return $this;
    }

    /**
     * Get priskorrektion1
     *
     * @return string
     */
    public function getPriskorrektion1()
    {
        return $this->priskorrektion1;
    }

    /**
     * Set antalkorr1
     *
     * @param float $antalkorr1
     *
     * @return Grund
     */
    public function setAntalkorr1($antalkorr1)
    {
        $this->antalkorr1 = $antalkorr1;

        return $this;
    }

    /**
     * Get antalkorr1
     *
     * @return float
     */
    public function getAntalkorr1()
    {
        return $this->antalkorr1;
    }

    /**
     * Set akrkorr1
     *
     * @param float $akrkorr1
     *
     * @return Grund
     */
    public function setAkrkorr1($akrkorr1)
    {
        $this->akrkorr1 = $akrkorr1;

        return $this;
    }

    /**
     * Get akrkorr1
     *
     * @return float
     */
    public function getAkrkorr1()
    {
        return $this->akrkorr1;
    }

    /**
     * Set priskoor1
     *
     * @param float $priskoor1
     *
     * @return Grund
     */
    public function setPriskoor1($priskoor1)
    {
        $this->priskoor1 = $priskoor1;

        return $this;
    }

    /**
     * Get priskoor1
     *
     * @return float
     */
    public function getPriskoor1()
    {
        return $this->priskoor1;
    }

    /**
     * Set priskorrektion2
     *
     * @param string $priskorrektion2
     *
     * @return Grund
     */
    public function setPriskorrektion2($priskorrektion2)
    {
        $this->priskorrektion2 = $priskorrektion2;

        return $this;
    }

    /**
     * Get priskorrektion2
     *
     * @return string
     */
    public function getPriskorrektion2()
    {
        return $this->priskorrektion2;
    }

    /**
     * Set antalkorr2
     *
     * @param float $antalkorr2
     *
     * @return Grund
     */
    public function setAntalkorr2($antalkorr2)
    {
        $this->antalkorr2 = $antalkorr2;

        return $this;
    }

    /**
     * Get antalkorr2
     *
     * @return float
     */
    public function getAntalkorr2()
    {
        return $this->antalkorr2;
    }

    /**
     * Set akrkorr2
     *
     * @param float $akrkorr2
     *
     * @return Grund
     */
    public function setAkrkorr2($akrkorr2)
    {
        $this->akrkorr2 = $akrkorr2;

        return $this;
    }

    /**
     * Get akrkorr2
     *
     * @return float
     */
    public function getAkrkorr2()
    {
        return $this->akrkorr2;
    }

    /**
     * Set priskoor2
     *
     * @param float $priskoor2
     *
     * @return Grund
     */
    public function setPriskoor2($priskoor2)
    {
        $this->priskoor2 = $priskoor2;

        return $this;
    }

    /**
     * Get priskoor2
     *
     * @return float
     */
    public function getPriskoor2()
    {
        return $this->priskoor2;
    }

    /**
     * Set priskorrektion3
     *
     * @param string $priskorrektion3
     *
     * @return Grund
     */
    public function setPriskorrektion3($priskorrektion3)
    {
        $this->priskorrektion3 = $priskorrektion3;

        return $this;
    }

    /**
     * Get priskorrektion3
     *
     * @return string
     */
    public function getPriskorrektion3()
    {
        return $this->priskorrektion3;
    }

    /**
     * Set antalkorr3
     *
     * @param float $antalkorr3
     *
     * @return Grund
     */
    public function setAntalkorr3($antalkorr3)
    {
        $this->antalkorr3 = $antalkorr3;

        return $this;
    }

    /**
     * Get antalkorr3
     *
     * @return float
     */
    public function getAntalkorr3()
    {
        return $this->antalkorr3;
    }

    /**
     * Set akrkorr3
     *
     * @param float $akrkorr3
     *
     * @return Grund
     */
    public function setAkrkorr3($akrkorr3)
    {
        $this->akrkorr3 = $akrkorr3;

        return $this;
    }

    /**
     * Get akrkorr3
     *
     * @return float
     */
    public function getAkrkorr3()
    {
        return $this->akrkorr3;
    }

    /**
     * Set priskoor3
     *
     * @param float $priskoor3
     *
     * @return Grund
     */
    public function setPriskoor3($priskoor3)
    {
        $this->priskoor3 = $priskoor3;

        return $this;
    }

    /**
     * Get priskoor3
     *
     * @return float
     */
    public function getPriskoor3()
    {
        return $this->priskoor3;
    }

    /**
     * Set pris
     *
     * @param float $pris
     *
     * @return Grund
     */
    public function setPris($pris)
    {
        $this->pris = $pris;

        return $this;
    }

    /**
     * Get pris
     *
     * @return float
     */
    public function getPris()
    {
        return $this->pris;
    }

    /**
     * Set fastpris
     *
     * @param float $fastpris
     *
     * @return Grund
     */
    public function setFastpris($fastpris)
    {
        $this->fastpris = $fastpris;

        return $this;
    }

    /**
     * Get fastpris
     *
     * @return float
     */
    public function getFastpris()
    {
        return $this->fastpris;
    }

    /**
     * Set minbud
     *
     * @param float $minbud
     *
     * @return Grund
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
     * Set note
     *
     * @param string $note
     *
     * @return Grund
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set landinspektoerid
     *
     * @param string $landinspektoerid
     *
     * @return Grund
     */
    public function setLandinspektoerid($landinspektoerid)
    {
        $this->landinspektoerid = $landinspektoerid;

        return $this;
    }

    /**
     * Get landinspektoerid
     *
     * @return string
     */
    public function getLandinspektoerid()
    {
        return $this->landinspektoerid;
    }

    /**
     * Set resstart
     *
     * @param \DateTime $resstart
     *
     * @return Grund
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
     * Set tilbudstart
     *
     * @param \DateTime $tilbudstart
     *
     * @return Grund
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
     * Set accept
     *
     * @param \DateTime $accept
     *
     * @return Grund
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
     * Set skoederekv
     *
     * @param \DateTime $skoederekv
     *
     * @return Grund
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
     * @return Grund
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
     * Set resslut
     *
     * @param \DateTime $resslut
     *
     * @return Grund
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
     * Set tilbudslut
     *
     * @param \DateTime $tilbudslut
     *
     * @return Grund
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
     * Set overtagelse
     *
     * @param \DateTime $overtagelse
     *
     * @return Grund
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
     * Set antagetbud
     *
     * @param float $antagetbud
     *
     * @return Grund
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
     * Set salgsprisumoms
     *
     * @param float $salgsprisumoms
     *
     * @return Grund
     */
    public function setSalgsprisumoms($salgsprisumoms)
    {
        $this->salgsprisumoms = $salgsprisumoms;

        return $this;
    }

    /**
     * Get salgsprisumoms
     *
     * @return float
     */
    public function getSalgsprisumoms()
    {
        return $this->salgsprisumoms;
    }

    /**
     * Set navn
     *
     * @param string $navn
     *
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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
     * @return Grund
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

    /**
     * Set lokalsamfundid
     *
     * @param \AppBundle\Entity\Lokalsamfund $lokalsamfundid
     *
     * @return Grund
     */
    public function setLokalsamfundid(\AppBundle\Entity\Lokalsamfund $lokalsamfundid = null)
    {
        $this->lokalsamfundid = $lokalsamfundid;

        return $this;
    }

    /**
     * Get lokalsamfundid
     *
     * @return \AppBundle\Entity\Lokalsamfund
     */
    public function getLokalsamfundid()
    {
        return $this->lokalsamfundid;
    }

    /**
     * Set salgsomraadeid
     *
     * @param \AppBundle\Entity\Salgsomraade $salgsomraadeid
     *
     * @return Grund
     */
    public function setSalgsomraadeid(\AppBundle\Entity\Salgsomraade $salgsomraadeid = null)
    {
        $this->salgsomraadeid = $salgsomraadeid;

        return $this;
    }

    /**
     * Get salgsomraadeid
     *
     * @return \AppBundle\Entity\Salgsomraade
     */
    public function getSalgsomraadeid()
    {
        return $this->salgsomraadeid;
    }

    public function __toString() {
      return $this->vej . ' ' . $this->husnummer . ($this->postbyid ? ', ' . $this->postbyid : '');
    }
}
