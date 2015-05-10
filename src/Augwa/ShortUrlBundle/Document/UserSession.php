<?php

namespace Augwa\ShortUrlBundle\Document;

/**
 * Class UserSession
 * @package Augwa\ShortUrlBundle\Document
 */
class UserSession
{

    /** @var string */
    protected $userSessionId;

    /** @var User */
    protected $user;

    /** @var int */
    protected $ipAddress;

    /** @var \DateTime */
    protected $dateCreated;

    /**
     * Get userSessionId
     *
     * @return id $userSessionId
     */
    public function getUserSessionId()
    {
        return $this->userSessionId;
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
     * Set user
     *
     * @param User $user
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User $user
     */
    public function getUser()
    {
        return $this->user;
    }
}
