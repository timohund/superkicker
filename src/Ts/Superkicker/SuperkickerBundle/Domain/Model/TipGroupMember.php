<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

use Doctrine\ORM\Mapping AS ORM;

/**
 * Represents the relation of a user to a tip group
 * 
 * this entity was compiled from Webforge\Doctrine\Compiler
 * @ORM\Entity
 * @ORM\Table(name="tip_group_members")
 */
class TipGroupMember extends CompiledTipGroupMember {

	const ROLE_NONE = 0;

	const ROLE_MEMBERSHIP_REQUESTED = 1;

	const ROLE_INVITED = 2;

	const ROLE_MEMBER = 4;

	const ROLE_ADMIN = 8;

	/**
	 * role
	 * @ORM\Column(type="integer")
	 */
	protected $role = self::ROLE_INVITED;

	/**
	 * @return boolean
	 */
	public function getIsAdmin() {
		return $this->role == self::ROLE_ADMIN;
	}

	/**
	 * @return boolean
	 */
	public function getIsMember() {
		return $this->role == self::ROLE_MEMBER;
	}

	/**
	 * @return boolean
	 */
	public function getIsInvited() {
		return $this->role == self::ROLE_INVITED;
	}

	/**
	 * @return boolean
	 */
	public function getIsMembershipRequested() {
		return $this->role == self::ROLE_MEMBERSHIP_REQUESTED;
	}

}
