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
	 * @var integer
	 */
	protected $tipCountPerTournament;

	/**
	 * @var integer
	 */
	protected $position;

	/**
	 * @return int
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param int $position
	 */
	public function setPosition($position) {
		$this->position = $position;
	}

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

	/**
	 * @return int
	 */
	public function getTipCountPerTournament() {
		return $this->tipCountPerTournament;
	}

	/**
	 * @param int $tipCountPerTournament
	 */
	public function setTipCountPerTournament($tipCountPerTournament) {
		$this->tipCountPerTournament = $tipCountPerTournament;
	}
}