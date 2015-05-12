<?php

namespace Augwa\ShortUrlBundle\Controller;

/**
 * Class UserFactoryController
 * @package Augwa\ShortUrlBundle\Controller
 */
class UserFactoryController extends FactoryController
{

    /**
     * @return UserController
     */
    public function make()
    {
        $user = new UserController;
        $user->setContainer($this->container);
        return $user;
    }

}