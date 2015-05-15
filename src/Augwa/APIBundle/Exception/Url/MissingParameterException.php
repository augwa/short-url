<?php


namespace Augwa\APIBundle\Exception\Url;

use Augwa\APIBundle\Exception\StatusCode\ClientError\BadRequest;

/**
 * Class MissingParameterException
 * @package Augwa\APIBundle\Exception\Url
 */
class MissingParameterException extends UrlException implements BadRequest {}