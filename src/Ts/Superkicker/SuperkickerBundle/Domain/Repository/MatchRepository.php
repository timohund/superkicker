<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Club;
use Webforge\Common\DateTime\DateTime;

class MatchRepository extends AbstractRepository{

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
	 * @param integer $matchDay
	 * @param integer $tournamentId
	 * @return \Doctrine\ORM\PersistentCollection<Ts\Superkicker\SuperkickerBundle\Match>
	 */
	public function findByMatchDayAndTournament($matchDay, $tournamentId) {
		return $this->getDoctrineRepository()->findBy(
				array('matchDay' => $matchDay, 'tournament' => $tournamentId)
		);
	}

	/**
	 * @param Match $match
	 */
	public function save(Match $match) {
		$this->entityManager->persist($match);
		$this->entityManager->flush();
	}

	/**
	 * @return \Doctrine\ORM\PersistentCollection<Ts\Superkicker\SuperkickerBundle\Match>
	 */
	public function findAllOrderByMatchDay() {
		return $this->getDoctrineRepository()->findBy(array(),array('matchDay' => 'asc'));
	}
}