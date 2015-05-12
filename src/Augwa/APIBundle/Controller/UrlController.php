<?php

namespace Augwa\APIBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Augwa\ShortUrlBundle\Controller as ShortUrl;
use Augwa\APIBundle\Exception as APIException;
use Augwa\ShortUrlBundle\Exception as ShortUrlException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UrlController
 * @package Augwa\APIBundle\Controller
 */
class UrlController extends Base\BaseController
{

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Augwa\APIBundle\Exception\User\MissingParameterException
     * @throws \Augwa\APIBundle\Exception\User\InvalidDataException
     */
    public function createAction(Request $request)
    {
        /** @var $url ShortUrl\UrlController */
        $url = $this->get('Augwa.ShortURL.Url.Factory')->make();

        $arguments = json_decode($request->getContent(), false);

        foreach([ 'url', 'title', 'description' ] as $requiredParameter) {
            if (false === isset($arguments->$requiredParameter)) {
                throw new APIException\User\MissingParameterException(sprintf('%s parameter is missing', $requiredParameter));
            }
        }

        $user = null;

        if (true === isset($arguments->email_address)) {
            /** @var $user ShortUrl\UserController */
            $user = $this->get('Augwa.ShortURL.User.Factory')->make();

            /**
             * see if we can find the account, otherwise create one
             */
            try {
                $user->loadByEmailAddress($arguments->email_address);
            } catch (ShortUrlException\User\NotFoundException $e) {
                /** @var $user ShortUrl\UserController */
                $user = $this->get('Augwa.ShortURL.User.Factory')->make();
                $user->createAccount($arguments->email_address, hash('sha256', (rand() . time())), $request->getClientIp());
            }
        }

        try {
            $url = $url->createUrl($arguments->url, $arguments->title, $arguments->description, $request->getClientIp(), $user);
        }
        catch (ShortUrlException\Url\InvalidDataException $e) {
            throw new APIException\User\InvalidDataException($e->getMessage(), $e->getCode(), $e);
        }

        $content = [
            'id' => $url->getWebId(),
            'url' => $url->getUrl(),
            'title' => $url->getTitle(),
            'description' => $url->getDescription(),
            'date_created' => $url->getDateCreated(),
            'ip_address' => long2ip($url->getIpAddress())
        ];

        if ($user instanceof ShortUrl\UserController) {
            $content['user'] = [
                'id' => $user->getUserId(),
                'email_address' => $user->getEmailAddress(),
                'date_created' => $user->getDateCreated(),
                'ip_address' => long2ip($user->getIpAddress())
            ];
        } else {
            $content['user'] = null;
        }

        return $this->responseBuilder(json_encode($content));
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Augwa\APIBundle\Exception\User\NotFoundException
     */
    public function readAction(Request $request)
    {
        /** @var $url ShortUrl\UrlController */
        $url = $this->get('Augwa.ShortURL.Url.Factory')->make();

        try {
            $url->loadById($url->getWebId($request->get('url_id')));
        }
        catch (ShortUrlException\User\NotFoundException $e) {
            throw new APIException\User\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        $content = [
            'id' => $url->getWebId(),
            'url' => $url->getUrl(),
            'title' => $url->getTitle(),
            'description' => $url->getDescription(),
            'date_created' => $url->getDateCreated(),
            'ip_address' => long2ip($url->getIpAddress())
        ];

        if ($url->getUser() instanceof ShortUrl\UserController) {
            $content['user'] = [
                'id' => $url->getUser()->getUserId(),
                'email_address' => $url->getUser()->getEmailAddress(),
                'date_created' => $url->getUser()->getDateCreated(),
                'ip_address' => long2ip($url->getUser()->getIpAddress())
            ];
        } else {
            $content['user'] = null;
        }

        return $this->responseBuilder(json_encode($content));
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Augwa\APIBundle\Exception\User\NotFoundException
     */
    public function deleteAction(Request $request)
    {
        /** @var $url ShortUrl\UrlController */
        $url = $this->get('Augwa.ShortURL.Url.Factory')->make();

        try {
            $url->loadById($url->getWebId($request->get('url_id')));
        }
        catch (ShortUrlException\User\NotFoundException $e) {
            throw new APIException\User\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        $url->delete();

        return $this->responseBuilder(null);
    }


    public function listAction(Request $request)
    {

        if (null === $request->query->get('user_id')) {
            throw new APIException\User\MissingParameterException('user_id parameter is missing');
        }

        /** @var $user ShortUrl\UserController */
        $user = $this->get('Augwa.ShortURL.User.Factory')->make();

        try {
            $user->loadById($request->query->get('user_id'));
        }
        catch (ShortUrlException\User\NotFoundException $e) {
            throw new APIException\User\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        $criteria = [
            'user.$id' => new \MongoId($request->query->get('user_id'))
        ];

        /** @var $url ShortUrl\UrlController */
        $url = $this->get('Augwa.ShortURL.Url.Factory')->make();

        $page = abs($request->query->get('page', 1));
        $count = $url->count($criteria);
        $limit = abs($request->query->get('limit', 10));

        if ($limit === 0) {
            $limit = 10;
        } elseif ($limit > 50) {
            $limit = 50;
        }

        $offset = ($page-1) * $limit;

        /** @var $results ShortUrl\UrlController[] */
        $results = $url->search($criteria, [ 'urlId' => 'desc' ], $limit, $offset);

        $content = [
            'count' => $count,
            'page' => $page,
            'pages' => (int)ceil($count / $limit),
            'data' => []
        ];

        if ($content['pages'] === 0) {
            $content['pages'] = 1;
        }

        foreach($results as $result) {
            $row = [
                'id' => $result->getWebId(),
                'url' => $result->getUrl(),
                'title' => $result->getTitle(),
                'description' => $result->getDescription(),
                'date_created' => $result->getDateCreated(),
                'ip_address' => long2ip($result->getIpAddress())
            ];

            if ($result->getUser() instanceof ShortUrl\UserController) {
                $row['user'] = [
                    'id' => $result->getUser()->getUserId(),
                    'email_address' => $result->getUser()->getEmailAddress(),
                    'date_created' => $result->getUser()->getDateCreated(),
                    'ip_address' => long2ip($result->getUser()->getIpAddress())
                ];
            } else {
                $row['user'] = null;
            }
            $content['data'][] = $row;
        }

        return $this->responseBuilder(json_encode($content));
    }

}