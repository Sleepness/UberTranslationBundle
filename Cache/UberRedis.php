<?php

namespace Sleepness\UberTranslationBundle\Cache;

use \Redis;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Wrapper under standard Redis class,
 * which ease work with Redis (using phpredis client - https://github.com/phpredis/phpredis)
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class UberRedis implements ResourceInterface, UberStorageInterface
{
    /**
     * @var \Redis
     */
    private $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * {@inheritdoc}
     */
    public function setConnection($host, $port)
    {
        $this->getRedis()->connect($host, $port);
    }

    /**
     * @return Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * {@inheritdoc}
     */
    public function addItem($key, $value, $expiration = null)
    {
        if ($expiration === null) {
            $expiration = 60 * 60 * 24 * 30; // default expires after 30 days
        }
        $encoded_value = json_encode($value);

        return $this->getRedis()->set($key, $encoded_value, $expiration);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        $response = json_decode($this->getRedis()->get($key));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllKeys()
    {
        return $this->getRedis()->keys('*');
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($key)
    {
        $this->getRedis()->delete($key);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function dropCache()
    {
        return $this->getRedis()->flushAll();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'uberRedis';
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
    }
} 
