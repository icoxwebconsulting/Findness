<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;

/**
 * Class Client
 *
 * @package AppBundle\Entity
 */
class Client extends BaseClient
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $accessTokens;

    /**
     * @var array
     */
    protected $authCodes;

    /**
     * @var array
     */
    protected $refreshTokens;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * Client constructor.
     * @param string|null $id
     * @param string|null $name
     */
    public function __construct($id = null, $name = null)
    {
        parent::__construct();

        if ($id) {
            $this->id = $id;
        } else {
            $this->id = uniqid();
        }
        if ($name) {
            $this->name = $name;
        }
        $this->accessTokens = new ArrayCollection();
        $this->authCodes = new ArrayCollection();
        $this->refreshTokens = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param AccessToken $token
     */
    public function setAccessToken(AccessToken $token)
    {
        $this->accessTokens[] = $token;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }

    /**
     * @param AuthCode $authCode
     */
    public function setAuthCode(AuthCode $authCode)
    {
        $this->authCodes[] = $authCode;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getAuthCodes()
    {
        return $this->authCodes;
    }

    /**
     * @param RefreshToken $token
     */
    public function setRefreshToken(RefreshToken $token)
    {
        $this->refreshTokens[] = $token;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getRefreshTokens()
    {
        return $this->refreshTokens;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
}