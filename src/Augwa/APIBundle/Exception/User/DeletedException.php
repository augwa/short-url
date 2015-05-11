<?php

namespace Augwa\APIBundle\Exception\User;

use Augwa\APIBundle\Exception\StatusCode\ClientError\Gone;

/**
 * Class DeletedException
 * @package Augwa\APIBundle\Exception\User
 */
class DeletedException extends UserException implements Gone {}