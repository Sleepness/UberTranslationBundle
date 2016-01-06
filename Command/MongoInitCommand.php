<?php

namespace Sleepness\UberTranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initialize environment for storing translations in MongoDB
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class MongoInitCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('uber:translations:mongo:init')
            ->setDefinition(array())
            ->setDescription('Create mongo database for storing translations')
            ->setHelp("
The <info>uber:translations:mongo:init</info> command create new database in Mongo for storing translations:

  <info>./app/console uber:translations:mongo:init</info>

Command example:

  <info>./app/console uber:translations:mongo:init</info>

            ");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $mongoClient = new \MongoClient();
            $database = new \MongoDB($mongoClient, 'uber_translations');
            $database->execute('"foo";'); // perform interaction to persist database

            $output->writeln("\e[37;42m Database 'uber_translations' successfully created \e[0m");
        } catch (\Exception $exception) {
            $output->writeln(sprintf("\e[37;43m %s \e[0m", $exception->getMessage()));
        }
    }
}
