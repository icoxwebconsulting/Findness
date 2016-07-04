<?php

namespace MapRoute\MapRoute;

use Customer\Customer\CustomerInterface;

/**
 * Interface MapRoute
 *
 * @package MapRoute\MapRoute
 */
interface MapRouteInterface
{
    /**
     * Get MapRoute id
     *
     * @return string
     */
    public function getId();

    /**
     * Set MapRoute Name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get MapRoute Name
     *
     * @return string
     */
    public function getName();

    /**
     * Set MapRoute Transport
     *
     * @param string $transport
     */
    public function setTransport($transport);

    /**
     * Get MapRoute Transport
     *
     * @return string
     */
    public function getTransport();

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

    /**
     * Set Path
     *
     * @param array $points
     */
    public function setPath(array $points);

    /**
     * Get Path
     *
     * @return array
     */
    public function getPath();
}