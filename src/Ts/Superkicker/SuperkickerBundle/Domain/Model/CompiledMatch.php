<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;
use Webforge\Common\DateTime\DateTime;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\Match
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\Match class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledMatch {
  
  /**
   * id
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;
  
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
   * homeSoccerClub
   * @ORM\ManyToOne(targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\SoccerClub")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $homeSoccerClub;
  
  /**
   * guestSoccerClub
   * @ORM\ManyToOne(targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\SoccerClub")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $guestSoccerClub;
  
  /**
   * date
   * @ORM\Column(type="WebforgeDateTime", nullable=true)
   */
  protected $date;
  
  /**
   * matchDay
   * @ORM\Column(type="integer")
   */
  protected $matchDay;
  
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
   * @param Ts\Superkicker\SuperkickerBundle\Domain\Model\SoccerClub $homeSoccerClub
   */
  public function setHomeSoccerClub(SoccerClub $homeSoccerClub) {
    $this->homeSoccerClub = $homeSoccerClub;
    return $this;
  }
  
  /**
   * @return Ts\Superkicker\SuperkickerBundle\Domain\Model\SoccerClub
   */
  public function getHomeSoccerClub() {
    return $this->homeSoccerClub;
  }
  
  /**
   * @param Ts\Superkicker\SuperkickerBundle\Domain\Model\SoccerClub $guestSoccerClub
   */
  public function setGuestSoccerClub(SoccerClub $guestSoccerClub) {
    $this->guestSoccerClub = $guestSoccerClub;
    return $this;
  }
  
  /**
   * @return Ts\Superkicker\SuperkickerBundle\Domain\Model\SoccerClub
   */
  public function getGuestSoccerClub() {
    return $this->guestSoccerClub;
  }
  
  /**
   * @param Webforge\Common\DateTime\DateTime $date
   */
  public function setDate(DateTime $date = NULL) {
    $this->date = $date;
    return $this;
  }
  
  /**
   * @return Webforge\Common\DateTime\DateTime
   */
  public function getDate() {
    return $this->date;
  }
  
  /**
   * @param integer $matchDay
   */
  public function setMatchDay($matchDay) {
    $this->matchDay = $matchDay;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getMatchDay() {
    return $this->matchDay;
  }
  
  public function __construct() {

  }
}
