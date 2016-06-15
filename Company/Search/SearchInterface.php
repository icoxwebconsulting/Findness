<?php

namespace Company\Search;

use Customer\Customer\CustomerDependantInterface;

/**
 * Interface SearchInterface
 *
 * @package Company\Search
 */
interface SearchInterface extends CustomerDependantInterface
{
    /**
     * @return mixed
     */
    public function getId();
    
    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param array $filters
     * @return mixed
     */
    public function setFilters(array $filters);

    /**
     * @return mixed
     */
    public function getFilters();
}