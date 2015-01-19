<?php

namespace Sleepness\UberTranslationBundle\Translation;

use Sleepness\UberTranslationBundle\Translation\Loader\MemcachedLoaderInterface;

class Loader implements MemcachedLoaderInterface
{
    public function load($resource, $locale, $domain = 'mesasages')
    {

    }
}
