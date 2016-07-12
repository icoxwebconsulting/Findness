<?php

namespace StaticList\StaticList;

/**
 * Interface StaticListDependant
 *
 * @package StaticList\StaticList
 */
interface StaticListDependantInterface
{
    /**
     * @param StaticListInterface $staticList
     */
    public function setStaticList(StaticListInterface $staticList);

    /**
     * @return StaticListInterface
     */
    public function getStaticList();
}