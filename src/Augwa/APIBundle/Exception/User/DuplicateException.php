<?php

namespace Augwa\APIBundle\Exception\User;

use Augwa\APIBundle\Exception\StatusCode\ClientError\Conflict;

/**
 * Class DuplicateException
 * @package Augwa\APIBundle\Exception\User
 */
class DuplicateException extends UserException implements Conflict {}