<?php

namespace Sleepness\UberTranslationBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Base command test case class for store base logic needed to run tests for command line tools
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
abstract class CommandTestCase extends KernelTestCase
{
    /**
     * @var \Symfony\Component\Console\Tester\CommandTester
     */
    protected $commandTester;

    /**
     * Get instance of console command
     *
     * @return mixed
     */
    abstract protected function getCommandInstance();

    /**
     * Get command text
     *
     * @return mixed
     */
    abstract protected function getCommand();

    /**
     * Boot command before run tests
     */
    public function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add($this->getCommandInstance());
        $command = $application->find($this->getCommand());
        $this->commandTester = new CommandTester($command);
    }
} 
