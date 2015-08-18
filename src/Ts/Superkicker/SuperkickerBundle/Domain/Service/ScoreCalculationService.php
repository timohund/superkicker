<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Service;

use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;

class ScoreCalculationService {

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TipRepository
	 */
	protected $tipRepository;

	/**
	 * @return \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TipRepository
	 */
	public function getTipRepository() {
		return $this->tipRepository;
	}

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TipRepository $tipRepository
	 */
	public function setTipRepository($tipRepository) {
		$this->tipRepository = $tipRepository;
	}

	/**
	 * @param User $user
	 * @return int
	 */
	public function getOverallScoreForUser(User $user) {
		$scoreSum = 0;
		$tips = $user->getTipps();
		foreach($tips as $tip) {
			$scoreSum += $this->getScoreForSingleTip($tip);
		}

		return $scoreSum;
	}

	/**
	 * @param User $user
	 * @param Tournament $tournament
	 * @return int
	 */
	public function getScoreForUserInTournament(User $user, Tournament $tournament) {
		$scoreSum = 0;
		$tips = $this->tipRepository->findByUserAndTournament($user, $tournament);
		foreach($tips as $tip) {
			$scoreSum += $this->getScoreForSingleTip($tip);
		}
		return $scoreSum;
	}

	/**
	 * @param Tip $tip
	 * @return int
	 */
	public function getScoreForSingleTip(Tip $tip) {
		/** @var $tip Tip */
		$match = $tip->getMatch();


		$matchGuestScore = $match->getGuestScore();
		$matchHomeScore = $match->getHomeScore();

		$tipHomeScore = $tip->getHomeScore();
		$tipGuestScore = $tip->getGuestScore();

		// Score calculation only makes sense when all score have value
		if($matchHomeScore === null ||  $matchHomeScore === null || $tipHomeScore === null || $tipGuestScore === null) {
			return 0;
		}


		$isPerfectTip = (($matchHomeScore === $tipHomeScore) && ($matchGuestScore === $tipGuestScore));
		if($isPerfectTip) {
			return 5;
		}

		// correct tendence for even score
		if($matchGuestScore == $matchHomeScore) {
			if($tipGuestScore == $tipHomeScore) {
				return 3;
			}
		} else {
			if($tipGuestScore == $tipHomeScore) {
				return 0;
			}
			$isTendenceBelow = ((($matchHomeScore < $tipHomeScore) && ($matchGuestScore < $tipGuestScore)) );
			$isTendenceAbove =	((($matchHomeScore > $tipHomeScore) && ($matchGuestScore > $tipGuestScore)) );
			$isTendence = (($isTendenceAbove || $isTendenceBelow) && $matchGuestScore !== $tipGuestScore);
			if($isTendence) {
				return 3;
			}
		}


		return 0;
	}
}