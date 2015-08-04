<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Webforge\Collections\ArrayCollection;
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
   * tipps
   * @ORM\OneToMany(mappedBy="user", targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip", cascade={"all"})
   */
  protected $tipps;
  
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
   * @param Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip> $tipps
   */
  public function setTipps(ArrayCollection $tipps) {
    $this->tipps = $tipps;
    return $this;
  }
  
  /**
   * @return Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip>
   */
  public function getTipps() {
    return $this->tipps;
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
  
  public function addTipp(Tip $tipp) {
    if (!$this->tipps->contains($tipp)) {
        $this->tipps->add($tipp);
    }
    return $this;
  }
  
  public function removeTipp(Tip $tipp) {
    if ($this->tipps->contains($tipp)) {
        $this->tipps->removeElement($tipp);
    }
    return $this;
  }
  
  public function hasTipp(Tip $tipp) {
    return $this->tipps->contains($tipp);
  }
  
  public function __construct() {
    parent::__construct();
    $this->tipps = new ArrayCollection();
  }
}
