<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;


use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;

class DashboardController extends AbstractController {

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Service\ScoreCalculationService
	 */
	protected $scoreCalculationService;

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Service\ScoreCalculationService $scoreCalculationService
	 */
	public function setScoreCalculationService($scoreCalculationService) {
		$this->scoreCalculationService = $scoreCalculationService;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction() {
		$tournamentsAndScores = array();
		$allTournaments = $this->getAllTournaments();

		foreach($allTournaments as $tournament) {
			/** @var $tournament Tournament */
			$tournamentAndScore = array(
				'tournament' => $tournament,
				'score' => $this->scoreCalculationService->getScoreForUserInTournament(
						$this->getCurrentLoginUser(),
						$tournament
				)
			);

			$tournamentsAndScores[] = $tournamentAndScore;
		}

		return $this->templating->renderResponse(
			'SuperkickerBundle:Dashboard:index.html.twig',
			array(
				'username' => $this->getCurrentLoginUser()->getUsername(),
				'tournamentsAndScores' => $tournamentsAndScores
			)
		);
	}
}