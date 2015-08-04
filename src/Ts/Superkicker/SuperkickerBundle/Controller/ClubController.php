<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Club;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;

class ClubController extends AbstractController {


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
	 * @param int $saved
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction($saved = 0) {
		$allClubs = $this->clubRepository->findAll();
		return $this->templating->renderResponse(
				'SuperkickerBundle:Club:edit.html.twig',
				array(
						'allClubs' => $allClubs,
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

		$clubsData = $request->get('clubs');

		foreach($clubsData as $id => $clubData) {
			if($id == 'new' && trim($clubData['name']) !== ''){
				$club = new Club();
			} else {
				$club = $this->clubRepository->findById($id);
			}

			if($club === null) {
				continue;
			}

			$club->setName($clubData['name']);

			$file = $request->files->get('logo_'.$id);

			if($file !== null) {
				$club->setLogoFile($file);
			}

			$this->clubRepository->save($club);
		}
		$editUrl = $this->router->generate('ts_superkicker_club_edit',array('saved' => 1));
		return new RedirectResponse($editUrl);
	}
}