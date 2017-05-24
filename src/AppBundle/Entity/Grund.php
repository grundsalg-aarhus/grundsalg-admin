<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * Grund
 *
 * @ORM\Table(name="Grund", indexes={
 *   @ORM\Index(name="fk_Grund_lokalsamfundId", columns={"lokalsamfundId"}),
 *   @ORM\Index(name="fk_Grund_salgsomraadeId", columns={"salgsomraadeId"}),
 *   @ORM\Index(name="fk_Grund_koeberPostById", columns={"koeberPostById"}),
 *   @ORM\Index(name="fk_Grund_medKoeberPostById", columns={"medKoeberPostById"}),
 *
 *   @ORM\Index(name="search_Grund_vej", columns={"vej"}),
 *   @ORM\Index(name="search_Grund_husnummer", columns={"husnummer"}),
 *   @ORM\Index(name="search_Grund_bogstav", columns={"bogstav"}),
 *   @ORM\Index(name="search_Grund_salgsType", columns={"salgsType"}),
 *   @ORM\Index(name="search_Grund_areal", columns={"areal"}),
 *   @ORM\Index(name="search_Grund_pris", columns={"pris"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GrundRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"GRUND" = "Grund", "COLL" = "GrundCollection"})
 */
class Grund {
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
   * @var GrundStatus
   *
   * @ORM\Column(name="status", type="GrundStatus", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\GrundStatus")
   */
  private $status;

  /**
   * @var GrundSalgStatus
   *
   * @ORM\Column(name="salgStatus", type="GrundSalgStatus", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\GrundSalgStatus")
   */
  private $salgstatus;

  /**
   * @var string
   *
   * @ORM\Column(name="gid", type="string", length=50, nullable=true)
   */
  private $gid;

  /**
   * @var GrundType
   *
   * @ORM\Column(name="type", type="GrundType", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\GrundType")
   */
  private $type;

  /**
   * @var string
   *
   * @ORM\Column(name="mnr", type="string", length=20, nullable=true)
   */
  private $mnr;

  /**
   * @var string
   *
   * @ORM\Column(name="mnr2", type="string", length=20, nullable=true)
   */
  private $mnr2;

  /**
   * @var string
   *
   * @ORM\Column(name="delAreal", type="string", length=60, nullable=true)
   */
  private $delareal;

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
   * @var integer
   *
   * @ORM\Column(name="husNummer", type="integer", nullable=true)
   */
  private $husnummer;

  /**
   * @var string
   *
   * @ORM\Column(name="bogstav", type="string", length=30, nullable=true)
   */
  private $bogstav;

  /**
   * @var \Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby", fetch="EAGER")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="postbyId", referencedColumnName="id")
   * })
   */
  protected $postby;

  /**
   * @var string
   *
   * @ORM\Column(name="urlGIS", type="string", length=255, nullable=true)
   */
  private $urlgis;

  /**
   * @var SalgsType
   *
   * @ORM\Column(name="salgsType", type="SalgsType", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\SalgsType")
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
   * @var boolean
   *
   * @ORM\Column(name="annonceres", type="boolean", nullable=true)
   */
  private $annonceres;

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
   * @var array
   *
   * @ORM\Column(name="tilsluttet", type="array", nullable=true)
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
   * @ORM\Column(name="prisKorrektion1", type="Priskorrektion", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Priskorrektion")
   */
  private $priskorrektion1;

  /**
   * @var integer
   *
   * @ORM\Column(name="antalKorr1", type="integer", nullable=true)
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
   * @ORM\Column(name="prisKorrektion2", type="Priskorrektion", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Priskorrektion")
   */
  private $priskorrektion2;

  /**
   * @var integer
   *
   * @ORM\Column(name="antalKorr2", type="integer", nullable=true)
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
   * @ORM\Column(name="prisKorrektion3", type="Priskorrektion", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Priskorrektion")
   */
  private $priskorrektion3;

  /**
   * @var integer
   *
   * @ORM\Column(name="antalKorr3", type="integer", nullable=true)
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
   * @var \AppBundle\Entity\LandInspektoer
   *
   * @ORM\ManyToOne(targetEntity="Landinspektoer")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="landInspektoerId", referencedColumnName="id", nullable=true)
   * })
   */
  private $landinspektoer;

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
   * @OneToMany(targetEntity="Reservation", mappedBy="grund")
   */
  private $reservationer;

  /**
   * @var \AppBundle\Entity\Salgshistorik
   *
   * @OneToMany(targetEntity="Salgshistorik", mappedBy="grund")
   */
  private $salgshistorik;

  /**
   * @var \AppBundle\Entity\Lokalsamfund
   *
   * @ORM\ManyToOne(targetEntity="Lokalsamfund")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="lokalsamfundId", referencedColumnName="id")
   * })
   */
  private $lokalsamfund;

  /**
   * @var \AppBundle\Entity\Salgsomraade
   *
   * @ORM\ManyToOne(targetEntity="Salgsomraade", fetch="EAGER")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="salgsomraadeId", referencedColumnName="id")
   * })
   */
  private $salgsomraade;

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
   * @var string
   *
   * @ORM\Column(name="pdflink", type="string", length=255, nullable=true)
   */
  private $pdfLink;

  public function __construct() {
    $this->reservationer = new ArrayCollection();
    $this->salgshistorik = new ArrayCollection();
    $this->tilsluttet = array();
    $this->annonceres = false;
    $this->setSalgstype(SalgsType::KVADRATMETERPRIS);
  }

  /**
   * Mainly used in form generation.
   *
   * @return string
   */
  public function __toString() {
    return $this->getVej() . ' ' . $this->getHusnummer() . $this->getBogstav() . ($this->getZipcity() ? ', ' . $this->getZipcity() : '');
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set status
   *
   * @param GrundStatus $status
   *
   * @return Grund
   */
  public function setStatus($status) {
    $this->status = $status;

    return $this;
  }

  /**
   * Get status
   *
   * @return GrundStatus
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * Set salgstatus
   *
   * @param GrundSalgStatus $salgstatus
   *
   * @return Grund
   */
  public function setSalgstatus($salgstatus) {
    $this->salgstatus = $salgstatus;

    return $this;
  }

  /**
   * Get salgstatus
   *
   * @return GrundSalgStatus
   */
  public function getSalgstatus() {
    return $this->salgstatus;
  }

  /**
   * Set gid
   *
   * @param string $gid
   *
   * @return Grund
   */
  public function setGid($gid) {
    $this->gid = $gid;

    return $this;
  }

  /**
   * Get gid
   *
   * @return string
   */
  public function getGid() {
    return $this->gid;
  }

  /**
   * Set grundtype
   *
   * @param GrundType $type
   *
   * @return Grund
   */
  public function setType($type) {
    $this->type = $type;

    return $this;
  }

  /**
   * Get grundtype
   *
   * @return GrundType
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set mnr
   *
   * @param string $mnr
   *
   * @return Grund
   */
  public function setMnr($mnr) {
    $this->mnr = $mnr;

    return $this;
  }

  /**
   * Get mnr
   *
   * @return string
   */
  public function getMnr() {
    return $this->mnr;
  }

  /**
   * Set mnr2
   *
   * @param string $mnr2
   *
   * @return Grund
   */
  public function setMnr2($mnr2) {
    $this->mnr2 = $mnr2;

    return $this;
  }

  /**
   * Get mnr2
   *
   * @return string
   */
  public function getMnr2() {
    return $this->mnr2;
  }

  /**
   * Set delareal
   *
   * @param string $delareal
   *
   * @return Grund
   */
  public function setDelareal($delareal) {
    $this->delareal = $delareal;

    return $this;
  }

  /**
   * Get delareal
   *
   * @return string
   */
  public function getDelareal() {
    return $this->delareal;
  }

  /**
   * Set ejerlav
   *
   * @param string $ejerlav
   *
   * @return Grund
   */
  public function setEjerlav($ejerlav) {
    $this->ejerlav = $ejerlav;

    return $this;
  }

  /**
   * Get ejerlav
   *
   * @return string
   */
  public function getEjerlav() {
    return $this->ejerlav;
  }

  /**
   * Set vej
   *
   * @param string $vej
   *
   * @return Grund
   */
  public function setVej($vej) {
    $this->vej = $vej;

    return $this;
  }

  /**
   * Get vej
   *
   * @return string
   */
  public function getVej() {
    return $this->vej;
  }

  /**
   * Set husnummer
   *
   * @param string $husnummer
   *
   * @return Grund
   */
  public function setHusnummer($husnummer) {
    $this->husnummer = $husnummer;

    return $this;
  }

  /**
   * Get husnummer
   *
   * @return string
   */
  public function getHusnummer() {
    return $this->husnummer;
  }

  /**
   * Set bogstav
   *
   * @param string $bogstav
   *
   * @return Grund
   */
  public function setBogstav($bogstav) {
    $this->bogstav = $bogstav;

    return $this;
  }

  /**
   * Get bogstav
   *
   * @return string
   */
  public function getBogstav() {
    return $this->bogstav;
  }

  /**
   * Set postbyid
   *
   * @param \AppBundle\Entity\Postby $postby
   *
   * @return Grund
   */
  public function setPostby($postby) {
    $this->postby = $postby;

    return $this;
  }

  /**
   * Get postbyid
   *
   * @return \AppBundle\Entity\Postby
   */
  public function getPostby() {
    return $this->postby;
  }

  /**
   * Set urlgis
   *
   * @param string $urlgis
   *
   * @return Grund
   */
  public function setUrlgis($urlgis) {
    $this->urlgis = $urlgis;

    return $this;
  }

  /**
   * Get urlgis
   *
   * @return string
   */
  public function getUrlgis() {
    return $this->urlgis;
  }

  /**
   * Set salgstype
   *
   * @param SalgsType $salgstype
   *
   * @return Grund
   */
  public function setSalgstype($salgstype) {
    $this->salgstype = $salgstype;

    return $this;
  }

  /**
   * Get salgstype
   *
   * @return SalgsType
   */
  public function getSalgstype() {
    return $this->salgstype;
  }

  /**
   * Set auktionstartdato
   *
   * @param \DateTime $auktionstartdato
   *
   * @return Grund
   */
  public function setAuktionstartdato($auktionstartdato) {
    $this->auktionstartdato = $auktionstartdato;

    return $this;
  }

  /**
   * Get auktionstartdato
   *
   * @return \DateTime
   */
  public function getAuktionstartdato() {
    return $this->auktionstartdato;
  }

  /**
   * Set auktionslutdato
   *
   * @param \DateTime $auktionslutdato
   *
   * @return Grund
   */
  public function setAuktionslutdato($auktionslutdato) {
    $this->auktionslutdato = $auktionslutdato;

    return $this;
  }

  /**
   * Get auktionslutdato
   *
   * @return \DateTime
   */
  public function getAuktionslutdato() {
    return $this->auktionslutdato;
  }

  /**
   * @return bool
   */
  public function isAnnonceres(): bool
  {
    return $this->annonceres;
  }

  /**
   * @param bool $annonceres
   */
  public function setAnnonceres(bool $annonceres)
  {
    $this->annonceres = $annonceres;
  }

  /**
   * Set datoannonce
   *
   * @param \DateTime $datoannonce
   *
   * @return Grund
   */
  public function setDatoannonce($datoannonce) {
    $this->datoannonce = $datoannonce;

    return $this;
  }

  /**
   * Get datoannonce
   *
   * @return \DateTime
   */
  public function getDatoannonce() {
    return $this->datoannonce;
  }

  /**
   * Set datoannonce1
   *
   * @param \DateTime $datoannonce1
   *
   * @return Grund
   */
  public function setDatoannonce1($datoannonce1) {
    $this->datoannonce1 = $datoannonce1;

    return $this;
  }

  /**
   * Get datoannonce1
   *
   * @return \DateTime
   */
  public function getDatoannonce1() {
    return $this->datoannonce1;
  }

  /**
   * @return array
   */
  public function getTilsluttet() {
    return $this->tilsluttet;
  }

  /**
   * @param array $tilsluttet
   */
  public function setTilsluttet(array $tilsluttet) {
    $this->tilsluttet = $tilsluttet;
  }

  /**
   * Set maxetagem2
   *
   * @param float $maxetagem2
   *
   * @return Grund
   */
  public function setMaxetagem2($maxetagem2) {
    $this->maxetagem2 = $maxetagem2;

    return $this;
  }

  /**
   * Get maxetagem2
   *
   * @return float
   */
  public function getMaxetagem2() {
    return $this->maxetagem2;
  }

  /**
   * Set areal
   *
   * @param float $areal
   *
   * @return Grund
   */
  public function setAreal($areal) {
    $this->areal = $areal;

    return $this;
  }

  /**
   * Get areal
   *
   * @return float
   */
  public function getAreal() {
    return $this->areal;
  }

  /**
   * Set arealvej
   *
   * @param float $arealvej
   *
   * @return Grund
   */
  public function setArealvej($arealvej) {
    $this->arealvej = $arealvej;

    return $this;
  }

  /**
   * Get arealvej
   *
   * @return float
   */
  public function getArealvej() {
    return $this->arealvej;
  }

  /**
   * Set arealkotelet
   *
   * @param float $arealkotelet
   *
   * @return Grund
   */
  public function setArealkotelet($arealkotelet) {
    $this->arealkotelet = $arealkotelet;

    return $this;
  }

  /**
   * Get arealkotelet
   *
   * @return float
   */
  public function getArealkotelet() {
    return $this->arealkotelet;
  }

  /**
   * Set bruttoareal
   *
   * @param float $bruttoareal
   *
   * @return Grund
   */
  public function setBruttoareal($bruttoareal) {
    $this->bruttoareal = $bruttoareal;

    return $this;
  }

  /**
   * Get bruttoareal
   *
   * @return float
   */
  public function getBruttoareal() {
    return $this->bruttoareal;
  }

  /**
   * Set prism2
   *
   * @param float $prism2
   *
   * @return Grund
   */
  public function setPrism2($prism2) {
    $this->prism2 = $prism2;

    return $this;
  }

  /**
   * Get prism2
   *
   * @return float
   */
  public function getPrism2() {
    return $this->prism2;
  }

  /**
   * Set prisfoerkorrektion
   *
   * @param float $prisfoerkorrektion
   *
   * @return Grund
   */
  public function setPrisfoerkorrektion($prisfoerkorrektion) {
    $this->prisfoerkorrektion = $prisfoerkorrektion;

    return $this;
  }

  /**
   * Get prisfoerkorrektion
   *
   * @return float
   */
  public function getPrisfoerkorrektion() {
    return $this->prisfoerkorrektion;
  }

  /**
   * Set priskorrektion1
   *
   * @param string $priskorrektion1
   *
   * @return Grund
   */
  public function setPriskorrektion1($priskorrektion1) {
    $this->priskorrektion1 = $priskorrektion1;

    return $this;
  }

  /**
   * Get priskorrektion1
   *
   * @return string
   */
  public function getPriskorrektion1() {
    return $this->priskorrektion1;
  }

  /**
   * Set antalkorr1
   *
   * @param integer $antalkorr1
   *
   * @return Grund
   */
  public function setAntalkorr1($antalkorr1) {
    $this->antalkorr1 = $antalkorr1;

    return $this;
  }

  /**
   * Get antalkorr1
   *
   * @return integer
   */
  public function getAntalkorr1() {
    return $this->antalkorr1;
  }

  /**
   * Set akrkorr1
   *
   * @param float $akrkorr1
   *
   * @return Grund
   */
  public function setAkrkorr1($akrkorr1) {
    $this->akrkorr1 = $akrkorr1;

    return $this;
  }

  /**
   * Get akrkorr1
   *
   * @return float
   */
  public function getAkrkorr1() {
    return $this->akrkorr1;
  }

  /**
   * Set priskoor1
   *
   * @param float $priskoor1
   *
   * @return Grund
   */
  public function setPriskoor1($priskoor1) {
    $this->priskoor1 = $priskoor1;

    return $this;
  }

  /**
   * Get priskoor1
   *
   * @return float
   */
  public function getPriskoor1() {
    return $this->priskoor1;
  }

  /**
   * Set priskorrektion2
   *
   * @param string $priskorrektion2
   *
   * @return Grund
   */
  public function setPriskorrektion2($priskorrektion2) {
    $this->priskorrektion2 = $priskorrektion2;

    return $this;
  }

  /**
   * Get priskorrektion2
   *
   * @return string
   */
  public function getPriskorrektion2() {
    return $this->priskorrektion2;
  }

  /**
   * Set antalkorr2
   *
   * @param integer $antalkorr2
   *
   * @return Grund
   */
  public function setAntalkorr2($antalkorr2) {
    $this->antalkorr2 = $antalkorr2;

    return $this;
  }

  /**
   * Get antalkorr2
   *
   * @return integer
   */
  public function getAntalkorr2() {
    return $this->antalkorr2;
  }

  /**
   * Set akrkorr2
   *
   * @param float $akrkorr2
   *
   * @return Grund
   */
  public function setAkrkorr2($akrkorr2) {
    $this->akrkorr2 = $akrkorr2;

    return $this;
  }

  /**
   * Get akrkorr2
   *
   * @return float
   */
  public function getAkrkorr2() {
    return $this->akrkorr2;
  }

  /**
   * Set priskoor2
   *
   * @param float $priskoor2
   *
   * @return Grund
   */
  public function setPriskoor2($priskoor2) {
    $this->priskoor2 = $priskoor2;

    return $this;
  }

  /**
   * Get priskoor2
   *
   * @return float
   */
  public function getPriskoor2() {
    return $this->priskoor2;
  }

  /**
   * Set priskorrektion3
   *
   * @param string $priskorrektion3
   *
   * @return Grund
   */
  public function setPriskorrektion3($priskorrektion3) {
    $this->priskorrektion3 = $priskorrektion3;

    return $this;
  }

  /**
   * Get priskorrektion3
   *
   * @return string
   */
  public function getPriskorrektion3() {
    return $this->priskorrektion3;
  }

  /**
   * Set antalkorr3
   *
   * @param integer $antalkorr3
   *
   * @return Grund
   */
  public function setAntalkorr3($antalkorr3) {
    $this->antalkorr3 = $antalkorr3;

    return $this;
  }

  /**
   * Get antalkorr3
   *
   * @return integer
   */
  public function getAntalkorr3() {
    return $this->antalkorr3;
  }

  /**
   * Set akrkorr3
   *
   * @param float $akrkorr3
   *
   * @return Grund
   */
  public function setAkrkorr3($akrkorr3) {
    $this->akrkorr3 = $akrkorr3;

    return $this;
  }

  /**
   * Get akrkorr3
   *
   * @return float
   */
  public function getAkrkorr3() {
    return $this->akrkorr3;
  }

  /**
   * Set priskoor3
   *
   * @param float $priskoor3
   *
   * @return Grund
   */
  public function setPriskoor3($priskoor3) {
    $this->priskoor3 = $priskoor3;

    return $this;
  }

  /**
   * Get priskoor3
   *
   * @return float
   */
  public function getPriskoor3() {
    return $this->priskoor3;
  }

  /**
   * Set pris
   *
   * @param float $pris
   *
   * @return Grund
   */
  public function setPris($pris) {
    $this->pris = $pris;

    return $this;
  }

  /**
   * Get pris
   *
   * @return float
   */
  public function getPris() {
    return $this->pris;
  }

  /**
   * Set fastpris
   *
   * @param float $fastpris
   *
   * @return Grund
   */
  public function setFastpris($fastpris) {
    $this->fastpris = $fastpris;

    return $this;
  }

  /**
   * Get fastpris
   *
   * @return float
   */
  public function getFastpris() {
    return $this->fastpris;
  }

  /**
   * Set minbud
   *
   * @param float $minbud
   *
   * @return Grund
   */
  public function setMinbud($minbud) {
    $this->minbud = $minbud;

    return $this;
  }

  /**
   * Get minbud
   *
   * @return float
   */
  public function getMinbud() {
    return $this->minbud;
  }

  /**
   * Set note
   *
   * @param string $note
   *
   * @return Grund
   */
  public function setNote($note) {
    $this->note = $note;

    return $this;
  }

  /**
   * Get note
   *
   * @return string
   */
  public function getNote() {
    return $this->note;
  }

  /**
   * Set Landinspektoer
   *
   * @param string $landinspektoer
   *
   * @return Grund
   */
  public function setLandinspektoer($landinspektoer) {
    $this->landinspektoer = $landinspektoer;

    return $this;
  }

  /**
   * Get landinspektoerid
   *
   * @return string
   */
  public function getLandinspektoer() {
    return $this->landinspektoer;
  }

  /**
   * Set resstart
   *
   * @param \DateTime $resstart
   *
   * @return Grund
   */
  public function setResstart($resstart) {
    $this->resstart = $resstart;

    return $this;
  }

  /**
   * Get resstart
   *
   * @return \DateTime
   */
  public function getResstart() {
    return $this->resstart;
  }

  /**
   * Set tilbudstart
   *
   * @param \DateTime $tilbudstart
   *
   * @return Grund
   */
  public function setTilbudstart($tilbudstart) {
    $this->tilbudstart = $tilbudstart;

    return $this;
  }

  /**
   * Get tilbudstart
   *
   * @return \DateTime
   */
  public function getTilbudstart() {
    return $this->tilbudstart;
  }

  /**
   * Set accept
   *
   * @param \DateTime $accept
   *
   * @return Grund
   */
  public function setAccept($accept) {
    $this->accept = $accept;

    return $this;
  }

  /**
   * Get accept
   *
   * @return \DateTime
   */
  public function getAccept() {
    return $this->accept;
  }

  /**
   * Set skoederekv
   *
   * @param \DateTime $skoederekv
   *
   * @return Grund
   */
  public function setSkoederekv($skoederekv) {
    $this->skoederekv = $skoederekv;

    return $this;
  }

  /**
   * Get skoederekv
   *
   * @return \DateTime
   */
  public function getSkoederekv() {
    return $this->skoederekv;
  }

  /**
   * Set beloebanvist
   *
   * @param \DateTime $beloebanvist
   *
   * @return Grund
   */
  public function setBeloebanvist($beloebanvist) {
    $this->beloebanvist = $beloebanvist;

    return $this;
  }

  /**
   * Get beloebanvist
   *
   * @return \DateTime
   */
  public function getBeloebanvist() {
    return $this->beloebanvist;
  }

  /**
   * Set resslut
   *
   * @param \DateTime $resslut
   *
   * @return Grund
   */
  public function setResslut($resslut) {
    $this->resslut = $resslut;

    return $this;
  }

  /**
   * Get resslut
   *
   * @return \DateTime
   */
  public function getResslut() {
    return $this->resslut;
  }

  /**
   * Set tilbudslut
   *
   * @param \DateTime $tilbudslut
   *
   * @return Grund
   */
  public function setTilbudslut($tilbudslut) {
    $this->tilbudslut = $tilbudslut;

    return $this;
  }

  /**
   * Get tilbudslut
   *
   * @return \DateTime
   */
  public function getTilbudslut() {
    return $this->tilbudslut;
  }

  /**
   * Set overtagelse
   *
   * @param \DateTime $overtagelse
   *
   * @return Grund
   */
  public function setOvertagelse($overtagelse) {
    $this->overtagelse = $overtagelse;

    return $this;
  }

  /**
   * Get overtagelse
   *
   * @return \DateTime
   */
  public function getOvertagelse() {
    return $this->overtagelse;
  }

  /**
   * Set antagetbud
   *
   * @param float $antagetbud
   *
   * @return Grund
   */
  public function setAntagetbud($antagetbud) {
    $this->antagetbud = $antagetbud;

    return $this;
  }

  /**
   * Get antagetbud
   *
   * @return float
   */
  public function getAntagetbud() {
    return $this->antagetbud;
  }

  /**
   * Set salgsprisumoms
   *
   * @param float $salgsprisumoms
   *
   * @return Grund
   */
  public function setSalgsprisumoms($salgsprisumoms) {
    $this->salgsprisumoms = $salgsprisumoms;

    return $this;
  }

  /**
   * Get salgsprisumoms
   *
   * @return float
   */
  public function getSalgsprisumoms() {
    return $this->salgsprisumoms;
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

  /**
   * @return mixed
   */
  public function getSalgshistorik() {
    return $this->salgshistorik;
  }

  /**
   * @param mixed $salgshistorik
   */
  public function setSalgshistorik($salgshistorik) {
    $this->salgshistorik = $salgshistorik;
  }

  /**
   * Set lokalsamfund
   *
   * @param \AppBundle\Entity\Lokalsamfund $lokalsamfund
   *
   * @return Grund
   */
  public function setLokalsamfund(\AppBundle\Entity\Lokalsamfund $lokalsamfund = NULL) {
    $this->lokalsamfund = $lokalsamfund;

    return $this;
  }

  /**
   * Get lokalsamfund
   *
   * @return \AppBundle\Entity\Lokalsamfund
   */
  public function getLokalsamfund() {
    return $this->lokalsamfund;
  }

  /**
   * Set salgsomraadeid
   *
   * @param \AppBundle\Entity\Salgsomraade $salgsomraadeid
   *
   * @return Grund
   */
  public function setSalgsomraade(\AppBundle\Entity\Salgsomraade $salgsomraade = NULL) {
    $this->salgsomraade = $salgsomraade;

    return $this;
  }

  /**
   * Get salgsomraade entity.
   *
   * @return \AppBundle\Entity\Salgsomraade
   */
  public function getSalgsomraade() {
    return $this->salgsomraade;
  }

  /**
   * @return \CrEOF\Spatial\DBAL\Types\Geography
   */
  public function getSpGeometry() {
    return $this->sp_geometry;
  }

  /**
   * @param \CrEOF\Spatial\PHP\Types\Geography\Polygon $sp_geometry
   */
  public function setSpGeometry(\CrEOF\Spatial\PHP\Types\Geography\Polygon $sp_geometry) {
    $this->sp_geometry = $sp_geometry;
  }

  /**
   * @return int
   */
  public function getSrid() {
    return $this->srid;
  }

  /**
   * @param int $srid
   */
  public function setSrid($srid) {
    $this->srid = $srid;
  }

  /**
   * @return string
   */
  public function getPdfLink() {
    return $this->pdfLink;
  }

  /**
   * @param string $pdfLink
   */
  public function setPdfLink($pdfLink) {
    $this->pdfLink = $pdfLink;
  }

  /**
   * Get the spatial data as an array.
   *
   * @return null|array
   *   If spatial data exists on the entity array is returned else null.
   */
  public function getSpGeometryArray() {
    if ($this->getSpGeometry()) {
      $json['type'] = $this->getSpGeometry()->getType();
      $json['coordinates'] = $this->getSpGeometry()->toArray();

      return $json;
    }

    return NULL;
  }

  /**
   * Get the status to show in the admin interface
   *
   * "Copy-paste" from legacy system
   *
   * @return string
   */
  public function getVisStatus() {
    if($this->getSalgstatus() === GrundSalgStatus::LEDIG) {
      return strval($this->getStatus());
    } else {
      return strval($this->getSalgstatus());
    }
  }



}
