<?php

namespace Sleepness\UberTranslationBundle\Tests\Translation;

require_once dirname(__DIR__) . '/../../../../app/AppKernel.php';

use Sleepness\UberTranslationBundle\Translation\MemcachedMessageCatalogue;

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


    public function testBuildByLocale()
    {
        $messageCatalogue = $this->messageCatalogue;
        $preparedTranslations = $messageCatalogue->buildByLocale('en');

        $this->assertEquals('key.hello', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertEquals('en', $preparedTranslations[0]['messages'][0]['locale']);
    }

    public function testBuildByDomain()
    {
        $messageCatalogue = $this->messageCatalogue;
        $preparedTranslations = $messageCatalogue->buildByDomain('messages');

        $this->assertEquals('key.hello', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('validators', $preparedTranslations);
    }

    public function testBuildByKey()
    {
        $messageCatalogue = $this->messageCatalogue;
        $preparedTranslations = $messageCatalogue->buildByKey('key.not.blank');

        $this->assertEquals('key.not.blank', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.NotBlank', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('messages', $preparedTranslations);
    }

    public function testBuildByText()
    {
        $messageCatalogue = $this->messageCatalogue;
        $preparedTranslations = $messageCatalogue->buildByText('value.MaxLength');

        $this->assertEquals('key.max.length', $preparedTranslations[0]['keyYml']);
        $this->assertEquals('value.MaxLength', $preparedTranslations[0]['messages'][0]['messageText']);
        $this->assertArrayNotHasKey('messages', $preparedTranslations);
    }

    public function testGetAll()
    {
        $messageCatalogue = $this->messageCatalogue;
        $preparedTranslations = $messageCatalogue->getAll();

        $this->assertEquals('key.max.length', $preparedTranslations[3]['keyYml']);
        $this->assertEquals('value.MaxLength', $preparedTranslations[3]['messages'][0]['messageText']);
    }

    public function setUp()
    {
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();
        $this->container = $this->kernel->getContainer();

        $this->uberMemcached = $this->container->get('uber.memcached');
        $values = array(
            'messages' => array(
                'key.hello' => 'value.Hello',
                'key.foo'   => 'value.Foo',
            ),
            'validators' => array(
                'key.not.blank'  => 'value.NotBlank',
                'key.max.length' => 'value.MaxLength',
            ),
        );
        $this->uberMemcached->addItem('en', $values);
        $this->messageCatalogue = $this->container->get('memcached.message.catalogue');
    }
}
