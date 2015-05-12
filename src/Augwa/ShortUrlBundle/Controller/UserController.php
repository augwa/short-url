<?php

namespace Augwa\ShortUrlBundle\Controller;

use Augwa\ShortUrlBundle\Document;
use Augwa\ShortUrlBundle\Exception;

/**
 * Class UserController
 * @package Augwa\ShortUrlBundle\Controller
 */
class UserController extends Base\BaseController
{

    /**
     * @param Document\User $document
     *
     * @return $this
     */
    protected function setDocument(Document\User $document = null)
    {
        $this->document = $document;
        return $this;

    }

    /**
     * @return Document\User
     */
    public function getDocument()
    {
        if ($this->document === null) {
            $this->document = new Document\User;
        }
        return parent::getDocument();
    }

    /**
     * @return string $userId
     */
    public function getUserId()
    {
        return $this->getDocument()->getUserId();
    }

    /**
     * @param string $emailAddress
     * @return $this
     */
    public function setEmailAddress($emailAddress = null)
    {
        $this->getDocument()->setEmailAddress($emailAddress);
        return $this;
    }

    /**
     * @return string $emailAddress
     */
    public function getEmailAddress()
    {
        return $this->getDocument()->getEmailAddress();
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password = null)
    {
        $this->getDocument()->setPassword($this->hashPassword($password));
        return $this;
    }

    /**
     * @return string $password
     */
    public function getPassword()
    {
        return $this->getDocument()->getPassword();
    }

    /**
     * @param string $salt
     * @return $this
     */
    public function setSalt($salt = null)
    {
        $this->getDocument()->setSalt($salt);
        return $this;
    }

    /**
     * @return string $salt
     */
    protected function getSalt()
    {
        if ($this->getDocument()->getSalt() == null) {
            $this->setSalt($this->generateSalt());
        }
        return $this->getDocument()->getSalt();
    }

    /**
     * @param int $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress = null)
    {
        $this->getDocument()->setIpAddress($ipAddress);
        return $this;
    }

    /**
     * @return int $ipAddress
     */
    public function getIpAddress()
    {
        return $this->getDocument()->getIpAddress();
    }

    /**
     * @param int|\MongoTimestamp $dateCreated
     * @return $this
     */
    protected function setDateCreated($dateCreated = null)
    {
        $this->getDocument()->setDateCreated($dateCreated);
        return $this;
    }

    /**
     * @return int $dateCreated
     */
    public function getDateCreated()
    {
        if ($this->getDocument()->getDateCreated() instanceof \MongoTimestamp) {
            return (int)$this->getDocument()->getDateCreated()->__toString();
        }
        return $this->getDocument()->getDateCreated();
    }

    protected function repository()
    {
        return $this->model()->getRepository('AugwaShortUrlBundle:User');
    }

    public function delete()
    {
        $this->manager()->remove($this->document);
        $this->manager()->flush();
    }

    /**
     * @throws \Augwa\ShortUrlBundle\Exception\User\DuplicateException
     */
    public function save()
    {
        if ($this->getDateCreated() === null) {
            $this->setDateCreated(time());
        }

        $this->manager()->persist($this->document);
        try {
            $this->manager()->flush();
        }
        catch (\MongoDuplicateKeyException $e) {
            throw new Exception\User\DuplicateException(sprintf('User with email address "%s" already exists', $this->getEmailAddress()), 0, $e);
        }
    }

    /**
     * @param string $emailAddress
     * @param string $password
     * @param string|null $ipAddress
     *
     * @return \Augwa\ShortUrlBundle\Controller\UserController
     *
     * @throws \Augwa\ShortUrlBundle\Exception\User\DuplicateException
     * @throws \Augwa\ShortUrlBundle\Exception\User\InvalidDataException
     */
    public function createAccount($emailAddress, $password, $ipAddress = null)
    {
        $emailAddress = trim($emailAddress);
        $password = trim($password);

        if (false === filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            throw new Exception\User\InvalidDataException('emailAddress is not a valid email address');
        }

        if (strlen($password) < 4) {
            throw new Exception\User\InvalidDataException('password must be at least 4 characters long');
        }

        if (true === $this->isEmailAddressRegistered($emailAddress)) {
            throw new Exception\User\DuplicateException(sprintf('user with emailAddress "%s" already exists', $emailAddress));
        }

        /** @var $user UserController */
        $user = $this->get('Augwa.ShortURL.User.Factory')->make();

        $user->setEmailAddress($emailAddress);
        $user->setPassword($password);
        $user->setIpAddress(ip2long($ipAddress));

        $user->save();

        return $user;
    }

    /**
     * @param string $emailAddress
     *
     * @return $this
     */
    public function loadByEmailAddress($emailAddress)
    {
        return $this->setDocument($this->findByEmailAddress($emailAddress));
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function loadById($id)
    {
        return $this->setDocument($this->findById($id));
    }

    /**
     * @param Document\User $user
     *
     * @return $this
     */
    public function loadByDocument(Document\User $user)
    {
        return $this->setDocument($user);
    }

    /**
     * @param array $criteria
     * @param array $order
     * @param null $limit
     * @param null $offset
     *
     * @return UserController[]
     */
    public function search(array $criteria = [], array $order = [], $limit = null, $offset = null)
    {
        /** @var $users Document\User[] */
        $users = $this->findBy($criteria, $order, $limit, $offset);

        $results = [];

        foreach($users as $userDoc) {
            /** @var $user UserController */
            $user = $this->get('Augwa.ShortURL.User.Factory')->make();
            $user->loadByDocument($userDoc);
            $user->setContainer($this->container());
            $results[] = $user;
        }

        return $results;
    }

    /**
     * @param string $emailAddress
     * @param string $password
     *
     * @return UserController
     *
     * @throws Exception\Authentication\PasswordMismatchException
     * @throws Exception\Authentication\EmailAddressNotFoundException
     */
    public function authenticateByEmailAddressAndPassword($emailAddress, $password)
    {
        /** @var $user UserController */
        $user = $this->get('Augwa.ShortURL.User.Factory')->make();

        try {
            $user->loadByEmailAddress($emailAddress);
        } catch (Exception\User\NotFoundException $e) {
            throw new Exception\Authentication\EmailAddressNotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        if (false === $user->isPasswordCorrect($password)) {
            throw new Exception\Authentication\PasswordMismatchException(sprintf('password does not match user with email address "%s"', $emailAddress));
        }

        return $user;
    }

    /**
     * @param string $emailAddress
     *
     * @return Document\User
     * @throws Exception\User\NotFoundException
     */
    protected function findByEmailAddress($emailAddress)
    {
        /** @var Document\User $user */
        $user = $this->findOneBy(
            [
                'emailAddress' => $emailAddress
            ]
        );

        if ($user === null) {
            throw new Exception\User\NotFoundException(sprintf('user with emailAddress "%s" was not found', $emailAddress));
        }

        return $user;
    }

    /**
     * @param string $id
     *
     * @return Document\User
     * @throws Exception\User\NotFoundException
     */
    protected function findById($id)
    {
        /** @var Document\User $user */
        $user = $this->findOneBy(
            [
                'userId' => $id
            ]
        );

        if ($user === null) {
            throw new Exception\User\NotFoundException(sprintf('user with id "%s" was not found', $id));
        }

        return $user;
    }

    /**
     * @param string $emailAddress
     *
     * @return bool
     * @throws Exception\User\DuplicateException
     */
    protected function isEmailAddressRegistered($emailAddress)
    {
        try {
            $this->findByEmailAddress($emailAddress);
            return true;
        }
        catch (Exception\User\NotFoundException $e) {
            return false;
        }
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function isPasswordCorrect($password)
    {
        return $this->hashPassword($password) === $this->getPassword();
    }

    /**
     * @return string
     */
    protected function generateSalt()
    {
        return hash('sha256', (time() . rand() . $this->parameter('secret')));
    }

    /**
     * @param string $password
     *
     * @return string
     */
    protected function hashPassword($password)
    {
        return hash('sha256', sprintf('%s:%s:%s', $password, $this->getSalt(), $this->parameter('secret')));
    }

}