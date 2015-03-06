<?php

namespace Sleepness\UberTranslationBundle\Frontend;

/**
 * Interface, that describes methods, need to be implemented by each class for messages frontend
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
interface MessagesFrontendInterface
{
    /**
     * Prepare translations to be displayed
     *
     * @param $domain - messages domain
     * @param $key - messages key
     * @param $message - message text
     * @param $locale - locale
     */
    public function prepareTranslations($domain, $key, $message, $locale);

    /**
     * Build message catalogue by locale
     *
     * @param $locale - locale
     * @return array - translations
     */
    public function buildByLocale($locale);

    /**
     * Build message catalogue by domain
     *
     * @param $domain - domain of messages set to be found and displayed
     * @return array - translations
     */
    public function buildByDomain($domain);

    /**
     * Build message catalogue by message key
     *
     * @param $keyYml - key of messages to be displayed
     * @return array - translations
     */
    public function buildByKey($keyYml);

    /**
     * Build message catalogue by given text value
     *
     * @param $text - text to be matched with existing values
     * @return array - translations
     */
    public function buildByText($text);

    /**
     * Get array of translations prepared for output
     *
     * @return array - translations
     */
    public function getAll();
}
