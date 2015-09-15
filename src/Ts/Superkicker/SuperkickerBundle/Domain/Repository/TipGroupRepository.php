<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;

use Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup;

class TipGroupRepository extends AbstractRepository{

	/**
	 * Should return the classname of the entity that this
	 * repository should retrieve.
	 *
	 * @return string
	 */
	protected function getEntityClassName() {
		return 'Ts\Superkicker\SuperkickerBundle\Domain\Model\TipGroup';
	}

	/**
	 * @param TipGroup $tipGroup
	 */
	public function save(TipGroup $tipGroup) {
		$this->entityManager->persist($tipGroup);
		$this->entityManager->flush();
	}
}