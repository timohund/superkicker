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
use Webforge\Common\DateTime\DateTime;

class RulesController extends AbstractController {


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
	 * @param int $tournamentId
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showAction($tournamentId = 1) {

		return $this->templating->renderResponse(
			'SuperkickerBundle:Rules:show.html.twig',
			array(
				'rules' => '',
				'tournaments' => $this->getAllTournaments(),
			)
		);
	}
}