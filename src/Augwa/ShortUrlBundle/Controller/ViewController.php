<?php

namespace Augwa\ShortUrlBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Augwa\APIBundle\Exception as APIException;
use Augwa\ShortUrlBundle\Exception as ShortUrlException;
use Augwa\ShortUrlBundle\Controller as ShortUrl;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ViewController
 * @package Augwa\APIBundle\Controller
 */
class ViewController extends Base\BaseViewController
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
     * @return RedirectResponse
     * @throws \Augwa\APIBundle\Exception\User\NotFoundException
     */
    public function redirectAction(Request $request)
    {
        /** @var $url ShortUrl\UrlController */
        $url = $this->get('Augwa.ShortURL.Url.Factory')->make();

        try {
            $url->loadById($url->getWebId($request->get('url_code')));
        }
        catch (ShortUrlException\User\NotFoundException $e) {
            throw new APIException\User\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        /** @var $urlStat UrlStatController */
        $urlStat = $this->get('Augwa.ShortURL.UrlStat.Factory')->make();

        $urlStat->createStat($url, $request->headers->get('User-Agent'), $request->getClientIp());

        return new RedirectResponse($url->getUrl(), Response::HTTP_FOUND, [ 'Referer' => $request->getUri() ]);
    }

}