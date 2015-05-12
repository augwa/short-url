<?php

namespace Augwa\ShortUrlBundle\Controller\Base;

use Augwa\ShortUrlBundle\Document\Base\BaseDocument;
use Symfony\Component\DependencyInjection;

/**
 * Class BaseController
 * @package Augwa\ShortUrlBundle\Controller\Base
 */
abstract class BaseController extends DependencyInjection\ContainerAware
{

    protected $document;

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    abstract protected function repository();

    /**
     * @param BaseDocument
     *
     * @return $this
     */
    abstract protected function setDocument();

    /**
     * @return BaseDocument|null
     */
    public function getDocument()
    {
        return $this->document;
    }

    public function findBy(array $criteria = [], array $orderBy = [], $limit = null, $offset = null)
    {
        return $this->repository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy($criteria)
    {
        return $this->repository()->findOneBy($criteria);
    }

    /**
     * @param string $factory
     * @param array $criteria
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    protected function _search($factory, array $criteria = [], array $order = [], $limit = null, $offset = null)
    {
        /** @var $docs BaseDocument[] */
        $docs = $this->findBy($criteria, $order, $limit, $offset);

        $results = [];
        foreach($docs as $doc) {
            /** @var BaseController $obj */
            $obj = $this->get($factory)->make();
            $obj->loadByDocument($doc);
            $obj->setContainer($this->container());
            $results[] = $obj;
        }

        return $results;
    }

    public function delete()
    {
        $this->manager()->remove($this->document);
        $this->manager()->flush();
    }

    /**
     * @return \Doctrine\Bundle\MongoDBBundle\ManagerRegistry
     */
    protected function model()
    {
        return $this->get('doctrine_mongodb');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function manager()
    {
        return $this->model()->getManager();
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function has($id)
    {
        return $this->container()->has($id);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function get($id)
    {
        return $this->container()->get($id);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function container()
    {
        return $this->container;
    }

    /**
     * @param $id
     *
     * @return string
     */
    protected function parameter($id)
    {
        return $this->container()->getParameter($id);
    }

}