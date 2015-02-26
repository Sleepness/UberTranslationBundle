<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;

/**
 * Prepare messages for output
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class MemcachedMessageCatalogue
{
    private $preparedTranslations = array();
    private $memcached;

    public function __construct(UberMemcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * Add new translation into array that will be displayed
     *
     * @param $domain
     * @param $keyYml
     * @param $message
     * @param $locale
     */
    public function add($domain, $keyYml, $message, $locale)
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
                    $this->add($memcacheDomain, $ymlKey, $value, $locale);
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
                            $this->add($domain, $ymlKey, $value, $locale);
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
                            $this->add($memcacheDomain, $keyYml, $value, $locale);
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
                            $this->add($memcacheDomain, $ymlKey, $value, $locale);
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
                        $this->add($memcacheDomain, $ymlKey, $value, $locale);
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }
}
