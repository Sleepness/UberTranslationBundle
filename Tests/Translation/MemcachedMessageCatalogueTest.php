<?php

namespace Sleepness\UberTranslationBundle\Tests\Translation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test MemcachedMessageCatalogue methods and cases
 */
class MemcachedMessageCatalogueTest extends WebTestCase
{
    /**
     * @var \Sleepness\UberTranslationBundle\Translation\MemcachedMessageCatalogue;
     */
    protected $messageCatalogue;

    /**
     * @var \Sleepness\UberTranslationBundle\Cache\UberMemcached;
     */
    protected $uberMemcached;

    /**
     * Test building catalogue by locale
     */
    public function testBuildByLocale()
    {
        $preparedTranslations = $this->messageCatalogue->buildByLocale('en_US');

        $this->assertEquals('key.hello', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertEquals('en_US', $preparedTranslations[0]['messages'][0]['locale']);
    }

    /**
     * Test building catalogue by domain name
     */
    public function testBuildByDomain()
    {
        $preparedTranslations = $this->messageCatalogue->buildByDomain('messages');

        $this->assertEquals('key.hello', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('validators', $preparedTranslations);
    }

    /**
     * Test building catalogue by translation key
     */
    public function testBuildByKey()
    {
        $preparedTranslations = $this->messageCatalogue->buildByKey('key.not.blank');

        $this->assertEquals('key.not.blank', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.NotBlank', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('messages', $preparedTranslations);
    }

    /**
     * Test building catalogue by given text
     */
    public function testBuildByText()
    {
        $preparedTranslations = $this->messageCatalogue->buildByText('value.MaxLength');

        $this->assertEquals('key.max.length', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.MaxLength', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('messages', $preparedTranslations);
    }

    /**
     * Test catalogue to get all messages from memcached
     */
    public function testGetAll()
    {
        $preparedTranslations = $this->messageCatalogue->getAll();

        $this->assertEquals('key.max.length', $preparedTranslations[3]['keyYml']);
        $this->assertEquals('value.MaxLength', $preparedTranslations[3]['messages'][0]['messageText']);
    }

    /**
     * Set up fixtures for testing
     */
    public function setUp()
    {
        static::bootKernel(array());
        $container = static::$kernel->getContainer();
        $this->uberMemcached = $container->get('uber.memcached');
        $this->messageCatalogue = $container->get('memcached.message.catalogue');
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
    public function getMessagesArray()
    {
        return array(
            'messages' => array(
                'key.hello' => 'value.Hello',
                'key.foo' => 'value.Foo',
            ),
            'validators' => array(
                'key.not.blank' => 'value.NotBlank',
                'key.max.length' => 'value.MaxLength',
            ),
        );
    }
}
