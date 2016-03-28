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
     * Set the Message Customer sender
     *
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer);

    /**
     * Get the Message Customer sender
     *
     * @return CustomerInterface
     */
    public function getCustomer();
}