<?php

namespace Company\Company;

use Customer\Customer\CustomerDependantInterface;

/**
 * Interface StyledCompanyInterface
 *
 * @package Company\Company
 */
interface StyledCompanyInterface extends CustomerDependantInterface, CompanyDependantInterface
{
    /**
     * @param mixed $style
     */
    public function setStyle($style);

    /**
     * @return mixed
     */
    public function getStyle();
}