<?php

namespace Sleepness\UberTranslationBundle\Cache;

use \Memcached;

class UberMemcached
{

    private $memcached;

    public function __construct(\Memcached $memcached)
    {
        $this->memcached = $memcached;
        $this->memcached->addServer('localhost', 11211);
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
     * @return void
     */
    public function setConnection()
    {
        $this->getMemcached()->addServer('localhost', 11211);
    }

    /**
     * Adds the item into memcached
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
     * Getting item from cache
     *
     * @param $key
     * @return mixed
     */
    public function getItem($key)
    {
        return $this->getMemcached()->get($key);
    }

    /**
     * Check if item with given key exists in cache
     *
     * @param $key
     * @return bool
     */
    public function hasItem($key)
    {
        return (true === $this->getMemcached()->get($key));
    }

    /**
     * Delete item from memcache
     *
     * @param $key
     * @return bool
     */
    public function deleteItem($key)
    {
        return $this->getMemcached()->delete($key);
    }

    /**
     * Remove all items from memcache(invalidate)
     *
     * @param int $delay
     * @return bool
     */
    public function dropCache($delay = 0)
    {
        return $this->getMemcached()->flush($delay);
    }
}
