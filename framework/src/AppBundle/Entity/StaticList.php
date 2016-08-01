<?php

namespace AppBundle\Entity;

use Customer\Customer\CustomerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use StaticList\StaticList\StaticList as StaticListBase;
use StaticList\StaticList\StaticListInterface;

/**
 * StaticList ORM Entity
 *
 * @package AppBundle\Entity
 */
class StaticList extends StaticListBase
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
     * @var ArrayCollection
     */
    protected $shareds;

    public function __construct(CustomerInterface $customer, $name, $id)
    {
        parent::__construct($customer, $name, $id);

        $this->shareds = new ArrayCollection();
    }

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
     * @param StaticListInterface $staticList
     * @return StaticList
     */
    static public function fromBusinessEntity(StaticListInterface $staticList)
    {
        $staticListEntity = new StaticList($staticList->getCustomer(), $staticList->getName());
        $staticListEntity->setCompanies($staticList->getCompanies()->toArray());
        return $staticListEntity;
    }
}
