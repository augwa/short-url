<?php

namespace Augwa\APIBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Augwa\ShortUrlBundle\Controller as ShortUrl;
use Augwa\APIBundle\Exception as APIException;
use Augwa\ShortUrlBundle\Exception as ShortUrlException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package Augwa\APIBundle\Controller
 */
class UserController extends Base\BaseController
{

    /**
     * @param Request $request
     *
     * @throws APIException\Api\NotImplementedException
     */
    public function createAction(Request $request)
    {
        throw new APIException\Api\NotImplementedException('The requested URL is not currently implemented');
    }

    /**
     * @param Request $request
     *
     * @throws APIException\Api\NotImplementedException
     */
    public function readAction(Request $request)
    {
        throw new APIException\Api\NotImplementedException('The requested URL is not currently implemented');
    }

    /**
     * @param Request $request
     *
     * @throws APIException\Api\NotImplementedException
     */
    public function updateAction(Request $request)
    {
        throw new APIException\Api\NotImplementedException('The requested URL is not currently implemented');
    }

    /**
     * @param Request $request
     *
     * @throws APIException\Api\NotImplementedException
     */
    public function deleteAction(Request $request)
    {
        throw new APIException\Api\NotImplementedException('The requested URL is not currently implemented');
    }

}