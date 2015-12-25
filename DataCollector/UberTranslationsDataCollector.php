<?php

namespace Sleepness\UberTranslationBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sleepness\UberTranslationBundle\Storage\UberMemcached;

/**
 * Collect translations from Memcache to Symfony profiler
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class UberTranslationsDataCollector extends DataCollector
{
    /**
     * @var UberMemcached $uberMemcached
     */
    private $uberMemcached;

    /**
     * @param UberMemcached $uberMemcached
     */
    public function __construct(UberMemcached $uberMemcached)
    {
        $this->uberMemcached = $uberMemcached;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $memcachedKeys = $this->uberMemcached->getAllKeys();
        $catalogues = array(); // prepare array for catalogues
        foreach ($memcachedKeys as $key) { // run through locales
            $catalogues[$key] = $this->uberMemcached->getItem($key);
        }
        $this->data = array(
            'memcached_translations' => $catalogues,
        );
    }

    /**
     * Allow fetch memcached translation in data collector template
     *
     * @return array
     */
    public function getTranslations()
    {
        return $this->data['memcached_translations'];
    }

    /**
     * Allow get count of available locales in collector template
     *
     * @return number
     */
    public function countAvailableLocales()
    {
        return count($this->data['memcached_translations']);
    }

    /**
     * Return collector name
     *
     * @return string
     */
    public function getName()
    {
        return 'uber_translations_collector';
    }
}
