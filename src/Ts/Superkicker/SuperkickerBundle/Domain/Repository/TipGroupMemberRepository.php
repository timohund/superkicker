<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;

use Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;

class TipGroupMemberRepository extends AbstractRepository{

	/**
	 * Should return the classname of the entity that this
	 * repository should retrieve.
	 *
	 * @return string
	 */
	protected function getEntityClassName() {
		return 'Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember';
	}

	/**
	 * @param TipGroupMember $tipGroupMember
	 */
	public function save(TipGroupMember $tipGroupMember) {
		$this->entityManager->persist($tipGroupMember);
		$this->entityManager->flush();
	}

	/**
	 *
	 * @param User $user
	 * @param TipGroup $tipGroup
	 * @return null|TipGroupMember
	 */
	public function findFirstMembershipForUserAndTipGroup(User $user, TipGroup $tipGroup) {
		$dql ='SELECT m FROM Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroupMember m '.
				'LEFT JOIN m.user u '.
				'LEFT JOIN m.tipGroup tg '.
				'WHERE u.id = ' .intval($user->getId()).' AND tg.id = '.intval($tipGroup->getId());

		$membership = $this->entityManager->createQuery($dql)->getOneOrNullResult();
		return $membership;
	}
}