<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Keywordvalue
 *
 * @ORM\Table(name="KeywordValue", indexes={@ORM\Index(name="fk_KeywordValue_keywordId", columns={"keywordId"})})
 * @ORM\Entity
 */
class KeywordValue
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
   * @ORM\Column(name="display", type="text", nullable=false)
   */
  private $display;

  /**
   * @var string
   *
   * @ORM\Column(name="value", type="text", nullable=false)
   */
  private $value;

  /**
   * @var \Keyword
   *
   * @ORM\ManyToOne(targetEntity="Keyword")
   * @ORM\JoinColumns({
   *   @ORM\JoinColumn(name="keywordId", referencedColumnName="id")
   * })
   */
  private $keyword;


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
   * Set display
   *
   * @param string $display
   *
   * @return KeywordValue
   */
  public function setDisplay($display)
  {
    $this->display = $display;

    return $this;
  }

  /**
   * Get display
   *
   * @return string
   */
  public function getDisplay()
  {
    return $this->display;
  }

  /**
   * Set value
   *
   * @param string $value
   *
   * @return KeywordValue
   */
  public function setValue($value)
  {
    $this->value = $value;

    return $this;
  }

  /**
   * Get value
   *
   * @return string
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * Set keywordid
   *
   * @param \AppBundle\Entity\Keyword $keyword
   *
   * @return KeywordValue
   */
  public function setKeyword(\AppBundle\Entity\Keyword $keyword = null)
  {
    $this->keyword = $keyword;

    return $this;
  }

  /**
   * Get keywordid
   *
   * @return \AppBundle\Entity\Keyword
   */
  public function getKeyword()
  {
    return $this->keyword;
  }

  public function __toString()
  {
    return __CLASS__;
  }
}
