<?php


namespace Augwa\ShortUrlBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Augwa\ShortUrlBundle\Controller as ShortUrl;
use Augwa\APIBundle\Exception as APIException;
use Augwa\ShortUrlBundle\Exception as ShortUrlException;
use Symfony\Component\HttpFoundation\Response;

class ViewController
{

    /**
     * @param Request $request
     *
     * @throws \Augwa\APIBundle\Exception\Api\NotImplementedException
     */
    public function homeAction(Request $request)
    {
        throw new APIException\Api\NotImplementedException('The requested URL is not currently implemented');
    }

    /**
     * @param Request $request
     *
     * @throws \Augwa\APIBundle\Exception\Api\NotImplementedException
     */
    public function redirectAction(Request $request)
    {
        throw new APIException\Api\NotImplementedException('The requested URL is not currently implemented');
    }

}