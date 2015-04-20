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

class TipController extends AbstractController {

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\MatchRepository
	 */
	protected $matchRepository;


	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TipRepository
	 */
	protected $tipRepository;

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
	public function editAction($matchDay = 0, $saved = 0) {
		$matches 	= $this->matchRepository->findByMatchDay($matchDay);
		$tips 		= $this->tipRepository->findByUserAndMatchDay($this->getCurrentLoginUser(), $matchDay);


		$matchTips 	= array();
		foreach($matches as $match) {
			/** @var $match Match */
			$matchTips[$match->getId()]['match'] = $match;
		}

		foreach($tips as $tip) {
			/** @var $tip Tip */
			if(array_key_exists($tip->getMatch()->getId(), $matchTips)) {
				$matchTips[$tip->getMatch()->getId()]['tip'] = $tip;
			} else {
				//tip for non existing match?
			}
		}

		//var_dump($saved); die();
		return $this->templating->renderResponse(
			'SuperkickerBundle:Tip:edit.html.twig',
			array(
				'matchTips' => $matchTips,
				'saved' => $saved
			)
		);
	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function saveAction(Request $request) {
		$matches = $request->get('match');
		$numberOfTipps = 0;
		foreach($matches as $matchId => $tipData) {
			$match 		= $this->matchRepository->findById($matchId);
			$savedTip 	= $this->tipRepository->findOneByUserAndMatch($this->getCurrentLoginUser(), $match);
			$tip 		= is_null($savedTip) ? new Tip() : $savedTip;

			$tip->setMatch($match);
			$tip->setHomeScore($tipData['home']);
			$tip->setGuestScore($tipData['guest']);
			$tip->setUser($this->getCurrentLoginUser());

			$this->tipRepository->save($tip);
			$numberOfTipps++;
		}

		$editUrl = $this->router->generate('ts_superkicker_tipp_edit',
				array(
						'matchDay' => $match->getMatchDay(),
						'saved' => true
				)
		);
		return new RedirectResponse($editUrl);
	}


}