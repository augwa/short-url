<?php

namespace Augwa\ShortUrlBundle\Document;

/**
 * Class User
 * @package Augwa\ShortUrlBundle\Document
 */
class User extends Base\BaseDocument
{

    /** @var \MongoId */
    protected $userId;

    /** @var string */
    protected $emailAddress;

    /** @var string */
    protected $password;

    /** @var string  */
    protected $salt;

    /** @var int */
    protected $ipAddress;

    /** @var \MongoTimestamp */
    protected $dateCreated;


    /**
     * @param \MongoId $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return \MongoId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $emailAddress
     * @return $this
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $salt
     * @return $this
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
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
}
