<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\User
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\User class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledUser extends BaseUser{
  
  /**
   * id
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;
  
  /**
   * name
   * @ORM\Column(nullable=true)
   */
  protected $name;
  
  /**
   * clientId
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $clientId;
  
  /**
   * @param integer $id
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getId() {
    return $this->id;
  }
  
  /**
   * @param string $name
   */
  public function setName($name = NULL) {
    $this->name = $name;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }
  
  /**
   * @param integer $clientId
   */
  public function setClientId($clientId = NULL) {
    $this->clientId = $clientId;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getClientId() {
    return $this->clientId;
  }
  
  public function __construct() {
    parent::__construct();
  }
}
