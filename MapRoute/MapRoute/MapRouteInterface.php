<?php

namespace MapRoute\MapRoute;

use Customer\Customer\CustomerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use MapRoute\MapRoutePath\MapRoutePathInterface;

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
     * Set Paths
     *
     * @param ArrayCollection $paths
     */
    public function setPaths(ArrayCollection $paths);

    /**
     * Add Paths
     *
     * @param MapRoutePathInterface $mapRoutePath
     */
    public function addPath(MapRoutePathInterface $mapRoutePath);

    /**
     * Remove Paths
     *
     * @param MapRoutePathInterface $mapRoutePath
     */
    public function removePath(MapRoutePathInterface $mapRoutePath);

    /**
     * Get Paths
     *
     * @return ArrayCollection
     */
    public function getPaths();
}