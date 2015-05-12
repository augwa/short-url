<?php


namespace Augwa\APIBundle\Exception\User;

use Augwa\APIBundle\Exception\StatusCode\ClientError\Unauthorized;
use Augwa\ShortUrlBundle\Exception\User\UserException;

/**
 * Class InvalidCredentialsException
 * @package Augwa\APIBundle\Exception\User
 */
class InvalidCredentialsException extends UserException implements Unauthorized {}