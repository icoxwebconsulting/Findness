<?php

namespace Customer\Customer;

/**
 * Interface CustomerDependantInterface
 *
 * @package Customer\Customer
 */
interface CustomerDependantInterface
{
    /**
     * Set Customer
     *
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer);

    /**
     * Get Customer
     *
     * @return CustomerInterface
     */
    public function getCustomer();
}