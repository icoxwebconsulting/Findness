<?php

namespace Company\Company;

/**
 * Class Company
 *
 * @package Company\Company
 */
class Company implements CompanyInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var string
     */
    protected $socialReason;

    /**
     * @var string
     */
    protected $socialObject;

    /**
     * @var string
     */
    protected $latitude;

    /**
     * @var string
     */
    protected $longitude;

    /**
     * @var string
     */
    protected $cif;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $phoneNumber;

    /**
     * @var string
     */
    protected $employees;

    /**
     * @var string
     */
    protected $billing;

    /**
     * @var string
     */
    protected $sector;

    /**
     * @var string
     */
    protected $freelance;

    /**
     * @var string
     */
    protected $cnae;



    /**
     * Balance constructor.
     *
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
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @inheritdoc
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @inheritdoc
     */
    public function getSocialReason()
    {
        return $this->socialReason;
    }

    /**
     * @inheritdoc
     */
    public function setSocialReason($socialReason)
    {
        $this->socialReason = $socialReason;
    }

    /**
     * @inheritdoc
     */
    public function getSocialObject()
    {
        return $this->socialObject;
    }

    /**
     * @inheritdoc
     */
    public function setSocialObject($socialObject)
    {
        $this->socialObject = $socialObject;
    }

    /**
     * @inheritdoc
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @inheritdoc
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @inheritdoc
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @inheritdoc
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @inheritdoc
     */
    public function getCIF()
    {
        return $this->cif;
    }

    /**
     * @inheritdoc
     */
    public function setCIF($cif)
    {
        $this->cif = $cif;
    }

    /**
     * @inheritdoc
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @inheritdoc
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @inheritdoc
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @inheritdoc
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @inheritdoc
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * @inheritdoc
     */
    public function setEmployees($employees)
    {
        $this->employees = $employees;
    }

    /**
     * @inheritdoc
     */
    public function getBilling()
    {
        return $this->billing;
    }

    /**
     * @inheritdoc
     */
    public function setBilling($billing)
    {
        $this->billing = $billing;
    }

    /**
     * @inheritdoc
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @inheritdoc
     */
    public function setSector($sector)
    {
        $this->sector = $sector;
    }

    /**
     * @inheritdoc
     */
    public function getFreelance()
    {
        return $this->freelance;
    }

    /**
     * @inheritdoc
     */
    public function setFreelance($freelance)
    {
        $this->freelance = $freelance;
    }

    /**
     * @inheritdoc
     */
    public function getCnae()
    {
        return $this->cnae;
    }

    /**
     * @inheritdoc
     */
    public function setCnae($cnae)
    {
        $this->cnae = $cnae;
    }
}