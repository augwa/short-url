<?php

namespace Augwa\ShortUrlBundle\Controller;

use Augwa\ShortUrlBundle\Document;
use Augwa\ShortUrlBundle\Exception;
use GeoIp2;

class UrlStatController extends Base\BaseController
{

    /** @var UserController|null */
    protected $url;

    /**
     * @param Document\UrlStat $document
     *
     * @return $this
     */
    protected function setDocument(Document\UrlStat $document = null)
    {
        $this->url = null;
        $this->document = $document;
        return $this;
    }

    /**
     * @return Document\UrlStat
     */
    public function getDocument()
    {
        if ($this->document === null) {
            $this->document = new Document\UrlStat;
        }
        return parent::getDocument();
    }

    /**
     * @return \MongoId $urlStatId
     */
    public function getUrlStatId()
    {
        return $this->getDocument()->getUrlStatId();
    }

    /**
     * @param int $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress)
    {
        $this->getDocument()->setIpAddress($ipAddress);
        return $this;
    }

    /**
     * @return int $ipAddress
     */
    public function getIpAddress()
    {
        return $this->getDocument()->getIpAddress();
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->getDocument()->setCountry($country);
        return $this;
    }

    /**
     * @return string $country
     */
    public function getCountry()
    {
        return $this->getDocument()->getCountry();
    }

    /**
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->getDocument()->setUserAgent($userAgent);
        return $this;
    }

    /**
     * @return string $userAgent
     */
    public function getUserAgent()
    {
        return $this->getDocument()->getUserAgent();
    }

    /**
     * @param int|\MongoTimestamp $dateCreated
     * @return $this
     */
    protected function setDateCreated($dateCreated = null)
    {
        $this->getDocument()->setDateCreated($dateCreated);
        return $this;
    }

    /**
     * @return int $dateCreated
     */
    public function getDateCreated()
    {
        if ($this->getDocument()->getDateCreated() instanceof \MongoTimestamp) {
            return (int)$this->getDocument()->getDateCreated()->__toString();
        }
        return $this->getDocument()->getDateCreated();
    }

    /**
     * @param UrlController|null $url
     * @return $this
     */
    public function setUrl(UrlController $url = null)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return UrlController
     */
    public function getUrl()
    {
        if ($this->url === null) {
            if ($this->getDocument()->getUrl() instanceof Document\Url) {
                $this->url = new UrlController;
                $this->url->loadByDocument($this->getDocument()->getUrl());
                $this->url->setContainer($this->container());
            }
        }
        return $this->url;
    }

    protected function repository()
    {
        return $this->model()->getRepository('AugwaShortUrlBundle:UrlStat');
    }

    public function save()
    {
        if ($this->getUrl() instanceof UrlController) {
            $this->getUrl()->save();
            $this->getDocument()->setUrl($this->getUrl()->getDocument());
        }

        if ($this->getDateCreated() === null) {
            $this->setDateCreated(time());
        }
        $this->manager()->persist($this->document);

        $this->manager()->flush();
    }

    /**
     * @param UrlController $url
     * @param $userAgent
     * @param $ipAddress
     *
     * @return UrlStatController
     */
    public function createStat(UrlController $url, $userAgent, $ipAddress)
    {
        $userAgent = trim($userAgent);
        $ipAddress = '69.165.175.254';

        $geoIp2 = new GeoIp2\Database\Reader($this->parameter('geoip2.database_location'));

        try {
            $country = $geoIp2->country($ipAddress);
            $country = $country->country->name;
        } catch(GeoIp2\Exception\AddressNotFoundException $e) {
            $country = null;
        }

        /** @var $urlStat UrlStatController */
        $urlStat = $this->get('Augwa.ShortURL.UrlStat.Factory')->make();

        $urlStat->setUrl($url);
        $urlStat->setUserAgent($userAgent);
        $urlStat->setCountry($country);
        $urlStat->setIpAddress(ip2long($ipAddress));

        $urlStat->save();

        return $urlStat;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function loadById($id)
    {
        return $this->setDocument($this->findById($id));
    }

    /**
     * @param Document\UrlStat $urlStat
     *
     * @return $this
     */
    public function loadByDocument(Document\UrlStat $urlStat)
    {
        return $this->setDocument($urlStat);
    }

    /**
     * @param array $criteria
     *
     * @return int
     */
    public function count(array $criteria = [])
    {
        $qb = $this->manager()->createQueryBuilder('AugwaShortUrlBundle:UrlStat');
        foreach($criteria as $field => $value) {
            $qb->field($field)->equals($value);
        }
        return $qb->getQuery()->execute()->count();
    }

    /**
     * @param array $criteria
     * @param array $order
     * @param null $limit
     * @param null $offset
     *
     * @return UserController[]
     */
    public function search(array $criteria = [], array $order = [], $limit = null, $offset = null)
    {
        return $this->_search('Augwa.ShortURL.UrlStat.Factory', $criteria, $order, $limit, $offset);
    }

    /**
     * @param $id
     *
     * @return Document\UrlStat
     * @throws Exception\UrlStat\NotFoundException
     */
    protected function findById($id)
    {
        /** @var Document\UrlStat $urlStat */
        $urlStat = $this->findOneBy(
            [
                'urlId' => $id
            ]
        );

        if ($urlStat === null) {
            throw new Exception\UrlStat\NotFoundException(sprintf('urlStat with id "%s" was not found', $id));
        }

        return $urlStat;
    }

}