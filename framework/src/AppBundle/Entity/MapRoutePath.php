<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;
use MapRoute\MapRoutePath\MapRoutePath as MapRoutePathBase;

/**
 * MapRoute ORM Entity
 *
 * @package AppBundle\Entity
 *
 * @Hateoas\Relation("self", href = @Hateoas\Route("get_map_route_paths", parameters = { "map_route_path" = "expr(object.getId())" }))
 * @Hateoas\Relation("map_route_paths", href = @Hateoas\Route("cget_map_route_paths"))))
 */
class MapRoutePath extends MapRoutePathBase
{
    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
}
