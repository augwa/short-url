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

    protected function repository()
    {
        return $this->model()->getRepository('AugwaShortUrlBundle:User');
    }

    /**
     * @param $emailAddress
     *
     * @throws Exception\User\DuplicateException
     */
    protected function emailRegistered($emailAddress)
    {
        /** @var Document\User $user */
        $user = $this->repository()->findOneBy(
            [
                'emailAddress' => $emailAddress
            ]
        );

        if ($user !== null) {
            throw new Exception\User\DuplicateException(sprintf('User with email address "%s" already exists', $emailAddress));
        }
    }

    /**
     * @param string $emailAddress
     * @param string $password
     * @param string|null $ipAddress
     *
     * @return Document\User
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

        $this->emailRegistered($emailAddress);

        $salt = $this->generateSalt($this->parameter('secret'));
        $hashedPassword = $this->hashPassword($password, $salt, $this->parameter('secret'));

        $user = new Document\User;
        $user->setEmailAddress($emailAddress);
        $user->setPassword($hashedPassword);
        $user->setSalt($salt);
        $user->setDateCreated(time());
        $user->setIpAddress(ip2long($ipAddress));

        $this->manager()->persist($user);

        /**
         * try to flush it, and if it blows up from a duplicate key then
         * it means another user was inserted with the same email address
         * between the previous check and this insert
         */
        try {
            $this->manager()->flush();
        }
        catch (\MongoDuplicateKeyException $e) {
            throw new Exception\User\DuplicateException(sprintf('User with email address "%s" already exists', $emailAddress), 0, $e);
        }

        return $user;
    }

    /**
     * @param string $secret
     *
     * @return string
     */
    protected function generateSalt($secret)
    {
        return hash('sha256', (time() . rand() . $secret));
    }

    /**
     * @param string $password
     * @param string $salt
     * @param string $secret
     *
     * @return string
     */
    protected function hashPassword($password, $salt, $secret)
    {
        return hash('sha256', sprintf('%s:%s:%s', $password, $salt, $secret));
    }

}