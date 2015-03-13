<?php

namespace Sleepness\UberTranslationBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Sleepness\UberTranslationBundle\Command\ImportCommand;

/**
 * Test ImportCommand executing cases
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev
 */
class ImportCommandTest extends KernelTestCase
{
    /**
     * Test command success execution
     */
    public function testSuccessExecute()
    {
        $commandTester = $this->bootCommandTestKernel();
        $commandTester->execute(
            array(
                'locales' => 'en,uk',
                'bundle' => 'TestBundle',
            )
        );
        $this->assertTrue(is_string($commandTester->getDisplay()));
        $this->assertEquals("\033[37;42m Translations from TestBundle imported successfully! \033[0m", trim($commandTester->getDisplay()));
    }

    /**
     * Test failure of the command
     */
    public function testFailureExecute()
    {
        $commandTester = $this->bootCommandTestKernel();
        $commandTester->execute(
            array(
                'locales' => 'eqwewqeqwe12341241',
                'bundle' => 'TestBundle',
            )
        );
        $this->assertTrue(is_string($commandTester->getDisplay()));
        $this->assertEquals("\033[37;43m Make sure you define all locales properly \033[0m", trim($commandTester->getDisplay()));
    }

    /**
     * Boot application for testing import command
     *
     * @return CommandTester
     */
    private function bootCommandTestKernel()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new ImportCommand());
        $command = $application->find('uber:translations:import');
        $commandTester = new CommandTester($command);

        return $commandTester;
    }
}
