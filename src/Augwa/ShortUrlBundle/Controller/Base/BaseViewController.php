<?php

namespace Augwa\ShortUrlBundle\Controller\Base;

use Symfony\Component\DependencyInjection;

/**
 * Class BaseViewController
 * @package Augwa\ShortUrlBundle\Controller\Base
 */
abstract class BaseViewController extends DependencyInjection\ContainerAware
{

   /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->container()->has($id);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function get($id)
    {
        return $this->container()->get($id);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function parameter($id)
    {
        return $this->container()->getParameter($id);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function container()
    {
        return $this->container;
    }

}