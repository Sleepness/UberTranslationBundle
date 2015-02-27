<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;

/**
 * Prepare messages for output
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev
 */
class MemcachedMessageCatalogue implements MessageCatalogueInterface
{
    private $preparedTranslations = array();

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

    /**
     * Prepare translations to be displayed
     *
     * @param $domain
     * @param $keyYml
     * @param $message
     * @param $locale
     */
    public function prepareTranslations($domain, $keyYml, $message, $locale)
    {
        $this->preparedTranslations[] = array(
            'domain' => $domain,
            'keyYml' => $keyYml,
            'messageProps' => array(
                'messageText' => $message,
                'locale' => $locale,
            ),
        );
    }

    /**
     * Build message catalogue by locale
     *
     * @param $locale
     * @return array
     */
    public function buildByLocale($locale)
    {
        if (preg_match('/^[a-z]{2}$/', $locale) || preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
            $translations = $this->memcached->getItem($locale);
            if (!$translations) {
                return array();
            }
            foreach ($translations as $memcacheDomain => $messages) {
                foreach ($messages as $ymlKey => $value) {
                    $this->prepareTranslations($memcacheDomain, $ymlKey, $value, $locale);
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * Build message catalogue by domain
     *
     * @param $domain - domain of messages set to be found and displayed
     * @return array
     */
    public function buildByDomain($domain)
    {
        $locales = $this->memcached->getAllKeys();
        foreach ($locales as $key => $locale) {
            if (preg_match('/^[a-z]{2}$/', $locale) || preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
                $translations = $this->memcached->getItem($locale);
                foreach ($translations as $memcacheDomain => $messages) {
                    if ($domain == $memcacheDomain) {
                        foreach ($messages as $ymlKey => $value) {
                            $this->prepareTranslations($domain, $ymlKey, $value, $locale);
                        }
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * Build message catalogue by message key
     *
     * @param $keyYml - key of messages to be displayed
     * @return array
     */
    public function buildByKey($keyYml)
    {
        $locales = $this->memcached->getAllKeys();
        foreach ($locales as $key => $locale) {
            if (preg_match('/^[a-z]{2}$/', $locale) || preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
                $translations = $this->memcached->getItem($locale);
                foreach ($translations as $memcacheDomain => $messages) {
                    foreach ($messages as $ymlKey => $value) {
                        if ($ymlKey == $keyYml) {
                            $this->prepareTranslations($memcacheDomain, $keyYml, $value, $locale);
                        }
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * Build message catalogue by given text value
     *
     * @param $text - text to be matched with existing values
     * @return array
     */
    public function buildByText($text)
    {
        $locales = $this->memcached->getAllKeys();
        foreach ($locales as $key => $locale) {
            if (preg_match('/^[a-z]{2}$/', $locale) || preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
                $translations = $this->memcached->getItem($locale);
                foreach ($translations as $memcacheDomain => $messages) {
                    foreach ($messages as $ymlKey => $value) {
                        if (stripos($value, $text) !== false) {
                            $this->prepareTranslations($memcacheDomain, $ymlKey, $value, $locale);
                        }
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * Get array of translations prepared for output
     *
     * @return array
     */
    public function getAll()
    {
        $locales = $this->memcached->getAllKeys();
        foreach ($locales as $key => $locale) {
            if (preg_match('/^[a-z]{2}$/', $locale) || preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
                $translations = $this->memcached->getItem($locale);
                foreach ($translations as $memcacheDomain => $messages) {
                    foreach ($messages as $ymlKey => $value) {
                        $this->prepareTranslations($memcacheDomain, $ymlKey, $value, $locale);
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }
}
