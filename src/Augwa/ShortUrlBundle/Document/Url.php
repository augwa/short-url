<?php

namespace Augwa\ShortUrlBundle\Document;

/**
 * Class Url
 * @package Augwa\ShortUrlBundle\Document
 */
class Url extends Base\BaseDocument
{

    /** @var \MongoId */
    protected $urlId;

    /** @var string */
    protected $url;

    /** @var string */
    protected $title;

    /** @var string */
    protected $description;

    /** @var User */
    protected $user;

    /** @var int */
    protected $ipAddress;

    /** @var \MongoTimestamp */
    protected $dateCreated;

    /**
     * @return \MongoInt64
     */
    public function getUrlId()
    {
        return $this->urlId;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}
