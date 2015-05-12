<?php

namespace Augwa\APIBundle\Controller\Base;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package Augwa\APIBundle\Controller\Base
 */
class BaseController extends ContainerAware
{

    protected static $extraHeaders = [];

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->container()->has($id);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function get($id)
    {
        return $this->container()->get($id);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function parameter($id)
    {
        return $this->container()->getParameter($id);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function container()
    {
        return $this->container;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    protected function addHeader($key, $value)
    {
        self::$extraHeaders[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return self::$extraHeaders;
    }

    /**
     * @return $this
     */
    protected function resetHeaders()
    {
        self::$extraHeaders = [];
        return $this;
    }

    /**
     * @param string $content
     * @param int $statusCode
     * @param array $headers
     *
     * @return Response
     */
    protected function responseBuilder($content, $statusCode = Response::HTTP_OK, $headers = [])
    {
        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'max-age=0, must-revalidate, public, s-maxage=0',
            'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT'
        ];

        $headers = array_replace(
            $defaultHeaders,
            $this->getHeaders(),
            $headers
        );

        $this->resetHeaders();

        $response = new Response;

        $response->setContent($content);
        $response->setStatusCode($statusCode);

        $response->headers->add($headers);

        return $response;
    }

}