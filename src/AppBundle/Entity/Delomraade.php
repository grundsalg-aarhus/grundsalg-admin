<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\DBAL\Types\Anvendelse;
use AppBundle\DBAL\Types\Kpl4;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Delomraade
 *
 * @ORM\Table(name="Delomraade", indexes={
 *   @ORM\Index(name="fk_Delomraade_lokalplanId", columns={"lokalplanId"}),
 *
 *   @ORM\Index(name="search_Delomraade_anvendelse", columns={"anvendelse"}),
 * })
 * @ORM\Entity
 *
 * @Gedmo\Loggable
 */
class Delomraade
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
   * @ORM\Column(name="kpl1", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $kpl1;

  /**
   * @var string
   *
   * @ORM\Column(name="kpl2", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $kpl2;

  /**
   * @var string
   *
   * @ORM\Column(name="kpl3", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $kpl3;

  /**
   * @var Kpl4
   *
   * @ORM\Column(name="kpl4", type="Kpl4", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Kpl4")
   *
   * @Gedmo\Versioned
   */
  private $kpl4;

  /**
   * @var string
   *
   * @ORM\Column(name="o1", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $o1;

  /**
   * @var string
   *
   * @ORM\Column(name="o2", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $o2;

  /**
   * @var string
   *
   * @ORM\Column(name="o3", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $o3;

  /**
   * @var Anvendelse
   *
   * @ORM\Column(name="anvendelse", type="Anvendelse", nullable=true)
   * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Anvendelse")
   *
   * @Gedmo\Versioned
   */
  private $anvendelse;

  /**
   * @var array
   *
   * @ORM\Column(name="mulighedFor", type="array", nullable=true)
   *
   * @Gedmo\Versioned
   */
  private $mulighedfor;

  /**
   * @var \Lokalplan
   *
   * @ORM\ManyToOne(targetEntity="Lokalplan", fetch="EAGER")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="lokalplanId", referencedColumnName="id")
   * })
   *
   * @Gedmo\Versioned
   */
  private $lokalplan;


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
   * Set kpl1
   *
   * @param string $kpl1
   *
   * @return Delomraade
   */
  public function setKpl1($kpl1)
  {
    $this->kpl1 = $kpl1;

    return $this;
  }

  /**
   * Get kpl1
   *
   * @return string
   */
  public function getKpl1()
  {
    return $this->kpl1;
  }

  /**
   * Set kpl2
   *
   * @param string $kpl2
   *
   * @return Delomraade
   */
  public function setKpl2($kpl2)
  {
    $this->kpl2 = $kpl2;

    return $this;
  }

  /**
   * Get kpl2
   *
   * @return string
   */
  public function getKpl2()
  {
    return $this->kpl2;
  }

  /**
   * Set kpl3
   *
   * @param string $kpl3
   *
   * @return Delomraade
   */
  public function setKpl3($kpl3)
  {
    $this->kpl3 = $kpl3;

    return $this;
  }

  /**
   * Get kpl3
   *
   * @return string
   */
  public function getKpl3()
  {
    return $this->kpl3;
  }

  /**
   * Set kpl4
   *
   * @param Kpl4 $kpl4
   *
   * @return Delomraade
   */
  public function setKpl4($kpl4)
  {
    $this->kpl4 = $kpl4;

    return $this;
  }

  /**
   * Get kpl4
   *
   * @return Kpl4
   */
  public function getKpl4()
  {
    return $this->kpl4;
  }

  /**
   * Set o1
   *
   * @param string $o1
   *
   * @return Delomraade
   */
  public function setO1($o1)
  {
    $this->o1 = $o1;

    return $this;
  }

  /**
   * Get o1
   *
   * @return string
   */
  public function getO1()
  {
    return $this->o1;
  }

  /**
   * Set o2
   *
   * @param string $o2
   *
   * @return Delomraade
   */
  public function setO2($o2)
  {
    $this->o2 = $o2;

    return $this;
  }

  /**
   * Get o2
   *
   * @return string
   */
  public function getO2()
  {
    return $this->o2;
  }

  /**
   * Set o3
   *
   * @param string $o3
   *
   * @return Delomraade
   */
  public function setO3($o3)
  {
    $this->o3 = $o3;

    return $this;
  }

  /**
   * Get o3
   *
   * @return string
   */
  public function getO3()
  {
    return $this->o3;
  }

  /**
   * Get combined o1, o2, o3 for list display
   *
   * @return string
   */
  public function getO123Combined()
  {
    $result = '';
    $result = empty($this->getO1()) ? $result : $result .= $this->getO1() . '-';
    $result = empty($this->getO2()) ? $result : $result .= $this->getO2();
    $result = empty($this->getO3()) ? $result : $result .= '.' . $this->getO3();

    return $result;
  }

  /**
   * Get combined kpl1 - kpl 4 for list display
   *
   * @return string
   */
  public function getKpl1234Combined()
  {
    $result = '';
    $result = empty($this->getKpl1()) ? $result : $result .= $this->getKpl1();
    $result = empty($this->getKpl2()) ? $result : $result .= '.' . $this->getKpl2();
    $result = empty($this->getKpl3()) ? $result : $result .= '.' . $this->getKpl3();
    $result = empty($this->getKpl4()) ? $result : $result .= '.' . $this->getKpl4();

    return $result;
  }

  /**
   * Set anvendelse
   *
   * @param Anvendelse $anvendelse
   *
   * @return Delomraade
   */
  public function setAnvendelse($anvendelse)
  {
    $this->anvendelse = $anvendelse;

    return $this;
  }

  /**
   * Get anvendelse
   *
   * @return Anvendelse
   */
  public function getAnvendelse()
  {
    return $this->anvendelse;
  }

  /**
   * Set mulighedfor
   *
   * @param array $mulighedfor
   *
   * @return Delomraade
   */
  public function setMulighedfor($mulighedfor)
  {
    $this->mulighedfor = $mulighedfor;

    return $this;
  }

  /**
   * Get mulighedfor
   *
   * @return array
   */
  public function getMulighedfor()
  {
    return $this->mulighedfor;
  }

  /**
   * Set lokalplanid
   *
   * @param \AppBundle\Entity\Lokalplan $lokalplan
   *
   * @return Delomraade
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

  public function __toString()
  {
    return $this->getKpl1234Combined() . ' (Område: ' . $this->getO123Combined() . ' - LP: ' . $this->getLokalplan() . ')';
  }
}
