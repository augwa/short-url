<?php

namespace Augwa\APIBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Augwa\ShortUrlBundle\Controller as ShortUrl;
use Augwa\APIBundle\Exception as APIException;
use Augwa\ShortUrlBundle\Exception as ShortUrlException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UrlStatController
 * @package Augwa\APIBundle\Controller
 */
class UrlStatController extends Base\BaseController
{


    public function readAction(Request $request)
    {
        /** @var $url ShortUrl\UrlController */
        $url = $this->get('Augwa.ShortURL.Url.Factory')->make();

        /** @var $urlStat ShortUrl\UrlStatController */
        $urlStat = $this->get('Augwa.ShortURL.UrlStat.Factory')->make();

        try {
            $url->loadById($url->getWebId($request->get('url_code')));
        }
        catch (ShortUrlException\Url\NotFoundException $e) {
            throw new APIException\Url\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        $criteria = [
            'url.$id' => $url->getWebId($request->get('url_code'))
        ];

        $page = abs($request->query->get('page', 1));
        $count = $urlStat->count($criteria);
        $limit = abs($request->query->get('limit', 10));

        if ($limit === 0) {
            $limit = 10;
        } elseif ($limit > 50) {
            $limit = 50;
        }

        $offset = ($page-1) * $limit;

        /** @var $results ShortUrl\UrlStatController[] */
        $results = $urlStat->search($criteria, [ 'dateCreated' => 'desc' ], $limit, $offset);



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
                'id' => $result->getUrlStatId(),
                'user_agent' => $result->getUserAgent(),
                'country' => $result->getCountry(),
                'date_created' => $result->getDateCreated(),
                'ip_address' => long2ip($result->getIpAddress()),
                'url' => null
            ];

            if ($result->getUrl() instanceof ShortUrl\UrlController) {
                $row['url'] = [
                    'id' => $result->getUrl()->getWebId(),
                    'url' => $result->getUrl()->getUrl(),
                    'title' => $result->getUrl()->getTitle(),
                    'description' => $result->getUrl()->getDescription(),
                    'date_created' => $result->getUrl()->getDateCreated(),
                    'ip_address' => long2ip($result->getUrl()->getIpAddress()),
                    'user' => null
                ];
                if ($result->getUrl()->getUser() instanceof ShortUrl\UserController) {
                    $row['url']['user'] = [
                        'id' => $result->getUrl()->getUser()->getUserId(),
                        'email_address' => $result->getUrl()->getUser()->getEmailAddress(),
                        'date_created' => $result->getUrl()->getUser()->getDateCreated(),
                        'ip_address' => long2ip($result->getUrl()->getUser()->getIpAddress())
                    ];
                }
            }

            $content['data'][] = $row;

        }

        return $this->responseBuilder(json_encode($content));
    }

}