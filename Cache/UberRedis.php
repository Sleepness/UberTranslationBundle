<?php

namespace Sleepness\UberTranslationBundle\Cache;

use \Redis;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Wrapper under standard Redis class,
 * which ease work with Redis (using phpredis client - https://github.com/phpredis/phpredis)
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class UberRedis implements ResourceInterface
{
    /**
     * @var \Redis
     */
    private $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @return Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'uberRedis';
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
    }
} 
