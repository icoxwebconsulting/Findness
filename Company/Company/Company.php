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
}