<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;

/**
 * Prepare messages for output
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class MemcachedMessageCatalogue implements MessageCatalogueInterface
{
    /**
     * @var \Sleepness\UberTranslationBundle\Cache\UberMemcached
     */
    private $memcached;

    /**
     * Constructor.
     *
     * @param \Sleepness\UberTranslationBundle\Cache\UberMemcached $memcached
     */
    public function __construct(UberMemcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        // TODO: Implement getLocale() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getDomains()
    {
        // TODO: Implement getDomains() method.
    }

    /**
     * {@inheritdoc}
     */
    public function all($domain = null)
    {
        // TODO: Implement all() method.
    }

    /**
     * {@inheritdoc}
     */
    public function set($id, $translation, $domain = 'messages')
    {
        // TODO: Implement set() method.
    }

    /**
     * {@inheritdoc}
     */
    public function has($id, $domain = 'messages')
    {
        // TODO: Implement has() method.
    }

    /**
     * {@inheritdoc}
     */
    public function defines($id, $domain = 'messages')
    {
        // TODO: Implement defines() method.
    }

    /**
     * {@inheritdoc}
     */
    public function get($id, $domain = 'messages')
    {
        // TODO: Implement get() method.
    }

    /**
     * {@inheritdoc}
     */
    public function replace($messages, $domain = 'messages')
    {
        // TODO: Implement replace() method.
    }

    /**
     * {@inheritdoc}
     */
    public function addCatalogue(MessageCatalogueInterface $catalogue)
    {
        // TODO: Implement addCatalogue() method.
    }

    /**
     * {@inheritdoc}
     */
    public function addFallbackCatalogue(MessageCatalogueInterface $catalogue)
    {
        // TODO: Implement addFallbackCatalogue() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getFallbackCatalogue()
    {
        // TODO: Implement getFallbackCatalogue() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getResources()
    {
        // TODO: Implement getResources() method.
    }

    /**
     * {@inheritdoc}
     */
    public function addResource(ResourceInterface $resource)
    {
        // TODO: Implement addResource() method.
    }

    /**
     * {@inheritdoc}
     */
    public function add($messages, $domain = 'messages')
    {
        // TODO: Implement add() method.
    }
}
