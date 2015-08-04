<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;

/**
 * Compiled Entity for Ts\Superkicker\SuperkickerBundle\Domain\Model\Club
 * 
 * To change table name or entity repository edit the Ts\Superkicker\SuperkickerBundle\Domain\Model\Club class.
 * @ORM\MappedSuperclass
 */
abstract class CompiledClub {
  
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
   * logoPath
   * @ORM\Column(nullable=true)
   */
  protected $logoPath;
  
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
   * @param string $logoPath
   */
  public function setLogoPath($logoPath = NULL) {
    $this->logoPath = $logoPath;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getLogoPath() {
    return $this->logoPath;
  }
  
  public function __construct() {

  }
}
