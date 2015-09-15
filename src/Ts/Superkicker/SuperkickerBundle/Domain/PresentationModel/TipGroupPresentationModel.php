<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\PresentationModel;

use Ts\Superkicker\SuperkickerBundle\Domain\Model;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember;

class TipGroupPresentationModel {

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Model\User
	 */
	protected $currentUser;

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup
	 */
	protected $tipGroup;

	/**
	 * @var boolean
	 */
	protected $currentUserHasAdminRole = FALSE;

	/**
	 * @var boolean
	 */
	protected $currentUserHasMemberRole = FALSE;

	/**
	 * @var boolean
	 */
	protected $currentUserHasInvitedRole = FALSE;

	/**
	 * @var boolean
	 */
	protected $currentUserHasMembershipRequested = FALSE;

	/**
	 * @var boolean
	 */
	protected $currentUserHasNoRole = TRUE;

	/**
	 * @return boolean
	 */
	public function isCurrentUserHasMemberRole() {
		$this->buildCurrentUserRoles();
		return $this->currentUserHasMemberRole;
	}

	/**
	 * @return boolean
	 */
	public function isCurrentUserHasInvitedRole() {
		$this->buildCurrentUserRoles();
		return $this->currentUserHasInvitedRole;
	}

	/**
	 * @return boolean
	 */
	public function isCurrentUserHasMembershipRequested() {
		$this->buildCurrentUserRoles();
		return $this->currentUserHasMembershipRequested;
	}

	/**
	 * @return boolean
	 */
	public function getCurrentUserHasAdminRole() {
		$this->buildCurrentUserRoles();
		return $this->currentUserHasAdminRole;
	}

	/**
	 * @return boolean
	 */
	public function isCurrentUserHasNoRole() {
		$this->buildCurrentUserRoles();

		return $this->currentUserHasNoRole;
	}

	/**
	 * @return void
	 */
	protected function buildCurrentUserRoles() {
		$this->currentUserHasAdminRole = false;
		$this->currentUserHasMemberRole = false;
		$this->currentUserHasInvitedRole = false;
		$this->currentUserHasMembershipRequested = false;

		$member = $this->getMembership();
		if($member == null) {
			return;
		}

		$this->currentUserHasAdminRole = $member->getIsAdmin();
		$this->currentUserHasMemberRole = $member->getIsMember();
		$this->currentUserHasInvitedRole = $member->getIsInvited();
		$this->currentUserHasMembershipRequested = $member->getIsMembershipRequested();
		$this->currentUserHasNoRole = false;
	}

	/**
	 * @return null|TipGroupMember
	 */
	public function getMembership() {
		foreach($this->tipGroup->getMembers() as $member) {
			/** @var $member TipGroupMember */
			if ($member->getUser() == $this->currentUser) {
				return $member;
			}
		}
		return null;
	}

	/**
	 * @return Model\TipGroup
	 */
	public function getTipGroup() {
		return $this->tipGroup;
	}

	/**
	 * @param Model\TipGroup $tipGroup
	 */
	public function setTipGroup($tipGroup) {
		$this->tipGroup = $tipGroup;
	}

	/**
	 * @return Model\User
	 */
	public function getCurrentUser() {
		return $this->currentUser;
	}

	/**
	 * @param Model\User $currentUser
	 */
	public function setCurrentUser($currentUser) {
		$this->currentUser = $currentUser;
	}
}