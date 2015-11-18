<?php

namespace Sleepness\UberTranslationBundle\Cache;

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Wrapper under standard MongoClient
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class UberMongo implements ResourceInterface
{
    public function __toString()
    {
        return 'uberMongo';
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        // TODO: Implement isFresh() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        // TODO: Implement getResource() method.
    }
}
