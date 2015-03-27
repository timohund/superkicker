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
}