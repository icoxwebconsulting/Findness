<?php

namespace Company\Company;

/**
 * Interface Company
 *
 * @package Company\Company
 */
interface CompanyInterface
{
    /**
     * Get Company id
     *
     * @return string
     */
    public function getId();

    /**
     * Get External id
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Set External id
     *
     * @param string $externalId
     */
    public function setExternalId($externalId);
}