<?php

namespace AppBundle\Entity;

use StaticList\StaticList\SharedStaticList as SharedStaticListBase;
use StaticList\StaticList\SharedStaticListInterface;

/**
 * SharedStaticList ORM Entity
 *
 * @package AppBundle\Entity
 */
class SharedStaticList extends SharedStaticListBase
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

    /**
     * Convert from business entity
     *
     * @param SharedStaticListInterface $staticList
     * @return SharedStaticList
     */
    static public function fromBusinessEntity(SharedStaticListInterface $staticList)
    {
        return new SharedStaticList($staticList->getCustomer(), $staticList->getStaticList());
    }
}
