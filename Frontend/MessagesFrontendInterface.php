<?php

namespace Sleepness\UberTranslationBundle\Frontend;

/**
 * Interface what describe methods that needs to be implemented by classes fro messages frontend
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
interface MessagesFrontendInterface
{

    /**
     * Prepare translations to be displayed
     *
     * @param $domain
     * @param $keyYml
     * @param $message
     * @param $locale
     */
    public function prepareTranslations($domain, $keyYml, $message, $locale);

    /**
     * Build message catalogue by locale
     *
     * @param $locale
     * @return array
     */
    public function buildByLocale($locale);

    /**
     * Build message catalogue by domain
     *
     * @param $domain - domain of messages set to be found and displayed
     * @return array
     */
    public function buildByDomain($domain);

    /**
     * Build message catalogue by message key
     *
     * @param $keyYml - key of messages to be displayed
     * @return array
     */
    public function buildByKey($keyYml);

    /**
     * Build message catalogue by given text value
     *
     * @param $text - text to be matched with existing values
     * @return array
     */
    public function buildByText($text);

    /**
     * Get array of translations prepared for output
     *
     * @return array
     */
    public function getAll();
}
