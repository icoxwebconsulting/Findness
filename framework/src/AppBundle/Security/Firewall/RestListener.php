<?php

namespace AppBundle\Security\Firewall;

use AppBundle\Security\Authentication\Token\RestUserToken;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class RestListener
 *
 * @package AppBundle\Security\Firewall
 */
class RestListener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * RestListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    /**
     * Handle authentication attempt
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $username = $request->headers->get('username');
        $password = $request->headers->get('password');

        if (!$username && !$password) {
            return;
        }

        $token = new RestUserToken($password);
        $token->setUser($username);

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            $token = $this->tokenStorage->getToken();
            if ($token instanceof RestUserToken) {
                $this->tokenStorage->setToken(null);
            }
            return;
        }
    }
}