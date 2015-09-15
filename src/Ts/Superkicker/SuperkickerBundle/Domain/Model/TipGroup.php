<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * Represents a group of users that want to tip together
 * 
 * this entity was compiled from Webforge\Doctrine\Compiler
 * @ORM\Entity
 * @ORM\Table(name="tip_groups")
 */
class TipGroup extends CompiledTipGroup {

	/**
	 * @return \Doctrine\Common\Collections\Collection<Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember>
	 */
	public function getAdminMembers() {
		$members = $this->getMembers();
		$result = new ArrayCollection();
		foreach($members as $member) {
			if($member->getIsAdmin()) {
				$result->add($member);
			}
		}

		return $result;
	}
}
