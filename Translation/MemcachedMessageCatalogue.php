<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;

/**
 * Prepare messages for output
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class MemcachedMessageCatalogue implements MessageCatalogueInterface
{
    private $preparedTranslations = array();
    private $memcached;

    /**
     * Constructor.
     *
     * @param \Sleepness\UberTranslationBundle\Cache\UberMemcached $memcached
     *
     */
    public function __construct(UberMemcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * Gets the catalogue locale.
     *
     * @return string The locale
     *
     * @api
     */
    public function getLocale()
    {
        // TODO: Implement getLocale() method.
    }

    /**
     * Gets the domains.
     *
     * @return array An array of domains
     *
     * @api
     */
    public function getDomains()
    {
        // TODO: Implement getDomains() method.
    }

    /**
     * Gets the messages within a given domain.
     *
     * If $domain is null, it returns all messages.
     *
     * @param string $domain The domain name
     *
     * @return array An array of messages
     *
     * @api
     */
    public function all($domain = null)
    {
        // TODO: Implement all() method.
    }

    /**
     * Sets a message translation.
     *
     * @param string $id The message id
     * @param string $translation The messages translation
     * @param string $domain The domain name
     *
     * @api
     */
    public function set($id, $translation, $domain = 'messages')
    {
        // TODO: Implement set() method.
    }

    /**
     * Checks if a message has a translation.
     *
     * @param string $id The message id
     * @param string $domain The domain name
     *
     * @return bool true if the message has a translation, false otherwise
     *
     * @api
     */
    public function has($id, $domain = 'messages')
    {
        // TODO: Implement has() method.
    }

    /**
     * Checks if a message has a translation (it does not take into account the fallback mechanism).
     *
     * @param string $id The message id
     * @param string $domain The domain name
     *
     * @return bool true if the message has a translation, false otherwise
     *
     * @api
     */
    public function defines($id, $domain = 'messages')
    {
        // TODO: Implement defines() method.
    }

    /**
     * Gets a message translation.
     *
     * @param string $id The message id
     * @param string $domain The domain name
     *
     * @return string The message translation
     *
     * @api
     */
    public function get($id, $domain = 'messages')
    {
        // TODO: Implement get() method.
    }

    /**
     * Sets translations for a given domain.
     *
     * @param array $messages An array of translations
     * @param string $domain The domain name
     *
     * @api
     */
    public function replace($messages, $domain = 'messages')
    {
        // TODO: Implement replace() method.
    }

    /**
     * Merges translations from the given Catalogue into the current one.
     *
     * The two catalogues must have the same locale.
     *
     * @param MessageCatalogueInterface $catalogue A MessageCatalogueInterface instance
     *
     * @api
     */
    public function addCatalogue(MessageCatalogueInterface $catalogue)
    {
        // TODO: Implement addCatalogue() method.
    }

    /**
     * Merges translations from the given Catalogue into the current one
     * only when the translation does not exist.
     *
     * This is used to provide default translations when they do not exist for the current locale.
     *
     * @param MessageCatalogueInterface $catalogue A MessageCatalogueInterface instance
     *
     * @api
     */
    public function addFallbackCatalogue(MessageCatalogueInterface $catalogue)
    {
        // TODO: Implement addFallbackCatalogue() method.
    }

    /**
     * Gets the fallback catalogue.
     *
     * @return MessageCatalogueInterface|null A MessageCatalogueInterface instance or null when no fallback has been set
     *
     * @api
     */
    public function getFallbackCatalogue()
    {
        // TODO: Implement getFallbackCatalogue() method.
    }

    /**
     * Returns an array of resources loaded to build this collection.
     *
     * @return ResourceInterface[] An array of resources
     *
     * @api
     */
    public function getResources()
    {
        // TODO: Implement getResources() method.
    }

    /**
     * Adds a resource for this collection.
     *
     * @param ResourceInterface $resource A resource instance
     *
     * @api
     */
    public function addResource(ResourceInterface $resource)
    {
        // TODO: Implement addResource() method.
    }

    /**
     * Adds translations for a given domain.
     *
     * @param array  $messages An array of translations
     * @param string $domain   The domain name
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
