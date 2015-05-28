<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;

use \Doctrine\ORM\EntityManager;

abstract class AbstractRepository {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
	 * Should return the classname of the entity that this
	 * repository should retrieve.
	 *
	 * @return string
	 */
	abstract protected function getEntityClassName();

	/**
	 * @return \Doctrine\ORM\EntityRepository
	 */
	protected function getDoctrineRepository() {
		return $this->entityManager->getRepository($this->getEntityClassName());
	}

	/**
	 * @return \Doctrine\ORM\PersistentCollection<Ts\Superkicker\SuperkickerBundle\Club>
	 */
	public function findAll() {
		return $this->getDoctrineRepository()->findAll();
	}

	/**
	 * @param $id
	 * @return null|object
	 */
	public function findById($id) {
		return $this->getDoctrineRepository()->findOneBy(
				array('id' => $id)
		);
	}

	/**
	 * @return void
	 */
	public function deleteAll() {
		$this->getDoctrineRepository()->clear();
	}

}