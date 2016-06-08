<?php

namespace Customer\Customer;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * Class Customer
 *
 * @package Customer\Customer
 */
class Customer extends BaseUser implements CustomerInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var bool
     */
    protected $confirmed;

    /**
     * Customer constructor.
     * @param string|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct();

        $this->id = $id;
        if (!$this->id) {
            $this->id = uniqid();
        }
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @inheritdoc
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return sprintf("%s %s",
            $this->getFirstName(),
            $this->getLastName());
    }

    /**
     * Set salt
     *
     * @param $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
}