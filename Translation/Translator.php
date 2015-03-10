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
        $this->addResource('memcached_loader', $this->container->get('uber.memcached'), $locale);

        parent::initializeCatalogue($locale);
    }
}
