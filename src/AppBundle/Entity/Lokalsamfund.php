<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Lokalsamfund
 *
 * @ORM\Table(name="Lokalsamfund", indexes={
 *   @ORM\Index(name="search_Lokalsamfund_active", columns={"active"}),
 *   @ORM\Index(name="search_Lokalsamfund_number", columns={"number"}),
 *   @ORM\Index(name="search_Lokalsamfund_name", columns={"name"})
 * })
 * @ORM\Entity
 *
 * @Gedmo\Loggable
 */
class Lokalsamfund
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
   * @ORM\Column(name="number", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $number;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=50, nullable=false)
   *
   * @Gedmo\Versioned
   */
  private $name;

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
   * Set number
   *
   * @param string $number
   *
   * @return Lokalsamfund
   */
  public function setNumber($number)
  {
    $this->number = $number;

    return $this;
  }

  /**
   * Get number
   *
   * @return string
   */
  public function getNumber()
  {
    return $this->number;
  }

  /**
   * Set name
   *
   * @param string $name
   *
   * @return Lokalsamfund
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get name
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set active
   *
   * @param integer $active
   *
   * @return Lokalsamfund
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
    return $this->getNumber() . ' - ' . $this->getName();
  }
}
