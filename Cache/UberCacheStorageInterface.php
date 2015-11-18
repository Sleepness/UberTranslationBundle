<?php

namespace Sleepness\UberTranslationBundle\Cache;


interface UberCacheStorageInterface
{
    /**
     * Set the connection with memcached server
     *
     * @param string $host
     * @param int $port
     * @return bool TRUE on success or FALSE failure.
     */
    public function setConnection($host, $port);

    /**
     * Add the item into memcached. If value with given keys exist,
     * it will only replace the value.
     *
     * @param $key
     * @param $value
     * @param null $expiration
     * @return bool
     */
    public function addItem($key, $value, $expiration = null);

    /**
     * Get item from memcached
     *
     * @param $key
     * @return mixed
     */
    public function getItem($key);

    /**
     * Get all keys(locales identifiers) stored on all the memcached storages
     *
     * @return array - locales identifiers
     */
    public function getAllKeys();

    /**
     * Delete item from memcached
     *
     * @param $key
     * @return bool
     */
    public function deleteItem($key);

    /**
     * Remove all items from memcached (invalidate)
     *
     * @param int $delay
     * @return bool
     */
    public function dropCache($delay = 0);
} 
