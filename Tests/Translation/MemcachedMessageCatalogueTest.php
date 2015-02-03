<?php

namespace Sleepness\UberTranslationBundle\Tests\Translation;

require_once dirname(__DIR__) . '/../../../../app/AppKernel.php';

use Sleepness\UberTranslationBundle\Translation\MemcachedMessageCatalogue;

/**
 * Testing MemcachedMessageCatalogue method and cases
 */
class MemcachedMessageCatalogueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AppKernel
     */
    protected $kernel;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

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
        $values = $this->getMessagesArray();
        $this->uberMemcached->addItem('en_US', $values);
        $preparedTranslations = $this->messageCatalogue->buildByLocale('en_US');

        $this->assertEquals('key.hello', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertEquals('en_US', $preparedTranslations[0]['messages'][0]['locale']);
        $this->uberMemcached->deleteItem('en_US');
    }

    /**
     * Test building catalogue by domain name
     */
    public function testBuildByDomain()
    {
        $values = $this->getMessagesArray();
        $this->uberMemcached->addItem('en_US', $values);
        $preparedTranslations = $this->messageCatalogue->buildByDomain('messages');

        $this->assertEquals('key.hello', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('validators', $preparedTranslations);
        $this->uberMemcached->deleteItem('en_US');
    }

    /**
     * Testing build catalogue by translation key
     */
    public function testBuildByKey()
    {
        $values = $this->getMessagesArray();
        $this->uberMemcached->addItem('en_US', $values);
        $preparedTranslations = $this->messageCatalogue->buildByKey('key.not.blank');

        $this->assertEquals('key.not.blank', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.NotBlank', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('messages', $preparedTranslations);
        $this->uberMemcached->deleteItem('en_US');
    }

    /**
     * Test build catalogue by given text to compare and search
     */
    public function testBuildByText()
    {
        $values = $this->getMessagesArray();
        $this->uberMemcached->addItem('en_US', $values);
        $preparedTranslations = $this->messageCatalogue->buildByText('value.MaxLength');

        $this->assertEquals('key.max.length', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.MaxLength', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('messages', $preparedTranslations);
        $this->uberMemcached->deleteItem('en_US');
    }

    /**
     * Test catalogue to build all messages from memcached
     */
    public function testGetAll()
    {
        $values = $this->getMessagesArray();
        $this->uberMemcached->addItem('en_US', $values);
        $preparedTranslations = $this->messageCatalogue->getAll();

        $this->assertEquals('key.max.length', $preparedTranslations[3]['keyYml']);
        $this->assertEquals('value.MaxLength', $preparedTranslations[3]['messages'][0]['messageText']);
        $this->uberMemcached->deleteItem('en_US');
    }

    /**
     * Boot the Kernel to get the container
     */
    public function setUp()
    {
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();
        $this->container = $this->kernel->getContainer();
        $this->uberMemcached = $this->container->get('uber.memcached');
        $this->messageCatalogue = $this->container->get('memcached.message.catalogue');
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
