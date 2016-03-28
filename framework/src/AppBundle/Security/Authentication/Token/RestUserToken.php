<?php

namespace AppBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Class RestUserToken
 *
 * @package AppBundle\Security\Authentication\Token
 */
class RestUserToken extends AbstractToken
{
    /**
     * @var array|\string[]|\Symfony\Component\Security\Core\Role\RoleInterface[]
     */
    private $password;

    /**
     * RestUserToken constructor.
     *
     * @param array|\string[]|\Symfony\Component\Security\Core\Role\RoleInterface[] $password
     */
    public function __construct($password)
    {
        $this->password = $password;
        parent::__construct();
    }

    /**
     * Get the password
     *
     * @return array|\string[]|\Symfony\Component\Security\Core\Role\RoleInterface[]
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get whole credentials
     *
     * @return string
     */
    public function getCredentials()
    {
        return '';
    }
}