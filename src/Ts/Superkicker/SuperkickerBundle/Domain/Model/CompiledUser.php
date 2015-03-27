<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\User
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\User class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledUser {
  
  /**
   * id
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;
  
  /**
   * name
   * @ORM\Column
   */
  protected $name;
  
  /**
   * clientId
   * @ORM\Column(type="integer")
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
  public function setName($name) {
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
  public function setClientId($clientId) {
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

  }
}
