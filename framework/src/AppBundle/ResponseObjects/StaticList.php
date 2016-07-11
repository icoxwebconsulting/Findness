<?php

namespace AppBundle\ResponseObjects;

use StaticList\StaticList\StaticListInterface;

/**
 * Class StaticList
 *
 * @package AppBundle\ResponseObjects
 */
class StaticList
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * StaticList constructor.
     *
     * @param StaticListInterface $staticList
     */
    public function __construct(StaticListInterface $staticList)
    {
        $this->id = $staticList->getId();
        $this->name = $staticList->getName();
    }
}