<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Keyword
 *
 * @ORM\Table(name="Keyword")
 * @ORM\Entity
 */
class Keyword
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
   * @ORM\Column(name="title", type="string", length=50, nullable=false)
   */
  private $title;

  /**
   * @var string
   *
   * @ORM\Column(name="alias", type="string", length=50, nullable=false)
   */
  private $alias;

  /**
   * @var string
   *
   * @ORM\Column(name="description", type="text", nullable=false)
   */
  private $description;


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
   * Set title
   *
   * @param string $title
   *
   * @return Keyword
   */
  public function setTitle($title)
  {
    $this->title = $title;

    return $this;
  }

  /**
   * Get title
   *
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set alias
   *
   * @param string $alias
   *
   * @return Keyword
   */
  public function setAlias($alias)
  {
    $this->alias = $alias;

    return $this;
  }

  /**
   * Get alias
   *
   * @return string
   */
  public function getAlias()
  {
    return $this->alias;
  }

  /**
   * Set description
   *
   * @param string $description
   *
   * @return Keyword
   */
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get description
   *
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  public function __toString()
  {
    return __CLASS__;
  }
}
