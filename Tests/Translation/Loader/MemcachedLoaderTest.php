<?php

namespace Sleepness\UberTranslationBundle\Tests\Translation\Loader;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Translation\MessageCatalogue;

class MemcachedLoaderTest extends WebTestCase
{
    /**
     * @var \Sleepness\UberTranslationBundle\Cache\UberMemcached;
     */
    private $uberMemcached;

    /**
     * @var \Sleepness\UberTranslationBundle\Translation\Loader\MemcachedLoader;
     */
    private $loader;

    public function testLoad()
    {
        $catalogue = $this->loader->load($this->uberMemcached, 'en_US');

        $this->assertTrue(is_object($catalogue));
        $this->assertTrue($catalogue instanceof MessageCatalogue);
        $this->assertEquals('en_US', $catalogue->getLocale());
        $this->assertEquals('messages', $catalogue->getDomains()[0]);
    }

    /**
     * Set up fixtures for testing
     */
    public function setUp()
    {
        static::bootKernel(array());
        $container = static::$kernel->getContainer();
        $this->uberMemcached = $container->get('uber.memcached');
        $this->loader = $container->get('uber.translation.loader');
        $values = $this->getMessagesArray();
        $this->uberMemcached->addItem('en_US', $values);
    }

    /**
     * Tear down fixtures after testing
     */
    public function tearDown()
    {
        $this->uberMemcached->deleteItem('en_US');
    }

    /**
     * Get messages for testing
     *
     * @return array - messages
     */
    private function getMessagesArray()
    {
        return array(
            'messages' => array(
                'key.hello' => 'value.Hello',
                'key.foo' => 'value.Foo',
            ),
        );
    }
} 
