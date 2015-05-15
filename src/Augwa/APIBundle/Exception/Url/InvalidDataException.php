<?php

namespace Augwa\APIBundle\Exception\Url;

use Augwa\APIBundle\Exception\StatusCode\ClientError\BadRequest;

/**
 * Class InvalidDataException
 * @package Augwa\APIBundle\Exception
 */
class InvalidDataException extends UrlException implements BadRequest {}