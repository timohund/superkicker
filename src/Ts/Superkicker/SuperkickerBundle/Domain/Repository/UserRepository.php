<?php
/**
 * Created by PhpStorm.
 * User: timoschmidt
 * Date: 13.03.15
 * Time: 18:29
 */

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;


use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;

class UserRepository extends AbstractRepository {

	/**
	 * Should return the classname of the entity that this
	 * repository should retrieve.
	 *
	 * @return string
	 */
	protected function getEntityClassName() {
		return 'Ts\Superkicker\SuperkickerBundle\Domain\Model\User';
	}

	/**
	 * @param integer $clientId
	 * @return \Doctrine\ORM\ArrayCollection<User>
	 */
	public function findByClientId($clientId) {
		return $this->getDoctrineRepository()->findBy(array('clientId' => $clientId));
	}

	/**
	 * @param TipGroup $tipGroup
	 * @return \Doctrine\ORM\ArrayCollection<User>
	 */
	public function findUsersNotAssignedToATipGroup(TipGroup $tipGroup) {
		$dql ='SELECT u FROM Ts\Superkicker\SuperkickerBundle\Domain\Model\User u WHERE u.id NOT IN (SELECT ui.id FROM Ts\Superkicker\SuperkickerBundle\Domain\Model\User ui '.
				'LEFT JOIN ui.tipGroupMemberships m '.
				'WHERE m.tipGroup = '.intval($tipGroup->getId()).')';
		$unassignedUsers = $this->entityManager
				->createQuery($dql)->getResult();

		return $unassignedUsers;
	}
}