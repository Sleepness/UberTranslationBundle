<?php

namespace Sleepness\UberTranslationBundle\Translation\Loader;

interface MemcachedLoaderInterface
{
    /**
     * @param $locale
     * @return mixed
     */
    public function load($locale);
}
