<?php

namespace Sleepness\UberTranslationBundle\Translation\Loader;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

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
            $resource = 'localhost:11211';
        }
        $data = explode(":", $resource);
        $host = $data[0];
        $port = $data[1];

        if (!$this->memcached->setConnection($host, $port)) {
            throw new NotFoundResourceException(sprintf('Resource "%s" not found.', $resource));
        }

        $messages = $resource->getItem($locale);
        // no messages in cache
        if (null === $messages) {
            $messages = array();
        }

        if (!is_array($messages)) {
            throw new InvalidResourceException(sprintf('The resource "%s" must contain an array.', $resource));
        }
        $catalogue = parent::load($messages, $locale, $domain);
        $catalogue->addResource($this->memcached);

        return $catalogue;
    }
}
