<?php

namespace Augwa\APIBundle\Exception\User;

use Augwa\APIBundle\Exception\StatusCode\ClientError\BadRequest;

/**
 * Class MissingParameterException
 * @package Augwa\APIBundle\Exception
 */
class MissingParameterException extends UserException implements BadRequest {}