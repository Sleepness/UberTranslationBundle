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
class UberMemcached implements ResourceInterface, UberStorageInterface
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
     * @return Memcached
     */
    public function getMemcached()
    {
        return $this->memcached;
    }

    /**
     * {@inheritdoc}
     */
    public function setConnection($host, $port)
    {
        $this->getMemcached()->addServer($host, $port);
    }

    /**
     * {@inheritdoc}
     */
    public function addItem($key, $value, $expiration = null)
    {
        if ($expiration === null) {
            $expiration = 60 * 60 * 24 * 30; // default expires after 30 days
        }

        return $this->getMemcached()->set($key, $value, $expiration);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        return $this->getMemcached()->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllKeys()
    {
        $allKeys = $this->getMemcached()->getAllKeys();
        $locales = array();
        foreach ($allKeys as $key) {
            if (!preg_match('/^[a-z]{2}_[a-zA-Z]{2}$|[a-z]{2}/', $key)) {
                continue;
            }
            $locales[] = $key;
        }

        return $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($key)
    {
        return $this->getMemcached()->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function dropCache()
    {
        return $this->getMemcached()->flush(0);
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
