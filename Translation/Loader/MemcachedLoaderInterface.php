<?php

namespace Sleepness\UberTranslationBundle\Translation\Loader;

use Sleepness\UberTranslationBundle\Translation\MemcachedMessageCatalogue;

interface MemcachedLoaderInterface
{
    /**
     * @param $resource
     * @param $locale
     * @param string $domain
     * @return MemcachedMessageCatalogue
     */
    public function load($resource, $locale, $domain = 'messages');

} 
