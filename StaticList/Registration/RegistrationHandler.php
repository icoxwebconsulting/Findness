<?php

namespace StaticList\Registration;

use Customer\Customer\CustomerInterface;
use StaticList\StaticList\SharedStaticList;
use StaticList\StaticList\SharedStaticListInterface;
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
     * @param string $name
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

    /**
     * Handle share
     *
     * @param CustomerInterface $customer
     * @param StaticListInterface $staticList
     * @return SharedStaticListInterface
     */
    public function share(CustomerInterface $customer,
                          StaticListInterface $staticList)
    {
        return new SharedStaticList($customer, $staticList);
    }
}