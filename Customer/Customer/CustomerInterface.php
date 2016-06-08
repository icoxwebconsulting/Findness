<?php

namespace Customer\Customer;

/**
 * Interface Customer
 *
 * @package Customer\Customer
 */
interface CustomerInterface
{
    /**
     * Get Customer id
     *
     * @return string
     */
    public function getId();

    /**
     * Set Customer First Name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * Get Customer First Name
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Set Customer Last Name
     *
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * Get Customer Last Name
     *
     * @return string
     */
    public function getLastName();

    /**
     * Set Customer confirmed
     *
     * @param bool $confirmed
     */
    public function setConfirmed($confirmed);

    /**
     * Get Customer confirmed
     *
     * @return string
     */
    public function isConfirmed();

    /**
     * Get Customer Full Name
     *
     * @return string
     */
    public function getFullName();

    /**
     * Set customer salt
     *
     * @param string $salt
     */
    public function setSalt($salt);
}