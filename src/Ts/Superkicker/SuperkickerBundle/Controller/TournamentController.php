<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Club;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament;

class TournamentController extends AbstractController {

	/**
	 * @param int $saved
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction($saved = 0) {
		return $this->templating->renderResponse(
				'SuperkickerBundle:Tournament:edit.html.twig',
				array(
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