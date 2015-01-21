<?php

namespace Sleepness\UberTranslationBundle\Translation\Loader;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;

class MemcachedLoader extends ArrayLoader implements LoaderInterface
{
    private $memcached;

    /**
     * @param UberMemcached $memcached
     */
    public function setUberMemcached(UberMemcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $locale, $domain = 'messages')
    {
        if (null == $resource) {
            $resource = $this->memcached;
        }
        $messages = $resource->getItem($locale);
        // no messages in cache
        if (null === $messages) {
            $messages = array();
        }
        $catalogue = parent::load($messages, $locale, $domain);

        return $catalogue;
    }
}
