<?php

namespace Sleepness\UberTranslationBundle\Storage;

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Wrapper under standard MongoClient
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class UberMongo implements ResourceInterface, UberStorageInterface
{
    /**
     * @var \MongoDB|null
     */
    private $mongoDB = null;

    /**
     * {@inheritdoc}
     */
    public function setConnection($host = 'localhost', $port = '27017')
    {
        try {
            $client = new \MongoClient("mongodb://$host:$port");
            $this->mongoDB = new \MongoDB($client, 'uber_translations');

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addItem($key, $value, $expiration = null)
    {
        // TODO: Implement addItem() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        // TODO: Implement getItem() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getAllKeys()
    {
        // TODO: Implement getAllKeys() method.
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($key)
    {
        // TODO: Implement deleteItem() method.
    }

    /**
     * {@inheritdoc}
     */
    public function dropCache()
    {
        // TODO: Implement dropCache() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        // TODO: Implement isFresh() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        // TODO: Implement getResource() method.
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'uberMongo';
    }
}
