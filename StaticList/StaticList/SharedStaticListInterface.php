<?php

namespace StaticList\StaticList;

use Customer\Customer\CustomerDependantInterface;

/**
 * Interface SharedStaticList
 *
 * @package StaticList\StaticList
 */
interface SharedStaticListInterface extends CustomerDependantInterface, StaticListDependantInterface
{
}