<?php

namespace Sleepness\UberTranslationBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Sleepness\UberTranslationBundle\Command\PurgeCommand;

/**
 * Test memcached purging command
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class PurgeCommandTest extends KernelTestCase
{
    /**
     * @var \Symfony\Component\Console\Tester\CommandTester;
     */
    private $commandTester;

    /**
     * Test purge command execution
     */
    public function testExecute()
    {
        $this->commandTester->execute(array());
        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertEquals("\033[37;42m All translations deleted from Memcache! \033[0m", trim($this->commandTester->getDisplay()));
    }

    /**
     * Boot application for testing purge memcache command
     */
    public function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new PurgeCommand());
        $command = $application->find('uber:translations:purge');
        $this->commandTester = new CommandTester($command);
    }
}
