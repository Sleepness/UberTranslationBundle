<?php

namespace Sleepness\UberTranslationBundle\Storage;

/**
 * Interface for all storage classes
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
interface UberStorageInterface
{
    /**
     * Set the connection with storage server
     *
     * @param string $host
     * @param int $port
     * @return bool TRUE on success or FALSE failure.
     */
    public function setConnection($host, $port);

    /**
     * Add the item into storage. If value with given keys exist,
     * it will only replace the value.
     *
     * @param $key
     * @param $value
     * @param null $expiration
     * @return bool
     */
    public function addItem($key, $value, $expiration = null);

    /**
     * Get item from storage by key
     *
     * @param $key
     * @return mixed
     */
    public function getItem($key);

    /**
     * Get all keys(locales identifiers) from storage
     *
     * @return array - locales identifiers
     */
    public function getAllKeys();

    /**
     * Delete item from storage
     *
     * @param $key
     * @return bool
     */
    public function deleteItem($key);

    /**
     * Remove all items from storage (invalidate)
     *
     * @return bool
     */
    public function dropCache();
} 
