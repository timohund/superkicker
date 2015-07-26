<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Webforge\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledTournament {
  
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
   * shortname
   * @ORM\Column(nullable=true)
   */
  protected $shortname;
  
  /**
   * clubs
   * @ORM\ManyToMany(targetEntity="Ts\Superkicker\SuperkickerBundle\Domain\Model\Club", cascade={"all"})
   * @ORM\JoinTable(name="tournaments2clubs", joinColumns={@ORM\JoinColumn(name="tournaments_id", onDelete="cascade")}, inverseJoinColumns={@ORM\JoinColumn(name="clubs_id", onDelete="cascade")})
   */
  protected $clubs;
  
  /**
   * matchDays
   * @ORM\Column(type="integer")
   */
  protected $matchDays;
  
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
   * @param string $shortname
   */
  public function setShortname($shortname = NULL) {
    $this->shortname = $shortname;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getShortname() {
    return $this->shortname;
  }
  
  /**
   * @param Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\Club> $clubs
   */
  public function setClubs(ArrayCollection $clubs) {
    $this->clubs = $clubs;
    return $this;
  }
  
  /**
   * @return Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\Club>
   */
  public function getClubs() {
    return $this->clubs;
  }
  
  /**
   * @param integer $matchDays
   */
  public function setMatchDays($matchDays) {
    $this->matchDays = $matchDays;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getMatchDays() {
    return $this->matchDays;
  }
  
  public function addClub(Club $club) {
    if (!$this->clubs->contains($club)) {
        $this->clubs->add($club);
    }
    return $this;
  }
  
  public function removeClub(Club $club) {
    if ($this->clubs->contains($club)) {
        $this->clubs->removeElement($club);
    }
    return $this;
  }
  
  public function hasClub(Club $club) {
    return $this->clubs->contains($club);
  }
  
  public function __construct() {
    $this->clubs = new ArrayCollection();
  }
}
