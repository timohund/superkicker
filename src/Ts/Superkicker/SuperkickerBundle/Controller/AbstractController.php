<?php

namespace Ts\Superkicker\SuperkickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Tip;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\User;

abstract class AbstractController extends Controller {

	/**
	 * @return User
	 */
	protected function getCurrentLoginUser() {
		$user = new User();
		$user->setId(1);
		$user->setName('me');
		$user->setClientId(1);


		return $user;
	}
}