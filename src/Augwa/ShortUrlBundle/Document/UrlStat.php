<?php

namespace Augwa\ShortUrlBundle\Document;

/**
 * Class UrlStat
 * @package Augwa\ShortUrlBundle\Document
 */
class UrlStat extends Base\BaseDocument
{

    /** @var \MongoId */
    protected $urlStatId;

    /** @var Url */
    protected $url;

    /** @var int */
    protected $ipAddress;

    /** @var string */
    protected $country;

    /** @var string */
    protected $userAgent;

    /** @var \MongoTimestamp */
    protected $dateCreated;


    /**
     * @return \MongoId
     */
    public function getUrlStatId()
    {
        return $this->urlStatId;
    }

    /**
     * @param int $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return int
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param \MongoTimestamp $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return \MongoTimestamp
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param Url $url
     * @return $this
     */
    public function setUrl(Url $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }
}
