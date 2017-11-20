<?php
/**
 *
 *
 */

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\DBAL\Types\GrundSalgStatus;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Salgshistorik
 *
 * @ORM\Table(name="Salgshistorik", indexes={@ORM\Index(name="fk_Salgshistorik_grundId", columns={"grundId"}),
 *   @ORM\Index(name="fk_Salgshistorik_koeberPostById", columns={"koeberPostById"}),
 *   @ORM\Index(name="fk_Salgshistorik_medKoeberPostById", columns={"medKoeberPostById"})})
 * @ORM\Entity
 *
 * @Gedmo\Loggable
 */
class Salgshistorik {
  use BlameableEntity;
  use TimestampableEntity;


  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="aarsag", type="text", nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $aarsag;

  /**
   * @var SalgsType
   *
   * @ORM\Column(name="salgsType", type="SalgsType", nullable=true)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\SalgsType")
   *
   * @Gedmo\Versioned
   */
  private $salgstype;

  /**
   * @var GrundSalgStatus
   *
   * @ORM\Column(name="salgStatus", type="GrundSalgStatus", nullable=true)
   *
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\GrundSalgStatus")
   *
   * @Gedmo\Versioned
   */
  private $salgStatus;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="resStart", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $resstart;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="resSlut", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $resslut;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="tilbudStart", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $tilbudstart;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="tilbudSlut", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $tilbudslut;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="accept", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $accept;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="overtagelse", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $overtagelse;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="skoedeRekv", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $skoederekv;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="beloebAnvist", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $beloebanvist;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="auktionStartDato", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $auktionstartdato;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="auktionSlutDato", type="date", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $auktionslutdato;

  /**
   * @var decimal
   *
   * @ORM\Column(name="minBud", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $minbud;

  /**
   * @var decimal
   *
   * @ORM\Column(name="antagetBud", type="decimal", precision=12, scale=2, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $antagetbud;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberNavn", type="string", length=255, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberNavn;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberAdresse", type="string", length=120, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberAdresse;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberLand", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberLand;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberTelefon", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberTelefon;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberMobil", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberMobil;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberEmail", type="string", length=120, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberEmail;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberNavn", type="string", length=255, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberNavn;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberAdresse", type="string", length=120, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberAdresse;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberLand", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberLand;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberTelefon", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberTelefon;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberMobil", type="string", length=50, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberMobil;

  /**
   * @var string
   *
   * @ORM\Column(name="medkoeberEmail", type="string", length=120, nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $medkoeberEmail;

  /**
   * @var string
   *
   * @ORM\Column(name="koeberNotat", type="text", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $koeberNotat;


  /**
   * @var \Grund
   *
   * @ORM\ManyToOne(targetEntity="Grund", inversedBy="salgshistorik")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="grundId", referencedColumnName="id")
   * })
   *
   * @Gedmo\Versioned
   */
  private $grund;

  /**
   * @var \Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="koeberPostById", referencedColumnName="id")
   * })
   * @ORM\OrderBy({"postalcode" = "ASC"})
   *
   * @Gedmo\Versioned
   */
  private $koeberPostby;

  /**
   * @var \AppBundle\Entity\Postby
   *
   * @ORM\ManyToOne(targetEntity="Postby")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="medKoeberPostById", referencedColumnName="id")
   * })
   * @ORM\OrderBy({"postalcode" = "ASC"})
   *
   * @Gedmo\Versioned
   */
  private $medkoeberPostby;

  /**
   * Salgshistorik constructor.
   *
   * @param Grund $grund
   */
  public function __construct(Grund $grund) {
    $grund->addSalgshistorik($this);

    $this->setSalgstype($grund->getSalgstype());
    $this->setSalgStatus($grund->getSalgstatus());

    $this->setResstart($grund->getResstart());
    $this->setResslut($grund->getResslut());
    $this->setTilbudstart($grund->getTilbudstart());
    $this->setTilbudslut($grund->getTilbudslut());
    $this->setAccept($grund->getAccept());
    $this->setOvertagelse($grund->getOvertagelse());
    $this->setSkoederekv($grund->getSkoederekv());
    $this->setBeloebanvist($grund->getBeloebanvist());
    $this->setAuktionstartdato($grund->getAuktionstartdato());
    $this->setAuktionslutdato($grund->getAuktionslutdato());
    $this->setMinbud($grund->getMinbud());
    $this->setAntagetbud($grund->getAntagetbud());

    $this->setKoeberNavn($grund->getKoeberNavn());
    $this->setKoeberAdresse($grund->getKoeberAdresse());
    $this->setKoeberPostby($grund->getKoeberPostby());
    $this->setKoeberLand($grund->getKoeberLand());
    $this->setKoeberTelefon($grund->getKoeberTelefon());
    $this->setKoeberMobil($grund->getKoeberMobil());
    $this->setKoeberEmail($grund->getKoeberEmail());

    $this->setKoeberNotat($grund->getKoeberNotat());

    $this->setMedkoeberNavn($grund->getMedkoeberNavn());
    $this->setMedkoeberAdresse($grund->getMedkoeberAdresse());
    $this->setMedkoeberPostby($grund->getMedkoeberPostby());
    $this->setMedkoeberLand($grund->getMedkoeberLand());
    $this->setMedkoeberTelefon($grund->getMedkoeberTelefon());
    $this->setMedkoeberMobil($grund->getMedkoeberMobil());
    $this->setMedkoeberEmail($grund->getMedkoeberEmail());
  }

  /**
   * @return string
   */
  public function __toString() {
    return __CLASS__;
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
   * Set aarsag
   *
   * @param string $aarsag
   *
   * @return Salgshistorik
   */
  public function setAarsag($aarsag) {
    $this->aarsag = $aarsag;

    return $this;
  }

  /**
   * Get aarsag
   *
   * @return string
   */
  public function getAarsag() {
    return $this->aarsag;
  }

  /**
   * Set salgstype
   *
   * @param string $salgstype
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
   * @return string
   */
  public function getSalgstype() {
    return $this->salgstype;
  }

  /**
   * Set status
   *
   * @param GrundSalgStatus $salgStatus
   *
   * @return Grund
   */
  public function setSalgStatus($salgStatus) {
    $this->salgStatus = $salgStatus;

    return $this;
  }

  /**
   * Get status
   *
   * @return GrundSalgStatus
   */
  public function getSalgStatus() {
    return $this->salgStatus;
  }

  /**
   * Set resstart
   *
   * @param \DateTime $resstart
   *
   * @return Salgshistorik
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
   * Set resslut
   *
   * @param \DateTime $resslut
   *
   * @return Salgshistorik
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
   * Set tilbudstart
   *
   * @param \DateTime $tilbudstart
   *
   * @return Salgshistorik
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
   * Set tilbudslut
   *
   * @param \DateTime $tilbudslut
   *
   * @return Salgshistorik
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
   * Set accept
   *
   * @param \DateTime $accept
   *
   * @return Salgshistorik
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
   * Set overtagelse
   *
   * @param \DateTime $overtagelse
   *
   * @return Salgshistorik
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
   * Set skoederekv
   *
   * @param \DateTime $skoederekv
   *
   * @return Salgshistorik
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
   * @return Salgshistorik
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
   * Set auktionstartdato
   *
   * @param \DateTime $auktionstartdato
   *
   * @return Salgshistorik
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
   * @return Salgshistorik
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
   * Set minbud
   *
   * @param decimal $minbud
   *
   * @return Salgshistorik
   */
  public function setMinbud($minbud) {
    $this->minbud = $minbud;

    return $this;
  }

  /**
   * Get minbud
   *
   * @return decimal
   */
  public function getMinbud() {
    return $this->minbud;
  }

  /**
   * Set antagetbud
   *
   * @param decimal $antagetbud
   *
   * @return Salgshistorik
   */
  public function setAntagetbud($antagetbud) {
    $this->antagetbud = $antagetbud;

    return $this;
  }

  /**
   * Get antagetbud
   *
   * @return decimal
   */
  public function getAntagetbud() {
    return $this->antagetbud;
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
   * Set grundid
   *
   * @param \AppBundle\Entity\Grund $grund
   *
   * @return Salgshistorik
   */
  public function setGrund(\AppBundle\Entity\Grund $grund = NULL) {
    $this->grund = $grund;

    return $this;
  }

  /**
   * Get grundid
   *
   * @return \AppBundle\Entity\Grund
   */
  public function getGrund() {
    return $this->grund;
  }
}
