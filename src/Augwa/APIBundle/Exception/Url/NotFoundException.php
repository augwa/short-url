<?php

namespace Augwa\APIBundle\Exception\Url;

use Augwa\APIBundle\Exception\StatusCode\ClientError\NotFound;

/**
 * Class NotFoundException
 * @package Augwa\APIBundle\Exception\Url
 */
class NotFoundException extends UrlException implements NotFound {}