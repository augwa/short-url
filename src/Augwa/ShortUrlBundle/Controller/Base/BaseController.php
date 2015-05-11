<?php

namespace Augwa\ShortUrlBundle\Controller\Base;

use Symfony\Component\DependencyInjection;

/**
 * Class BaseController
 * @package Augwa\ShortUrlBundle\Controller\Base
 */
abstract class BaseController extends DependencyInjection\ContainerAware
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    abstract protected function repository();

    /**
     * @return \Doctrine\Bundle\MongoDBBundle\ManagerRegistry
     */
    protected function model()
    {
        return $this->get('doctrine_mongodb');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function manager()
    {
        return $this->model()->getManager();
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * @param $id
     *
     * @return object
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @param $id
     *
     * @return string
     */
    protected function parameter($id)
    {
        return $this->container->getParameter($id);
    }

}