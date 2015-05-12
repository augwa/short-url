<?php

namespace Augwa\ShortUrlBundle\Controller;

/**
 * Class UrlFactoryController
 * @package Augwa\ShortUrlBundle\Controller
 */
class UrlFactoryController extends FactoryController
{

    /**
     * @return UrlController
     */
    public function make()
    {
        $url = new UrlController;
        $url->setContainer($this->container);
        return $url;
    }

}