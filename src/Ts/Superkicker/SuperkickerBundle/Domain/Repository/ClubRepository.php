<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Club;

class ClubRepository extends AbstractRepository{


	/**
	 * Should return the classname of the entity that this
	 * repository should retrieve.
	 *
	 * @return string
	 */
	protected function getEntityClassName() {
		return 'Ts\Superkicker\SuperkickerBundle\Domain\Model\Club';
	}

	/**
	 * @param Club $club
	 */
	public function save(Club $club) {
		$this->entityManager->persist($club);
		$this->entityManager->flush();
	}
}