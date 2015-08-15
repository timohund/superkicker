<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tournament;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;
use Symfony\Component\DependencyInjection\Container;

abstract class AbstractController extends Controller {

	/**
	 * @var \Symfony\Component\Security\Core\SecurityContext
	 */
	protected $securityContext;

	/**
	 * @var \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TournamentRepository
	 */
	protected $tournamentRepository;

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
	 * @var  \Symfony\Bundle\FrameworkBundle\Routing\Router
	 */
	protected $router;

	/**
	 * @var \Symfony\Component\DependencyInjection\Container
	 */
	protected $container;

	/**
	 * @return \Symfony\Component\Security\Core\SecurityContext
	 */
	public function getSecurityContext() {
		return $this->securityContext;
	}

	/**
	 * @param \Symfony\Component\Security\Core\SecurityContext $securityContext
	 */
	public function setSecurityContext($securityContext) {
		$this->securityContext = $securityContext;
	}

	/**
	 * @return User
	 */
	protected function getCurrentLoginUser() {
		return $this->securityContext->getToken()->getUser();
	}

	/**
	 * @return \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TournamentRepository
	 */
	public function getTournamentRepository() {
		return $this->tournamentRepository;
	}

	/**
	 * @param \Ts\Superkicker\SuperkickerBundle\Domain\Repository\TournamentRepository $tournamentRepository
	 */
	public function setTournamentRepository($tournamentRepository) {
		$this->tournamentRepository = $tournamentRepository;

		$allTournaments = $this->getAllTournaments();
		$this->container->get('twig')->addGlobal('tournaments', $allTournaments);
	}

	/**
	 * @return \Doctrine\ORM\PersistentCollection<Tournament>
	 */
	protected function getAllTournaments() {
		return $this->getTournamentRepository()->findAll();
	}

	/**
	 * @param integer $matchDay
	 * @param integer $matchDays
	 * @return mixed
	 */
	protected function getNextMatchDay($matchDay, $matchDays) {
		return min($matchDay + 1, $matchDays);
	}

	/**
	 * @param integer $matchDay
	 * @return mixed
	 */
	protected function getPreviousMatchDay($matchDay) {
		return max(1, $matchDay - 1);
	}

	/**
	 * @param EngineInterface $templating
	 * @param Router $router
	 * @param Container $container
	 */
	public function __construct(EngineInterface $templating, Router $router, Container $container) {
		$this->templating = $templating;
		$this->router = $router;
		$this->container = $container;
	}
}