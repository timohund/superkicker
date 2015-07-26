<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;
use Webforge\Common\DateTime\DateTime;

/**
 * Represents a match in the tip model
 * 
 * this entity was compiled from Webforge\Doctrine\Compiler
 * @ORM\Entity
 * @ORM\Table(name="matches")
 */
class Match extends CompiledMatch {
	/**
	* @return boolean
	*/
  public function getIsStarted(){
	   return $this->getDate()->isBefore(new DateTime());
  }
}
