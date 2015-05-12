<?php

namespace Augwa\ShortUrlBundle\Controller;

use Augwa\ShortUrlBundle\Document\Base\BaseDocument;

/**
 * Class UrlStatFactoryController
 * @package Augwa\ShortUrlBundle\Controller
 */
class UrlStatFactoryController extends FactoryController {

    /**
     * @return BaseDocument
     */
    function make()
    {
        $url = new UrlStatController;
        $url->setContainer($this->container);
        return $url;
    }
}