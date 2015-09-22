<?php

namespace Sleepness\UberTranslationBundle\Tests\Command;

use Sleepness\UberTranslationBundle\Command\DumpCommand;

/**
 * Test cli command for dump memcached translations
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class DumpCommandTest extends CommandTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getCommandInstance()
    {
        return new DumpCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommand()
    {
        return 'uber:translations:dump';
    }

    /**
     * Test purge command execution
     */
    public function testExecute()
    {
        $this->commandTester->execute(array());
        $this->assertTrue(is_string($this->commandTester->getDisplay()));
    }
}
