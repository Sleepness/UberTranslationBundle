<?php

namespace Sleepness\UberTranslationBundle\Translation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            foreach ($messages as $key => $message) {
                $this->preparedTranslations[] = array(
                    'domain' => $domain,
                    'key' => $key,
                    'message' => array(
                        'messageText' => $message,
                        'locale' => $locale,
                    ),
                );
            }
        }
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
