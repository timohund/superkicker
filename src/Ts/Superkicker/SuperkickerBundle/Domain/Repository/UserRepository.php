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
}