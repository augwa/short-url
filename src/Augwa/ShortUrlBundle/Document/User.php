<?php

namespace Augwa\ShortUrlBundle\Document;

/**
 * Class User
 * @package Augwa\ShortUrlBundle\Document
 */
class User
{

    /** @var int */
    protected $userId;

    /** @var string */
    protected $emailAddress;

    /** @var string */
    protected $password;

    /** @var string  */
    protected $salt;

    /** @var int */
    protected $ipAddress;

    /** @var \DateTime */
    protected $dateCreated;


    /**
     * Get userId
     *
     * @return id $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     * @return self
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string $emailAddress
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Get salt
     *
     * @return string $salt
     */
    public function getSalt()
    {
        return $this->salt;
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
}
