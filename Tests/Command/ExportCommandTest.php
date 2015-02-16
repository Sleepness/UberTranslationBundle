<?php

namespace Sleepness\UberTranslationBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Sleepness\UberTranslationBundle\Command\ExportCommand;

/**
 * Test ExportCommand executing cases
 *
 * @author Alexandr Zhulev
 */
class ExportCommandTest extends KernelTestCase
{
    /**
     * @var \Symfony\Component\Console\Tester\CommandTester;
     */
    private $commandTester;

    /**
     * @var \Sleepness\UberTranslationBundle\Cache\UberMemcached;
     */
    private $uberMemcached;

    /**
     * @var String;
     */
    private static $bundlePath;

    /**
     * Test command success execution
     */
    public function testSuccessExecute()
    {
        $commandTester = $this->commandTester;
        $commandTester->execute(
            array(
                'bundle' => 'TestBundle',
            )
        );
        $this->assertTrue(is_string($commandTester->getDisplay()));
        $this->assertFileExists(static::$bundlePath . '/Resources/translations/messages.en_US.yml');
        $this->assertRegExp(
            '/key:\n\s+not:\n\s+blank:/',
            file_get_contents(static::$bundlePath . '/Resources/translations/validators.en_US.yml')
        );
        $this->assertEquals(
            "\033[37;42m Translations exported successfully in \"TestBundle/Resources/translations\"! \033[0m",
            trim($commandTester->getDisplay())
        );
    }

    /**
     * Test command failure
     *
     * @expectedException InvalidArgumentException
     */
    public function testException()
    {
        $commandTester = $this->commandTester;
        $commandTester->execute(
            array(
                'bundle' => 'NotExistedTestBundle',
            )
        );
        $this->setExpectedException('InvalidArgumentException');
    }

    /**
     * Set up fixtures for testing
     */
    public function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new ExportCommand());
        $command = $application->find('uber:translations:export');
        $this->commandTester = new CommandTester($command);
        $values = $this->getMessagesArray();
        $container = $kernel->getContainer();
        $this->uberMemcached = $container->get('uber.memcached');
        $this->uberMemcached->addItem('en_US', $values);
        static::$bundlePath = $kernel->getBundle('TestBundle')->getPath();
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

    /**
     * Tear down fixtures after testing
     */
    public function tearDown()
    {
        $this->uberMemcached->deleteItem('en_US');
    }

    /**
     * Tear down fixtures after test class
     */
    public static function tearDownAfterClass()
    {
        unlink(static::$bundlePath . '/Resources/translations/messages.en_US.yml');
        unlink(static::$bundlePath . '/Resources/translations/validators.en_US.yml');
    }
}
