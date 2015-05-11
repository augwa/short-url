<?php

namespace Augwa\APIBundle\Controller\Base;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class BaseController
 * @package Augwa\APIBundle\Controller\Base
 */
class BaseController extends ContainerAware
{

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

}