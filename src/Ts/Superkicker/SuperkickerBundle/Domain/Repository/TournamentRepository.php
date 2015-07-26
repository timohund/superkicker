<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Club;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament;

class TournamentRepository extends AbstractRepository{


	/**
	 * Should return the classname of the entity that this
	 * repository should retrieve.
	 *
	 * @return string
	 */
	protected function getEntityClassName() {
		return 'Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament';
	}

	/**
	 * @param Tournament $tournament
	 */
	public function save(Tournament $tournament) {
		$this->entityManager->persist($tournament);
		$this->entityManager->flush();
	}

	/**
	 * @return \Doctrine\ORM\PersistentCollection<Ts\Superkicker\SuperkickerBundle\Tournament>
	 */
	public function findAll() {
		return $this->getDoctrineRepository()->findAll();
	}
}