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
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class ImportCommandTest extends KernelTestCase
{
    /**
     * @var \Symfony\Component\Console\Tester\CommandTester;
     */
    private $commandTester;

    /**
     * Test command success execution
     */
    public function testSuccessExecute()
    {
        $this->commandTester->execute(
            array(
                'locales' => 'en,uk',
                'bundle' => 'TestBundle',
            )
        );
        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertEquals("\033[37;42m Translations from TestBundle imported successfully! \033[0m", trim($this->commandTester->getDisplay()));
    }

    /**
     * Test failure of the command
     */
    public function testFailureExecute()
    {
        $this->commandTester->execute(
            array(
                'locales' => 'eqwewqeqwe12341241',
                'bundle' => 'TestBundle',
            )
        );
        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertEquals("\033[37;43m Make sure you define all locales properly \033[0m", trim($this->commandTester->getDisplay()));
    }

    /**
     * Boot application for testing import command
     */
    public function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new ImportCommand());
        $command = $application->find('uber:translations:import');
        $this->commandTester = new CommandTester($command);
    }
}
