<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;

class DashboardController extends AbstractController {

	/**
	 * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
	 */
	protected $templating;

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
	 */
	public function __construct(EngineInterface $templating) {
		$this->templating = $templating;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction() {
		$score = $this->scoreCalculationService->getOverallScoreForUser($this->getCurrentLoginUser());
		return $this->templating->renderResponse(
			'SuperkickerBundle:Dashboard:index.html.twig',
			array(
				'tournaments' => $this->getAllTournaments(),
				'score' => $score
			)
		);
	}
}