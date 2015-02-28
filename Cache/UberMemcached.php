<?php

namespace Sleepness\UberTranslationBundle\Cache;

use \Memcached;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Wrapper under standard Memcached class,
 * which ease work with memcached
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class UberMemcached implements ResourceInterface
{
    private $memcached;

    public function __construct(\Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'uberMemcached';
    }

    /**
     * @return Memcached|null
     */
    public function getMemcached()
    {
        return $this->memcached;
    }

    /**
     * Set the connection with memcached server
     *
     * @param string $host
     * @param int $port
     * @return bool TRUE on success or FALSE failure.
     */
    public function setConnection($host, $port)
    {
        $this->getMemcached()->addServer($host, $port);
    }

    /**
     * Add the item into memcached. If value with given keys exist,
     * it will only replace the value.
     *
     * @param $key
     * @param $value
     * @param null $expiration
     * @return bool
     */
    public function addItem($key, $value, $expiration = null)
    {
        if ($expiration == null) {
            $expiration = 60 * 60 * 24 * 30; // default expires after 30 days
        }
        return $this->getMemcached()->set($key, $value, $expiration);
    }

    /**
     * Get item from memcached
     *
     * @param $key
     * @return mixed
     */
    public function getItem($key)
    {
        return $this->getMemcached()->get($key);
    }

    /**
     * Get all keys(locales identifiers) stored on all the memcached storages
     *
     * @return array - locales identifiers
     */
    public function getAllKeys()
    {
        return $this->getMemcached()->getAllKeys();
    }

    /**
     * Delete item from memcached
     *
     * @param $key
     * @return bool
     */
    public function deleteItem($key)
    {
        return $this->getMemcached()->delete($key);
    }

    /**
     * Remove all items from memcached (invalidate)
     *
     * @param int $delay
     * @return bool
     */
    public function dropCache($delay = 0)
    {
        return $this->getMemcached()->flush($delay);
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
