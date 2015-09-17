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
class UberRedis implements ResourceInterface
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
     * Set the connection with Redis server
     *
     * @param string $host
     * @param int $port
     * @return bool TRUE on success or FALSE failure.
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
     * Add the item into Redis. If value with given keys exist,
     * it will only replace the value.
     *
     * @param $key
     * @param $value
     * @param null $expiration
     * @return bool
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
     * Get item from Redis
     *
     * @param $key
     * @return mixed
     */
    public function getItem($key)
    {
        $response = json_decode($this->getRedis()->get($key));

        return $response;
    }

    /**
     * Get all keys(locales identifiers) stored on all the Redis storage
     *
     * @return array - locales identifiers
     */
    public function getAllKeys()
    {
        return $this->getRedis()->keys('*');
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
