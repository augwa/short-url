<?php

namespace Augwa\APIBundle\Exception\User;

use Augwa\APIBundle\Exception\StatusCode\ClientError\NotFound;

/**
 * Class NotFoundException
 * @package Augwa\APIBundle\Exception\User
 */
class NotFoundException extends UserException implements NotFound {}