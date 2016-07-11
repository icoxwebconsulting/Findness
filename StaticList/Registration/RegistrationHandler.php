<?php

namespace StaticList\Registration;

use Customer\Customer\CustomerInterface;
use StaticList\StaticList\StaticList;
use StaticList\StaticList\StaticListInterface;

/**
 * Class RegistrationHandler
 *
 * @package StaticList\Registration
 */
class RegistrationHandler
{
    /**
     * Handle registration
     *
     * @param CustomerInterface $customer
     * @param $name
     * @param array $companies
     * @return StaticListInterface
     */
    public function register(CustomerInterface $customer,
                             $name,
                             array $companies)
    {
        $staticList = new StaticList($customer, $name);
        $staticList->setCompanies($companies);
        return $staticList;
    }
}