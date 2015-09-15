<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledTipGroupMember {
  
  /**
   * id
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;
  
  /**
   * user
   * @ORM\ManyToOne(targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\User", inversedBy="tipGroupMemberships")
   * @ORM\JoinColumn
   */
  protected $user;
  
  /**
   * role
   * @ORM\Column(type="integer")
   */
  protected $role;
  
  /**
   * tipGroup
   * @ORM\ManyToOne(targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup", cascade={"all"}, inversedBy="members")
   * @ORM\JoinColumn
   */
  protected $tipGroup;
  
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
   * @param Ts\Superkicker\SuperkickerBundle\Domain\Model\User $user
   */
  public function setUser(User $user = NULL) {
    if (isset($this->user) && $this->user !== $user) {
        $this->user->removeTipGroupMembership($this);
    }
    $this->user = $user;
    if (isset($user)) {
        $user->addTipGroupMembership($this);
    }
    return $this;
  }
  
  /**
   * @return Ts\Superkicker\SuperkickerBundle\Domain\Model\User
   */
  public function getUser() {
    return $this->user;
  }
  
  /**
   * @param integer $role
   */
  public function setRole($role) {
    $this->role = $role;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getRole() {
    return $this->role;
  }
  
  /**
   * @param Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup $tipGroup
   */
  public function setTipGroup(TipGroup $tipGroup = NULL) {
    if (isset($this->tipGroup) && $this->tipGroup !== $tipGroup) {
        $this->tipGroup->removeMember($this);
    }
    $this->tipGroup = $tipGroup;
    if (isset($tipGroup)) {
        $tipGroup->addMember($this);
    }
    return $this;
  }
  
  /**
   * @return Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup
   */
  public function getTipGroup() {
    return $this->tipGroup;
  }
  
  public function __construct() {

  }
}
