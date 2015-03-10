<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;

/**
 * Custom translator class
 *
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class Translator extends BaseTranslator
{
    /**
     * {@inheritdoc}
     */
    protected function loadCatalogue($locale)
    {
        $this->initializeCatalogue($locale);
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeCatalogue($locale)
    {
        $memcached = $this->container->get('uber.memcached');
        $domains = array_keys($memcached->getItem($locale));
        foreach ($domains as $domain) {
            $this->addResource('memcached_loader', $memcached, $locale, $domain);
        }

        parent::initializeCatalogue($locale);
    }
}
