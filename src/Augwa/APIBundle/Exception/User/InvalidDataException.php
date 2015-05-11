<?php

namespace Augwa\APIBundle\Exception\User;

use Augwa\APIBundle\Exception\StatusCode\ClientError\BadRequest;

/**
 * Class InvalidDataException
 * @package Augwa\APIBundle\Exception
 */
class InvalidDataException extends UserException implements BadRequest {

}