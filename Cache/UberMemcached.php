<?php

namespace Sleepness\UberTranslationBundle\Cache;

use \Memcached;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Wrapper under standard Memcached class,
 * which ease work with memcached
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
     * Add the item into memcached
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
     * Get all keys stored on all the memcached storages
     *
     * @return array
     */
    public function getAllKeys()
    {
        return $this->getMemcached()->getAllKeys();
    }

    /**
     * Get all messages by domain
     *
     * @param $domain
     * @return array
     */
    public function getAllByDomain($domain)
    {
        $translationsByDomain = array();
        $locales = $this->getAllKeys();
        foreach ($locales as $key => $locale) {
            $translations = $this->getItem($locale);
            foreach ($translations as $memcacheDomain => $messages) {
                if ($domain == $memcacheDomain) {
                    foreach ($messages as $ymlKey => $value) {
                        $translationsByDomain[]= array(
                            'domain' => $domain,
                            'keyYml' => $ymlKey,
                            'messages' => array(
                                array(
                                    'messageText' => $value,
                                    'locale' => $locale,
                                )
                            ),
                        );
                    }
                }
            }
        }

        return $translationsByDomain;
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
