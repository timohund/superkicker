<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;
use Webforge\Common\DateTime\DateTime;

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
	 * @param int $matchDay
	 * @param int $saved
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction($matchDay = 1, $saved = 0) {
		$allClubs = $this->clubRepository->findAll($matchDay);
		$allMatches = $this->matchRepository->findByMatchDay($matchDay);
		$prevMatchDay = $this->getPreviousMatchDay($matchDay);
		$nextMatchDay = $this->getNextMatchDay($matchDay);

		return $this->templating->renderResponse(
			'SuperkickerBundle:Match:edit.html.twig',
			array(
					'allMatches' => $allMatches,
					'allClubs' => $allClubs,
					'saved' => $saved,
					'matchDay' => $matchDay,
					'prevMatchDay' => $prevMatchDay,
					'nextMatchDay' => $nextMatchDay
			)
		);
	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function saveAction(Request $request) {
		$matchData 		= $request->get('match');
		$matchDay 		= $request->get('matchDay');

		foreach($matchData as $matchId => $matchDataItem) {
			$homeClub 	= isset($matchDataItem['home']) ? $matchDataItem['home'] : 0;
			$guestClub 	= isset($matchDataItem['guest']) ? $matchDataItem['guest'] : 0;

			$homeScore = null;
			if(isset($matchDataItem['homeScore']) && trim($matchDataItem['homeScore']) !== '') {
				$homeScore = (int) $matchDataItem['homeScore'];
			}

			$guestScore = null;
			if(isset($matchDataItem['guestScore']) && trim($matchDataItem['guestScore']) !== '') {
				$guestScore = (int) $matchDataItem['guestScore'];
			}

			try {
				$date	= isset($matchDataItem['date']) ? DateTime::parse('d.m.Y H:i', $matchDataItem['date']) : null;
			} catch (\Webforge\Common\DateTime\ParsingException $e) {
				$date 	= null;
			}
			$matchDay	= isset($matchDataItem['day']) ? (int) $matchDataItem['day'] : 1;

			if($matchId == 'new') {
				$match = new Match();
			} else {
				$match = $this->matchRepository->findById($matchId);
			}

			if($date == null || $homeClub == null || $guestClub == null || $matchDay == 0) {
				continue;
			}

			$match->setHomeClub( $this->clubRepository->findById($homeClub) );
			$match->setGuestClub( $this->clubRepository->findById($guestClub) );
			$match->setDate($date);
			$match->setMatchDay($matchDay);
			$match->setHomeScore($homeScore);
			$match->setGuestScore($guestScore);

			$this->matchRepository->save($match);
		}

		$editUrl = $this->router->generate(
				'ts_superkicker_match_edit',
				array('saved' => true, 'matchDay' => $matchDay)
		);
		return new RedirectResponse($editUrl);
	}
}