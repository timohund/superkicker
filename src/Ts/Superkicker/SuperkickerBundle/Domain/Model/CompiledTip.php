<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledTip {
  
  /**
   * id
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;
  
  /**
   * match
   * @ORM\ManyToOne(targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\Match")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $match;
  
  /**
   * homeScore
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $homeScore;
  
  /**
   * guestScore
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $guestScore;
  
  /**
   * user
   * @ORM\ManyToOne(targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\User", inversedBy="tipps")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $user;
  
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
   * @param Ts\Superkicker\SuperkickerBundle\Domain\Model\Match $match
   */
  public function setMatch(Match $match) {
    $this->match = $match;
    return $this;
  }
  
  /**
   * @return Ts\Superkicker\SuperkickerBundle\Domain\Model\Match
   */
  public function getMatch() {
    return $this->match;
  }
  
  /**
   * @param integer $homeScore
   */
  public function setHomeScore($homeScore = NULL) {
    $this->homeScore = $homeScore;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getHomeScore() {
    return $this->homeScore;
  }
  
  /**
   * @param integer $guestScore
   */
  public function setGuestScore($guestScore = NULL) {
    $this->guestScore = $guestScore;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getGuestScore() {
    return $this->guestScore;
  }
  
  /**
   * @param Ts\Superkicker\SuperkickerBundle\Domain\Model\User $user
   */
  public function setUser(User $user) {
    if (isset($this->user) && $this->user !== $user) {
        $this->user->removeTipp($this);
    }
    $this->user = $user;
    $user->addTipp($this);
    return $this;
  }
  
  /**
   * @return Ts\Superkicker\SuperkickerBundle\Domain\Model\User
   */
  public function getUser() {
    return $this->user;
  }
  
  public function __construct() {

  }
}
