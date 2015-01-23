<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;

class Translator extends BaseTranslator
{

    protected function loadCatalogue($locale)
    {
        $this->initializeCatalogue($locale);
    }

    protected function initializeCatalogue($locale)
    {
        $this->addLoader('uberMemcached', $this->container->get('uber.translation.loader'));
        $this->addResource('uberMemcached', $this->container->get('uber.memcached'), $locale);

        parent::initializeCatalogue($locale);
    }
}
