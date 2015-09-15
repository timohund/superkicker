<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;
use Ts\Superkicker\SuperkickerBundle\Domain\PresentationModel\TipGroupPresentationModel;
use Ts\Superkicker\SuperkickerBundle\Exception\SecurityException;

class TipGroupController extends AbstractController {

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TipGroupRepository
	 */
	protected $tipGroupRepository;

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TipGroupMemberRepository
	 */
	protected $tipGroupMemberRepository;

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TipGroupRepository $tipGroupRepository
	 */
	public function setTipGroupRepository($tipGroupRepository) {
		$this->tipGroupRepository = $tipGroupRepository;
	}

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Repository\UserRepository $userRepository
	 */
	public function setUserRepository($userRepository) {
		$this->userRepository = $userRepository;
	}

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TipGroupMemberRepository $tipGroupMemberRepository
	 */
	public function setTipGroupMemberRepository($tipGroupMemberRepository) {
		$this->tipGroupMemberRepository = $tipGroupMemberRepository;
	}

	/**
	 * @param int $saved
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction($saved = 0) {
		$allGroups = $this->tipGroupRepository->findAll();

		$groupPresentations = array();
		foreach($allGroups as $group) {
			$groupPresentation = $this->getPresentationModelForTipGroup($group);
			$groupPresentations[] = $groupPresentation;
		}
		return $this->templating->renderResponse(
			'SuperkickerBundle:TipGroup:edit.html.twig',
			array(
				'groupPresentations' => $groupPresentations,
				'saved' => $saved
			)
		);
	}

	/**
	 * @param integer $tipGroupId
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws SecurityException
	 */
	public function manageSingleAction($tipGroupId) {
		$group = $this->tipGroupRepository->findById($tipGroupId);
		$groupPresentation = $this->getPresentationModelForTipGroup($group);
		$unassignedUsers = $this->userRepository->findUsersNotAssignedToATipGroup($group);
		$this->validateThatCurrentUserIsAdminOfTipGroup($group);

		return $this->templating->renderResponse(
			'SuperkickerBundle:TipGroup:manage_single.html.twig',
			array(
				'groupPresentation' => $groupPresentation,
				'unassignedUsers' => $unassignedUsers
			)
		);

	}

	/**
	 * @param integer $tipGroupId
	 * @return RedirectResponse
	 * @throws SecurityException
	 */
	public function requestMembershipAction($tipGroupId) {
		$editUrl = $this->router->generate('ts_superkicker_tipgroup_edit');

		$group = $this->tipGroupRepository->findById($tipGroupId);
		$membership = $this->tipGroupMemberRepository->findFirstMembershipForUserAndTipGroup(
				$this->getCurrentLoginUser(), $group
		);

		if($membership != NULL) {
			throw new SecurityException("The user is allready assigned to this group", 1442249622);
		}

		$this->addMembership($tipGroupId, TipGroupMember::ROLE_MEMBERSHIP_REQUESTED, $this->getCurrentLoginUser());

		return new RedirectResponse($editUrl);
	}

	/**
	 * @param integer $tipGroupId
	 * @return RedirectResponse
	 */
	public function removeMembershipAction($tipGroupId) {
		$editUrl = $this->router->generate('ts_superkicker_tipgroup_edit');

		/**
		 * @var $tipGroup TipGroup
		 */
		$tipGroup = $this->tipGroupRepository->findById($tipGroupId);

		$members = $tipGroup->getMembers();
		foreach($members as $member) {
			if($member->getUser() == $this->getCurrentLoginUser()) {
				$tipGroup->removeMember($member);
				$member->setTipGroup();
				$member->setUser();
				break;
			}
		}

		$this->tipGroupRepository->save($tipGroup);
		return new RedirectResponse($editUrl);
	}

	/**
	 * @param integer $tipGroupMemberId
	 * @return RedirectResponse
	 * @throws SecurityException
	 */
	public function acceptInvitationAction($tipGroupMemberId) {
		$editUrl = $this->router->generate('ts_superkicker_tipgroup_edit');

		/** @var  $member TipGroupMember */
		$member = $this->tipGroupMemberRepository->findById($tipGroupMemberId);
		$member->setRole(TipGroupMember::ROLE_MEMBER);

		if($member->getUser()->getId() !== $this->getCurrentLoginUser()->getId()) {
			throw new SecurityException("You are not the invited used", 1442249622);
		}

		$this->tipGroupMemberRepository->save($member);
		return new RedirectResponse($editUrl);
	}

	/**
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function inviteUserAction(Request $request) {
		$tipGroupId = $request->get('tipGroupId');
		$userId = $request->get('userId');

		$group = $this->tipGroupRepository->findById($tipGroupId);
		$this->validateThatCurrentUserIsAdminOfTipGroup($group);

		$user = $this->userRepository->findById($userId);
		$this->addMembership($tipGroupId, TipGroupMember::ROLE_INVITED, $user);

		$manageSingleUrl = $this->router->generate('ts_superkicker_tipgroup_manage_single',
				array('tipGroupId' => $tipGroupId)
		);
		return new RedirectResponse($manageSingleUrl);
	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function saveAction(Request $request) {
		$editUrl = $this->router->generate('ts_superkicker_tipgroup_edit',
				array(
						'saved' => true
				));

		$tipGroupsData = $request->get('tipgroups');

		foreach($tipGroupsData as $id => $tipGroupData) {
			if ($id == 'new' && trim($tipGroupData['name']) !== '') {
				$tipGroup = new TipGroup();
				$memberShip = new TipGroupMember();
				$memberShip->setUser($this->getCurrentLoginUser());
				$memberShip->setTipGroup($tipGroup);
				$memberShip->setRole(TipGroupMember::ROLE_ADMIN);
				$tipGroup->addMember($memberShip);
			} else {
				$tipGroup = $this->tipGroupRepository->findById($id);
			}

			if($tipGroup === null) {
				continue;
			}

			$tipGroup->setName($tipGroupData['name']);
			$this->tipGroupRepository->save($tipGroup);
		}

		return new RedirectResponse($editUrl);
	}

	/**
	 * @param $group
	 * @return TipGroupPresentationModel
	 */
	protected function getPresentationModelForTipGroup($group) {
		$groupPresentation = new TipGroupPresentationModel();
		$groupPresentation->setTipGroup($group);
		$groupPresentation->setCurrentUser($this->getCurrentLoginUser());
		return $groupPresentation;
	}

	/**
	 * @param integer $tipGroupId
	 * @param integer $role
	 * @param User $user
	 */
	protected function addMembership($tipGroupId, $role, $user) {
		/**
		 * @var $tipGroup TipGroup
		 */
		$tipGroup = $this->tipGroupRepository->findById($tipGroupId);

		$memberShip = new TipGroupMember();
		$memberShip->setUser($user);
		$memberShip->setTipGroup($tipGroup);
		$memberShip->setRole($role);
		$tipGroup->addMember($memberShip);
		$this->tipGroupRepository->save($tipGroup);
	}

	/**
	 * @param $group
	 * @throws SecurityException
	 */
	protected function validateThatCurrentUserIsAdminOfTipGroup($group) {
		$membership = $this->tipGroupMemberRepository->findFirstMembershipForUserAndTipGroup(
				$this->getCurrentLoginUser(), $group
		);

		if ($membership == NULL || !$membership->getIsAdmin()) {
			throw new SecurityException("You are not the admin of this group", 1442249422);
		}
	}
}