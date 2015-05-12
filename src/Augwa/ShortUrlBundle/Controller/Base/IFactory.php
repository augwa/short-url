<?php


namespace Augwa\ShortUrlBundle\Controller\Base;

use Augwa\ShortUrlBundle\Document\Base\BaseDocument;

interface IFactory {

    /**
     * @return BaseDocument
     */
    function make();

}