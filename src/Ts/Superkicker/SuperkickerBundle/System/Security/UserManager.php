<?php
namespace Ts\Superkicker\SuperkickerBundle\System\Security;

use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;

/**
 * Class UserManager
 *
 * @author Andrzej Ośmiałowski <andrzej@osmialowski.co.uk>
 */
class UserManager extends BaseUserManager {
	/**
	 * Find user by given username or email
	 *
	 * @param string $username
	 * @param string $email
	 * @return mixed
	 */
	public function findUserByUsernameAndEmail($username, $email) {
		return $this->repository->createQueryBuilder('u')
				->where('u.username = :username')
				->orWhere('u.email = :email')
				->setParameter('username', $username)
				->setParameter('email', $email)
				->getQuery()
				->getOneOrNullResult();
	}
}