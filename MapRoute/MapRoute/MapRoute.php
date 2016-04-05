<?php

namespace MapRoute\MapRoute;

use Customer\Customer\CustomerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use MapRoute\MapRoutePath\MapRoutePathInterface;

/**
 * Class MapRoute
 *
 * @package MapRoute\MapRoute
 */
class MapRoute implements MapRouteInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $transport;

    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var ArrayCollection
     */
    protected $paths;

    /**
     * MapRoute constructor.
     * @param string|null $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
        if (!$this->id) {
            $this->id = uniqid();
        }
        $this->paths = new ArrayCollection();
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
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    /**
     * @inheritdoc
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @inheritdoc
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @inheritdoc
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @inheritdoc
     */
    public function setPaths(ArrayCollection $paths)
    {
        $this->paths = $paths;
    }

    /**
     * @inheritdoc
     */
    public function addPath(MapRoutePathInterface $mapRoutePath)
    {
        $this->paths->add($mapRoutePath);
    }

    /**
     * @inheritdoc
     */
    public function removePath(MapRoutePathInterface $mapRoutePath)
    {
        $this->paths->removeElement($mapRoutePath);
    }

    /**
     * @inheritdoc
     */
    public function getPaths()
    {
        return $this->paths;
    }
}