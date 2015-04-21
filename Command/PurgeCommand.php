<?php

namespace Sleepness\UberTranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Purge memcached
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class PurgeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('uber:translations:purge')
            ->setDefinition(array())
            ->setDescription('Remove all items from memcached')
            ->setHelp("
The <info>uber:translations:purge</info> command delete all translations from memcached:

Command example:

  <info>./app/console uber:translations:purge</info>

            ");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $memcached = $this->getContainer()->get('uber.memcached'); // get uber memcached
        $keys = $this->getContainer()->getParameter('sleepness_uber_translation.supported_locales');
        foreach ($keys as $index => $locale) {
            if (preg_match('/^[a-z]{2}$/', $locale) || preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
                if ($memcached->deleteItem($locale)) {
                    $output->writeln("\033[37;42m Translations for " . $locale ." locale deleted from Memcache! \033[0m");
                } else {
                    $output->writeln("\033[37;43m Data with " . $locale . " locale has not been deleted from Memcache! \033[0m");
                }
            }
        }
    }
}
