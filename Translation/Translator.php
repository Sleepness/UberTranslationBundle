<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;

/**
 * Custom translator class
 *
 * @author Alexandr Zhulev
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
        $this->addLoader('uberMemcached', $this->container->get('uber.translation.loader'));
        $this->addResource('uberMemcached', $this->container->get('uber.memcached'), $locale);

        parent::initializeCatalogue($locale);
    }
}
