<?php

namespace Sleepness\UberTranslationBundle\Translation;

/**
 * Prepare messages for output
 */
class MemcachedMessageCatalogue
{
    private $preparedTranslations = array();

    /**
     * Add new translations of certain locale to be prepared for output
     *
     * @param $locale
     * @param $translations
     */
    public function add($locale, $translations)
    {
        if (!$translations) {
            return;
        }
        foreach ($translations as $domain => $messages) {
            foreach ($messages as $keyYml => $message) {
                $this->preparedTranslations[] = array(
                    'domain' => $domain,
                    'keyYml' => $keyYml,
                    'messages' => array(
                        array(
                            'messageText' => $message,
                            'locale' => $locale,
                        )
                    ),
                );
            }
        }
    }

    /**
     * Build message catalog by domain
     *
     * @param $domain
     * @param $messages
     */
    public function buildByDomain($domain, $messages)
    {
        // will added build by domain functional
    }

    /**
     * Get array of translations prepared for output
     *
     * @return array
     */
    public function getAll()
    {
        return $this->preparedTranslations;
    }
}
