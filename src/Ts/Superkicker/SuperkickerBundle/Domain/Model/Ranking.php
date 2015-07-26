<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Model;

/**
 * Simple value object to hold the ranking of a user.
 *
 * Class Ranking
 * @package Ts\Superkicker\SuperkickerBundle\Domain\Model
 */
class Ranking {

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var integer
	 */
	protected $score;

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @return int
	 */
	public function getScore() {
		return $this->score;
	}

	/**
	 * @param int $score
	 */
	public function setScore($score) {
		$this->score = $score;
	}
}