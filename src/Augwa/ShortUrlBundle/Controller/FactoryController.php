<?php

namespace Augwa\ShortUrlBundle\Controller;

use Augwa\ShortUrlBundle\Controller\Base\IFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class FactoryController implements IFactory
{

    /** @var ContainerInterface */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

}