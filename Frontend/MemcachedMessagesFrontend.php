<?php

namespace Sleepness\UberTranslationBundle\Frontend;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;

/**
 * Prepare messages for output from memcached
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class MemcachedMessagesFrontend implements MessagesFrontendInterface
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
