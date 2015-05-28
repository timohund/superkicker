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

class TipRepository extends AbstractRepository{

	/**
	 * Should return the classname of the entity that this
	 * repository should retrieve.
	 *
	 * @return string
	 */
	protected function getEntityClassName() {
		return 'Ts\Superkicker\SuperkickerBundle\Domain\Model\Match';
	}

	/**
	 * @param Tip $tip
	 */
	public function save(Tip $tip) {
		$this->entityManager->getClassMetaData(get_class($tip->getUser()))->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
		$this->entityManager->persist($tip->getUser());

		$this->entityManager->persist($tip);
		$this->entityManager->flush();
	}

	/**
	 * @param User $user
	 * @param int $matchDay
	 * @return \Doctrine\ORM\ArrayCollection
	 */
	public function findByUserAndMatchDay(User $user, $matchDay) {
		$dql ='SELECT t FROM Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip t '.
				'LEFT JOIN t.user u '.
				'LEFT JOIN t.match m '.
				'WHERE m.matchDay = '.intval($matchDay).' AND u.id = '.intval($user->getId());
		$userTips = $this->entityManager
			->createQuery($dql)->getResult();

		return $userTips;
	}

	/**
	 * @param User $user
	 * @param Match $match
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findOneByUserAndMatch(User $user, Match $match) {
		$dql ='SELECT t FROM Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip t '.
				'LEFT JOIN t.user u '.
				'LEFT JOIN t.match m '.
				'WHERE m = '.intval($match->getId()).' AND u.id ='.intval($user->getId());
		return $this->entityManager->createQuery($dql)->getOneOrNullResult();
	}

}