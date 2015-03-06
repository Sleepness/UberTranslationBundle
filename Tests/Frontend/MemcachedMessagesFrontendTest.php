<?php

namespace Sleepness\UberTranslationBundle\Tests\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test MemcachedMessagesFrontend methods and cases
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class MemcachedMessagesFrontendTest extends WebTestCase
{
    /**
     * @var \Sleepness\UberTranslationBundle\Frontend\MemcachedMessagesFrontend;
     */
    private $messagesFrontend;

    /**
     * @var \Sleepness\UberTranslationBundle\Cache\UberMemcached;
     */
    private $uberMemcached;

    /**
     * Test building catalogue by locale
     */
    public function testBuildByLocale()
    {
        $preparedTranslations = $this->messagesFrontend->buildByLocale('en_US');

        $this->assertEquals('key.hello', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTranslations[0]['messageProps']['messageText']);
        $this->assertEquals('en_US', $preparedTranslations[0]['messageProps']['locale']);
    }

    /**
     * Test building catalogue by domain name
     */
    public function testBuildByDomain()
    {
        $preparedTranslations = $this->messagesFrontend->buildByDomain('messages');

        $this->assertEquals('key.hello', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTranslations[0]['messageProps']['messageText']);
        $this->assertArrayNotHasKey('validators', $preparedTranslations);
    }

    /**
     * Test building catalogue by translation key
     */
    public function testBuildByKey()
    {
        $preparedTranslations = $this->messagesFrontend->buildByKey('key.not.blank');

        $this->assertEquals('key.not.blank', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.NotBlank', $preparedTranslations[0]['messageProps']['messageText']);
        $this->assertArrayNotHasKey('messages', $preparedTranslations);
    }

    /**
     * Test building catalogue by given text
     */
    public function testBuildByText()
    {
        $preparedTranslations = $this->messagesFrontend->buildByText('value.MaxLength');

        $this->assertEquals('key.max.length', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.MaxLength', $preparedTranslations[0]['messageProps']['messageText']);
        $this->assertArrayNotHasKey('messages', $preparedTranslations);
    }

    /**
     * Test catalogue to get all messages from memcached
     */
    public function testGetAll()
    {
        $preparedTranslations = $this->messagesFrontend->getAll();

        $this->assertEquals('key.max.length', $preparedTranslations[3]['keyYml']);
        $this->assertEquals('value.MaxLength', $preparedTranslations[3]['messageProps']['messageText']);
    }

    /**
     * Set up fixtures for testing
     */
    public function setUp()
    {
        static::bootKernel(array());
        $container = static::$kernel->getContainer();
        $this->uberMemcached = $container->get('uber.memcached');
        $this->messagesFrontend = $container->get('memcached.messages.frontend');
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
            'validators' => array(
                'key.not.blank' => 'value.NotBlank',
                'key.max.length' => 'value.MaxLength',
            ),
        );
    }
} 
