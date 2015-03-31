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
     * Return booted kernel
     *
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    protected function getKernel()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        return $kernel;
    }

    /**
     * Boot command before run tests
     */
    protected function setUp()
    {
        $application = new Application($this->getKernel());
        $application->add($this->getCommandInstance());
        $command = $application->find($this->getCommand());
        $this->commandTester = new CommandTester($command);
    }
} 
