<?php

namespace Sleepness\UberTranslationBundle\Tests\Command;

use Sleepness\UberTranslationBundle\Command\PurgeCommand;

/**
 * Test memcached purging command
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class PurgeCommandTest extends CommandTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getCommandInstance()
    {
        return new PurgeCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommand()
    {
        return 'uber:translations:purge';
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
