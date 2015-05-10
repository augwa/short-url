<?php

namespace Augwa\ShortUrlBundle\Document;

/**
 * Class UrlStat
 * @package Augwa\ShortUrlBundle\Document
 */
class UrlStat
{

    /** @var int */
    protected $urlStatId;

    /** @var Url */
    protected $url;

    /** @var int */
    protected $ipAddress;

    /** @var string */
    protected $country;

    /** @var string */
    protected $userAgent;

    /** @var \DateTime */
    protected $dateCreated;


    /**
     * Get urlStatId
     *
     * @return id $urlStatId
     */
    public function getUrlStatId()
    {
        return $this->urlStatId;
    }

    /**
     * Set ipAddress
     *
     * @param int $ipAddress
     * @return self
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return int $ipAddress
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return self
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string $userAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set dateCreated
     *
     * @param timestamp $dateCreated
     * @return self
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return timestamp $dateCreated
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set url
     *
     * @param Url $url
     * @return self
     */
    public function setUrl(Url $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return Url $url
     */
    public function getUrl()
    {
        return $this->url;
    }
}
