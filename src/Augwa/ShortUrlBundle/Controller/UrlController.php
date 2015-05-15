<?php

namespace Augwa\ShortUrlBundle\Controller;

use Augwa\ShortUrlBundle\Document;
use Augwa\ShortUrlBundle\Exception;

class UrlController extends Base\BaseController
{

    /** @var UserController|null */
    protected $user;

    protected $baseChars = 'BDqClkwrh03jJdYRbLF49x2HS1yZMfcNv7PQ86XTgzpt5VnsmKWG';

    /**
     * @param Document\Url $document
     *
     * @return $this
     */
    protected function setDocument(Document\Url $document = null)
    {
        $this->user = null;
        $this->document = $document;
        return $this;
    }

    /**
     * @return Document\Url
     */
    public function getDocument()
    {
        if ($this->document === null) {
            $this->document = new Document\Url;
        }
        return parent::getDocument();
    }

    /**
     * @return int $urlId
     */
    public function getUrlId()
    {
        return $this->getDocument()->getUrlId();
    }

    /**
     * @param string $id
     *
     * @return string|int
     */
    public function getWebId($id = null)
    {
        if ($id === null) {
            return $this->baseEncode($this->getUrlId());
        } else {
            return $this->baseDecode($id);
        }
    }

    /**
     * @param $num
     *
     * @return string
     */
    private function baseEncode($num)
    {
        $r = $num % 62;
        $res = $this->baseChars[$r];
        $q = floor($num / 62);
        while ($q) {
            $r = $q % 62;
            $q = floor($q / 62);
            $res = $this->baseChars[$r] . $res;
        }
        return $res;
    }

    /**
     * @param $num
     *
     * @return bool|int
     */
    private function baseDecode($num)
    {
        $limit = strlen($num);
        $res = strpos($this->baseChars, $num[0]);
        for($i = 1; $i < $limit; $i++) {
            $res = 62 * $res + strpos($this->baseChars, $num[$i]);
        }
        return (int)$res;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url = null)
    {
        $this->getDocument()->setUrl($url);
        return $this;
    }

    /**
     * @return string $url
     */
    public function getUrl()
    {
        return $this->getDocument()->getUrl();
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title = null)
    {
        $this->getDocument()->setTitle($title);
        return $this;
    }

    /**
     * @return string $title
     */
    public function getTitle()
    {
        return $this->getDocument()->getTitle();
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description = null)
    {
        $this->getDocument()->setDescription($description);
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->getDocument()->getDescription();
    }

    /**
     * @param int $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress = null)
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
     * @param UserController|null $user
     * @return $this
     */
    public function setUser(UserController $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return UserController|null
     */
    public function getUser()
    {
        if ($this->user === null) {
            if ($this->getDocument()->getUser() instanceof Document\User) {
                $this->user = new UserController;
                $this->user->loadByDocument($this->getDocument()->getUser());
                $this->user->setContainer($this->container());
            }
        }
        return $this->user;
    }

    protected function repository()
    {
        return $this->model()->getRepository('AugwaShortUrlBundle:Url');
    }

    public function delete()
    {
        $this->manager()->remove($this->document);
        $this->manager()->flush();
    }

    public function save()
    {
        if ($this->getUser() instanceof UserController) {
            $this->getUser()->save();
            $this->getDocument()->setUser($this->getUser()->getDocument());
        }

        if ($this->getDateCreated() === null) {
            $this->setDateCreated(time());
        }
        $this->manager()->persist($this->document);

        $this->manager()->flush();
    }

    /**
     * @param string $fullUrl
     * @param string $title
     * @param string $description
     * @param string $ipAddress
     * @param UserController $user
     *
     * @return UrlController
     *
     * @throws Exception\User\InvalidDataException
     */
    public function createUrl($fullUrl, $title, $description, $ipAddress, $user = null)
    {

        if (false === ($user instanceof UserController)) {
            $user = null;
        }

        $fullUrl = trim($fullUrl);
        $title = strip_tags(trim($title));
        $description = strip_tags(trim($description));
        $ipAddress = ip2long($ipAddress);

        if (false === filter_var($fullUrl, FILTER_VALIDATE_URL)) {
            throw new Exception\Url\InvalidDataException('fullUrl is not a valid url');
        }

        /** @var $url UrlController */
        $url = $this->get('Augwa.ShortURL.Url.Factory')->make();

        $url->setUrl($fullUrl);
        $url->setTitle($title);
        $url->setDescription($description);
        $url->setIpAddress($ipAddress);
        if ($user instanceof UserController) {
            $url->setUser($user);
        }

        $url->save();

        return $url;
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
     * @param Document\Url $url
     *
     * @return $this
     */
    public function loadByDocument(Document\Url $url)
    {
        return $this->setDocument($url);
    }

    /**
     * @param array $criteria
     *
     * @return int
     */
    public function count(array $criteria = [])
    {
        $qb = $this->manager()->createQueryBuilder('AugwaShortUrlBundle:Url');
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
        return $this->_search('Augwa.ShortURL.Url.Factory', $criteria, $order, $limit, $offset);
    }

    /**
     * @param $id
     *
     * @return Document\Url
     * @throws Exception\Url\NotFoundException
     */
    protected function findById($id)
    {
        /** @var Document\Url $url */
        $url = $this->repository()->findOneBy(
            [
                'urlId' => $id
            ]
        );

        if ($url === null) {
            throw new Exception\Url\NotFoundException(sprintf('url with id "%s" was not found', $this->baseEncode($id)));
        }

        return $url;
    }

}