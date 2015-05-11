<?php

namespace Augwa\APIBundle\Exception\Api;

use Augwa\APIBundle\Exception\Exception;
use Augwa\APIBundle\Exception\StatusCode\ServerError\NotImplemented;

/**
 * Class NotImplementedException
 * @package Augwa\APIBundle\Exception\Api
 */
class NotImplementedException extends Exception implements NotImplemented {}