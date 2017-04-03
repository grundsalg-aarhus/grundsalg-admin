<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Postby
 *
 * @ORM\Table(name="PostBy", indexes={
 *   @ORM\Index(name="search_PostBy_postalcode", columns={"postalCode"}),
 *   @ORM\Index(name="search_PostBy_city", columns={"city"}),
 * })
 * @ORM\Entity
 */
class Postby
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
   * @var integer
   *
   * @ORM\Column(name="postalCode", type="integer", nullable=false)
   */
  private $postalcode;

  /**
   * @var string
   *
   * @ORM\Column(name="city", type="string", length=100, nullable=false)
   */
  private $city;


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
   * Set postalcode
   *
   * @param integer $postalcode
   *
   * @return Postby
   */
  public function setPostalcode($postalcode)
  {
    $this->postalcode = $postalcode;

    return $this;
  }

  /**
   * Get postalcode
   *
   * @return integer
   */
  public function getPostalcode()
  {
    return $this->postalcode;
  }

  /**
   * Set city
   *
   * @param string $city
   *
   * @return Postby
   */
  public function setCity($city)
  {
    $this->city = $city;

    return $this;
  }

  /**
   * Get city
   *
   * @return string
   */
  public function getCity()
  {
    return $this->city;
  }

  public function __toString()
  {
    return $this->postalcode . ' ' . $this->city;
  }
}
