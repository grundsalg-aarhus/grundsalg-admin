<?php

namespace AppBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user", indexes={
 *   @ORM\Index(name="search_User_enabled", columns={"enabled"}),
 *   @ORM\Index(name="search_User_username", columns={"username"}),
 *   @ORM\Index(name="search_User_name", columns={"name"}),
 *   @ORM\Index(name="search_User_email", columns={"email"})
 * })
 */
class User extends BaseUser
{
  use BlameableEntity;
  use TimestampableEntity;

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=255, nullable=true)
   */
  protected $name;

  /**
   * User constructor.
   */
  public function __construct()
  {
    parent::__construct();
  }

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
   * Set name
   *
   * @param string $name
   *
   * @return User
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
   * __toString
   *
   * @return string
   */
  public function __toString()
  {
    return isset($this->name) ? $this->name : $this->getUsernameCanonical();
  }
}