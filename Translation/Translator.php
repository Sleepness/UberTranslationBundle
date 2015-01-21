<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;

class Translator extends BaseTranslator
{
    protected function initializeCatalogue($locale)
    {
        // Register our custom loader
        $this->addLoader('uberMemcached', $this->container->get('uber.translation.loader'));

        parent::initializeCatalogue($locale);
    }
}
