<?php

namespace Sleepness\UberTranslationBundle\Translation\Loader;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Translation\MessageCatalogue;

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

        $messagesOfDomain = $resource->getItem($locale);
        $messages = $messagesOfDomain[$domain];

        // no messages in cache
        if (null === $messages) {
            $messages = array();
        }

        if (!is_array($messages)) {
            throw new InvalidResourceException(sprintf('The resource "%s" must contain an array.', $resource));
        }

        $catalogue = new MessageCatalogue($locale);
        $catalogue->add($messages, $domain);
        $catalogue->addResource($resource);

        return $catalogue;
    }
}
