<?php

namespace AppBundle\Services;

use FOS\OAuthServerBundle\Model\ClientInterface;
use FOS\OAuthServerBundle\Storage\OAuthStorage as OAuthStorageBase;
use OAuth2\Model\IOAuth2Client;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class FindnessOAuthStorage
 *
 * @package AppBundle\Services
 */
class FindnessOAuthStorage extends OAuthStorageBase
{
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