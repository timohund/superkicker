<?php
namespace Ts\Superkicker\SuperkickerBundle\System\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OAuthUserProvider
 */
class OAuthUserProvider extends BaseClass {
	/**
	 * {@inheritdoc}
	 */
	public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
		$userId = $response->getUsername();
		$user = $this->userManager->findUserBy(array($this->getProperty($response) => $userId));
		$email = $response->getEmail();
		$username = $response->getNickname() ?: $response->getRealName();


		if (null === $user) {
			// when we did not get an email or username from the api, we return it without auto registration
			if (trim($email) === '' || trim($username) === '') {
				throw new AccountNotLinkedException('No email or username provided');
			}

			$user = $this->userManager->findUserByUsernameAndEmail($username, $email);

			if (null === $user || !$user instanceof UserInterface) {
				$oauthService = $response->getResourceOwner()->getName();

				$user = $this->userManager->createUser();
				$username = str_replace(' ', '', $username).'@'.$oauthService;
				$user->setUsername($username);
				$user->setEmail($email);
				$user->setPassword(sha1(rand(1,1000000000)));
				$user->setEnabled(true);
				$user->setOAuthService($oauthService);
				$user->setOAuthId($userId);
				$user->setOAuthAccessToken($response->getAccessToken());
				$this->userManager->updateUser($user);
			} else {
				throw new AuthenticationException('Username or email has been already used.');
			}
		} else {
			$checker = new UserChecker();
			$checker->checkPreAuth($user);
		}

		return $user;
	}
}