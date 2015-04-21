<?php

namespace Sleepness\UberTranslationBundle\Tests\Command;

use Sleepness\UberTranslationBundle\Command\ImportCommand;

/**
 * Test ImportCommand executing cases
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class ImportCommandTest extends CommandTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getCommandInstance()
    {
        return new ImportCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommand()
    {
        return 'uber:translations:import';
    }

    /**
     * Test command success execution
     */
    public function testSuccessExecute()
    {
        $this->execution(
            array(
                'bundle' => 'TestBundle',
            ),
            "\033[37;42m Translations from TestBundle imported successfully! \033[0m"
        );
    }

    /**
     * Common operations for import command tests
     *
     * @param $arguments - array of arguments for command
     * @param $output - expected output
     */
    private function execution($arguments, $output)
    {
        $this->commandTester->execute($arguments);
        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertEquals($output, trim($this->commandTester->getDisplay()));
    }
}
