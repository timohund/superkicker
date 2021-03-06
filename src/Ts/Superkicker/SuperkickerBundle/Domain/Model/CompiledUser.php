<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Webforge\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\User
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\User class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledUser extends \FOS\UserBundle\Model\User {
  
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
   * oAuthService
   * @ORM\Column(nullable=true)
   */
  protected $oAuthService;
  
  /**
   * oAuthId
   * @ORM\Column(nullable=true)
   */
  protected $oAuthId;
  
  /**
   * oAuthAccessToken
   * @ORM\Column(nullable=true)
   */
  protected $oAuthAccessToken;
  
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
   * tipGroupMemberships
   * @ORM\OneToMany(mappedBy="user", targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember", cascade={"all"})
   */
  protected $tipGroupMemberships;
  
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
   * @param string $oAuthService
   */
  public function setOAuthService($oAuthService = NULL) {
    $this->oAuthService = $oAuthService;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getOAuthService() {
    return $this->oAuthService;
  }
  
  /**
   * @param string $oAuthId
   */
  public function setOAuthId($oAuthId = NULL) {
    $this->oAuthId = $oAuthId;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getOAuthId() {
    return $this->oAuthId;
  }
  
  /**
   * @param string $oAuthAccessToken
   */
  public function setOAuthAccessToken($oAuthAccessToken = NULL) {
    $this->oAuthAccessToken = $oAuthAccessToken;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getOAuthAccessToken() {
    return $this->oAuthAccessToken;
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
  
  /**
   * @param Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember> $tipGroupMemberships
   */
  public function setTipGroupMemberships(ArrayCollection $tipGroupMemberships) {
    $this->tipGroupMemberships = $tipGroupMemberships;
    return $this;
  }
  
  /**
   * @return Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember>
   */
  public function getTipGroupMemberships() {
    return $this->tipGroupMemberships;
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
  
  public function addTipGroupMembership(TipGroupMember $tipGroupMembership) {
    if (!$this->tipGroupMemberships->contains($tipGroupMembership)) {
        $this->tipGroupMemberships->add($tipGroupMembership);
    }
    return $this;
  }
  
  public function removeTipGroupMembership(TipGroupMember $tipGroupMembership) {
    if ($this->tipGroupMemberships->contains($tipGroupMembership)) {
        $this->tipGroupMemberships->removeElement($tipGroupMembership);
    }
    return $this;
  }
  
  public function hasTipGroupMembership(TipGroupMember $tipGroupMembership) {
    return $this->tipGroupMemberships->contains($tipGroupMembership);
  }
  
  public function __construct() {
    parent::__construct();
    $this->tipps = new ArrayCollection();
    $this->tipGroupMemberships = new ArrayCollection();
  }
}
