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
	 * @param int $tournamentId
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showAction($tournamentId = 1) {

		return $this->templating->renderResponse(
			'SuperkickerBundle:Rules:show.html.twig',
			array(
				'rules' => ''
			)
		);
	}
}