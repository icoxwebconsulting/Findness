<?php

namespace Customer\Registration;

use Customer\Customer\CustomerInterface;

/**
 * Class RegistrationHandler
 *
 * @package Customer\Registration
 */
class RegistrationHandler
{
    /**
     * Handle a new customer registration logic
     *
     * @param CustomerInterface $customer
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $salt
     * @param string $password
     * @return CustomerInterface
     */
    public function register(CustomerInterface $customer,
                             $username,
                             $firstName,
                             $lastName,
                             $salt,
                             $password)
    {
        $customer->setUsername($username);
        $customer->setEmail($username);
        $customer->setPassword($password);
        $customer->setSalt($salt);
        $customer->setFirstName($firstName);
        $customer->setLastName($lastName);
        $customer->setRoles(array('ROLE_USER'));
        $customer->setEnabled(true);
        return $customer;
    }
}