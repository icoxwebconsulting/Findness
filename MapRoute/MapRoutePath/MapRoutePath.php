<?php

namespace MapRoute\MapRoutePath;

use Company\Company\CompanyInterface;
use MapRoute\MapRoute\MapRouteInterface;

/**
 * Class MapRoutePath
 *
 * @package MapRoutePath\MapRoutePath
 */
class MapRoutePath implements MapRoutePathInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var CompanyInterface
     */
    protected $startPoint;

    /**
     * @var CompanyInterface
     */
    protected $endPoint;

    /**
     * @var MapRouteInterface
     */
    protected $mapRoute;

    /**
     * MapRoutePath constructor.
     * @param string|null $id
     */
    public function __construct($id = null)
    {
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
    public function setStartPoint(CompanyInterface $company)
    {
        $this->startPoint = $company;
    }

    /**
     * @inheritdoc
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * @inheritdoc
     */
    public function setEndPoint(CompanyInterface $company)
    {
        $this->endPoint = $company;
    }

    /**
     * @inheritdoc
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * @inheritdoc
     */
    public function setMapRoute(MapRouteInterface $mapRoute)
    {
        $this->mapRoute = $mapRoute;
    }

    /**
     * @inheritdoc
     */
    public function getMapRoute()
    {
        return $this->mapRoute;
    }
}