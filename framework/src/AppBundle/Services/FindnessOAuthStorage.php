<?php

namespace AppBundle\Services;

use FOS\OAuthServerBundle\Model\AccessTokenManagerInterface;
use FOS\OAuthServerBundle\Model\AuthCodeManagerInterface;
use FOS\OAuthServerBundle\Model\ClientInterface;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\OAuthServerBundle\Model\RefreshTokenManagerInterface;
use FOS\OAuthServerBundle\Storage\OAuthStorage as OAuthStorageBase;
use FOS\UserBundle\Model\UserManager;
use FOS\UserBundle\Security\UserProvider;
use OAuth2\Model\IOAuth2Client;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class FindnessOAuthStorage
 *
 * @package AppBundle\Services
 */
class FindnessOAuthStorage extends OAuthStorageBase
{
    /**
     * FindnessOAuthStorage constructor.
     *
     * @param ClientManagerInterface $clientManager
     * @param AccessTokenManagerInterface $accessTokenManager
     * @param RefreshTokenManagerInterface $refreshTokenManager
     * @param AuthCodeManagerInterface $authCodeManager
     * @param UserManager|null $userManager
     * @param EncoderFactoryInterface|null $encoderFactory
     */
    public function __construct(
        ClientManagerInterface $clientManager,
        AccessTokenManagerInterface $accessTokenManager,
        RefreshTokenManagerInterface $refreshTokenManager,
        AuthCodeManagerInterface $authCodeManager,
        UserManager $userManager = null,
        EncoderFactoryInterface $encoderFactory = null
    ) {
        $userProvider = new UserProvider($userManager);

        parent::__construct(
            $clientManager,
            $accessTokenManager,
            $refreshTokenManager,
            $authCodeManager,
            $userProvider,
            $encoderFactory
        );
    }

    /**
     * Check if credentials are valid
     *
     * @param IOAuth2Client $client
     * @param string $username
     * @param string $password
     * @return array|bool
     */
    public function checkUserCredentials(IOAuth2Client $client, $username, $password)
    {
        if (!$client instanceof ClientInterface) {
            throw new \InvalidArgumentException('Client has to implement the ClientInterface');
        }

        try {
            $user = $this->userProvider->loadUserByUsername($username);
        } catch (AuthenticationException $e) {
            return false;
        }

        if ($user->getPassword() !== $password) {
            return false;
        }

        return array(
            'data' => $user,
        );
    }
}