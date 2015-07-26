<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Ranking;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;
use Ts\Superkicker\SuperkickerBundle\Domain\Service\RankingSort;
use Webforge\Common\DateTime\DateTime;

class RankingController extends AbstractController {

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\UserRepository
	 */
	protected $userRepository;

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
	 * @param EngineInterface $templating
	 * @param Router $router
	 */
	public function __construct(EngineInterface $templating, Router $router) {
		$this->templating = $templating;
		$this->router = $router;
	}

	/**
	 * @return \Ts\Superkicker\SuperkickerBundle\Domain\Repository\UserRepository
	 */
	public function getUserRepository() {
		return $this->userRepository;
	}

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Repository\UserRepository $userRepository
	 */
	public function setUserRepository($userRepository) {
		$this->userRepository = $userRepository;
	}

	/**
	 * @param int $tournamentId
	 * @return Response
	 */
	public function showAction($tournamentId = 0) {
		$usersWithSameClient = $this->userRepository->findByClientId($this->getCurrentLoginUser()->getClientId());

		$tournament = $this->getTournamentRepository()->findById($tournamentId);
		$rankings = array();
		foreach($usersWithSameClient as $user) {
			$score = $this->scoreCalculationService->getScoreForUserInTournament($user,$tournament);

			$ranking = new Ranking();
			$ranking->setUser($user);
			$ranking->setScore($score);
			$rankings[] = $ranking;
		}


		$rankings = RankingSort::sort($rankings);
		return $this->templating->renderResponse(
				'SuperkickerBundle:Ranking:show.html.twig',
				array(
					'rankings' => $rankings,
					'tournaments' => $this->getAllTournaments()
				)
		);
	}
}