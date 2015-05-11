<?php

namespace Augwa\ShortUrlBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserFactoryController
 * @package Augwa\ShortUrlBundle\Controller
 */
class UserFactoryController {

    public static function createUser(ContainerInterface $container)
    {
        $user = new UserController;
        $user->setContainer($container);
        return $user;
    }

}