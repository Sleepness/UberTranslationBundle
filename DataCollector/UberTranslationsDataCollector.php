<?php

namespace Sleepness\UberTranslationBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sleepness\UberTranslationBundle\Cache\UberMemcached;

/**
 * Collect translations from Memcache to Symfony profiler
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class UberTranslationsDataCollector extends DataCollector
{
    private $uberMemcached;

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
            if (!preg_match('/^[a-z]{2}_[a-zA-Z]{2}$|[a-z]{2}/', $key)) {
                continue;
            }
            $catalogues[$key] = $this->uberMemcached->getItem($key);
//            $catalogues[$key] = $key;
        }

        $this->data = array(
            'memcached_translations' => $catalogues,
        );
    }

    /**
     */
    public function getTranslations()
    {
        return $this->data['memcached_translations'];
    }

    /**
     */
    public function getAvailableLocales()
    {
        return count($this->data['memcached_translations']);
    }

    /**
     */
    public function getName()
    {
        return 'uber_translations_collector';
    }
}
