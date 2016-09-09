<?php

namespace AppBundle\Security\Authentication\Provider;

use AppBundle\Security\Authentication\Token\RestUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class RestProvider
 *
 * @package AppBundle\Security\Authentication\Provider
 */
class RestProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * RestProvider constructor.
     *
     * @param UserProviderInterface $userProvider
     */
    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Authenticate the user
     *
     * @param TokenInterface $token
     * @return RestUserToken
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user &&
            $user->getPassword() === $token->getPassword() &&
            $user->isEnabled() &&
            $user->isConfirmed()
        ) {
            $authenticatedToken = new RestUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        $message = 'Usuario y/o contraseña incorrectos.';

        if (!$user->isConfirmed()) {
            $message = 'Usuario no confirmado.';
        } else if (!$user->isEnabled()) {
            $message = 'Usuario deshabilitado.';
        }

        throw new AuthenticationException($message);
    }

    /**
     * Validate supported token
     *
     * @param TokenInterface $token
     * @return bool
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof RestUserToken;
    }
}