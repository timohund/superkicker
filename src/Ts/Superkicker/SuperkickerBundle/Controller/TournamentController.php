<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Club;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament;

class TournamentController extends AbstractController {


	/**
	 * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
	 */
	protected $templating;

	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	/**
	 * @var \Symfony\Component\HttpFoundation\Response
	 */
	protected $response;

	/**
	 * @param EngineInterface $templating
	 * @param Router $router
	 */
	public function __construct(EngineInterface $templating, Router $router) {
		$this->templating = $templating;
		$this->router = $router;
	}

	/**
	 * @param int $saved
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction($saved = 0) {
		return $this->templating->renderResponse(
				'SuperkickerBundle:Tournament:edit.html.twig',
				array(
						'tournaments' => $this->getAllTournaments(),
						'saved' => $saved
				)
		);
	}


	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function saveAction(Request $request) {
		$tournamentsData = $request->get('tournaments');

		foreach($tournamentsData as $id => $tournamentData) {
			if($id == 'new' && trim($tournamentData['name']) !== ''){
				$tournament = new Tournament();
			} else {
				$tournament = $this->tournamentRepository->findById($id);
			}

			if($tournament === null) {
				continue;
			}

			$tournament->setName($tournamentData['name']);
			$tournament->setMatchDays($tournamentData['matchdays']);

			$this->tournamentRepository->save($tournament);
		}

		$editUrl = $this->router->generate('ts_superkicker_tournament_edit',array('saved' => 1));
		return new RedirectResponse($editUrl);
	}
}