<?php

namespace Sleepness\UberTranslationBundle\Tests\Command;

use Sleepness\UberTranslationBundle\Command\ExportCommand;

/**
 * Test ExportCommand executing cases
 *
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class ExportCommandTest extends CommandTestCase
{
    /**
     * @var \Symfony\Component\Console\Tester\CommandTester;
     */
    protected $commandTester;

    /**
     * @var \Sleepness\UberTranslationBundle\Cache\UberMemcached;
     */
    private $uberMemcached;

    /**
     * @var String;
     */
    protected static $exportResource;

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
        $this->assertFileExists(static::$exportResource . '/messages.en_XX.yml');
        $this->assertRegExp(
            '/key:\n\s+not:\n\s+blank:/',
            file_get_contents(static::$exportResource . '/validators.en_XX.yml')
        );
        $this->assertEquals(
            "\033[37;42m Translations exported successfully into TestBundle/Resources/translations/ ! \033[0m",
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
        parent::setUp();
        $values = $this->getMessagesArray();
        $container = $this->getKernel()->getContainer();
        $this->uberMemcached = $container->get('uber.memcached');
        $this->uberMemcached->addItem('en_XX', $values);
        $bundlePath = $this->getKernel()->getBundle('TestBundle')->getPath();
        static::$exportResource = $bundlePath . '/Resources/translations/';
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
        $this->uberMemcached->deleteItem('en_XX');
    }

    /**
     * Tear down fixtures after test class
     */
    public static function tearDownAfterClass()
    {
        $files = array_diff(scandir(static::$exportResource . '/'), array('.','..'));
        foreach ($files as $file) {
            if (strstr($file, '_XX') !== false) {
                unlink(static::$exportResource . '/' . $file);
            }
        }
    }

    /**
     * Get instance of console command
     *
     * @return mixed
     */
    protected function getCommandInstance()
    {
        return new ExportCommand();
    }

    /**
     * Get command text
     *
     * @return mixed
     */
    protected function getCommand()
    {
       return 'uber:translations:export';
    }
}
