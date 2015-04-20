<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Club;

class ClubRepository extends AbstractRepository{

	/**
	 * @return \Doctrine\ORM\PersistentCollection<Ts\Superkicker\SuperkickerBundle\Club>
	 */
	public function findAll() {
//		return $this->entityManager->getRepository('Ts\Superkicker\SuperkickerBundle\Domain\Model\Club')->findAll();
		$result = new ArrayCollection();

		$bvb = new Club();
		$bvb->setName('BVB');
		$bvb->setId(1);

		$fcb = new Club();
		$fcb->setName('FC Bayern München');
		$fcb->setId(2);

		$koeln = new Club();
		$koeln->setName('1. FC Köln');
		$koeln->setId(3);

		$mainz = new Club();
		$mainz->setName('1. FC Mainz');
		$mainz->setId(4);

		$result->add($bvb);
		$result->add($fcb);
		$result->add($koeln);
		$result->add($mainz);

		return $result;
	}

	public function findById($id) {
		$mainz = new Club();
		$mainz->setName('1. FC Mainz');
		$mainz->setId(4);
		return $mainz;
	}
}