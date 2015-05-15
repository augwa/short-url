<?php

namespace Augwa\APIBundle\Exception\User;

use Augwa\APIBundle\Exception\StatusCode\ClientError\Unauthorized;

/**
 * Class AuthenticationException
 * @package Augwa\APIBundle\Exception\User
 */
class AuthenticationException extends UserException implements Unauthorized {}