<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Webforge\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledTipGroup {
  
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
   * members
   * @ORM\OneToMany(mappedBy="tipGroup", targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember", cascade={"all"}, orphanRemoval=true)
   */
  protected $members;
  
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
   * @param Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember> $members
   */
  public function setMembers(ArrayCollection $members) {
    $this->members = $members;
    return $this;
  }
  
  /**
   * @return Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember>
   */
  public function getMembers() {
    return $this->members;
  }
  
  public function addMember(TipGroupMember $member) {
    if (!$this->members->contains($member)) {
        $this->members->add($member);
    }
    return $this;
  }
  
  public function removeMember(TipGroupMember $member) {
    if ($this->members->contains($member)) {
        $this->members->removeElement($member);
    }
    return $this;
  }
  
  public function hasMember(TipGroupMember $member) {
    return $this->members->contains($member);
  }
  
  public function __construct() {
    $this->members = new ArrayCollection();
  }
}
