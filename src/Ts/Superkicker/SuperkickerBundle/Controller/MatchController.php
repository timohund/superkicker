<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;

class MatchController extends AbstractController {

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\MatchRepository
	 */
	protected $matchRepository;

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\ClubRepository
	 */
	protected $clubRepository;

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
	 * @return \Ts\Superkicker\SuperkickerBundle\Domain\Repository\MatchRepository
	 */
	public function getMatchRepository() {
		return $this->matchRepository;
	}

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Repository\MatchRepository
	 */
	public function setMatchRepository($matchRepository) {
		$this->matchRepository = $matchRepository;
	}

	/**
	 * @return \Ts\Superkicker\SuperkickerBundle\Domain\Repository\ClubRepository
	 */
	public function getClubRepository() {
		return $this->clubRepository;
	}

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Repository\ClubRepository $clubRepository
	 */
	public function setClubRepository($clubRepository) {
		$this->clubRepository = $clubRepository;
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
	 * @param int $match
	 * @param int $saved
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction($match = 0, $saved = 0) {
		$allClubs = $this->clubRepository->findAll();
		return $this->templating->renderResponse(
			'SuperkickerBundle:Match:edit.html.twig',
			array(
					'matchId' => $match,
					'allClubs' => $allClubs
			)
		);
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction() {
		$allMatches = $this->matchRepository->findAll();
		return $this->templating->renderResponse(
			'SuperkickerBundle:Match:list.html.twig',
			array(
				'allMatches' => $allMatches
			)
		);

	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function saveAction(Request $request) {

		$matchData = $request->get('match');
		$match = new Match();
		$match->setHomeClub( $this->clubRepository->findById($matchData['home']) );
		$match->setGuestClub( $this->clubRepository->findById($matchData['guest']) );
		$match->setMatchDay($matchData['day']);

		$this->matchRepository->save($match);
		$editUrl = $this->router->generate('ts_superkicker_match_list');
		return new RedirectResponse($editUrl);
	}
}