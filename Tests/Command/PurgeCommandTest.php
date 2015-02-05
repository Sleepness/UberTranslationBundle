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
 * @author Alexandr Zhulev
 */
class PurgeCommandTest extends KernelTestCase
{
    /**
     * Test purge command execution
     */
    public function testExecute()
    {
        $commandTester = $this->bootCommandTestKernel();
        $commandTester->execute(array());
        $this->assertTrue(is_string($commandTester->getDisplay()));
        $this->assertEquals("\033[37;42m All translations deleted from Memcache! \033[0m", trim($commandTester->getDisplay()));
    }

    /**
     * Boot application for testing purge command
     *
     * @return CommandTester
     */
    private function bootCommandTestKernel()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new PurgeCommand());
        $command = $application->find('uber:translations:purge');
        $commandTester = new CommandTester($command);

        return $commandTester;
    }
} 