<?php

namespace Sleepness\UberTranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Dump all translations stored in memcache
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class DumpCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('uber:translations:dump')
            ->setDefinition(array())
            ->setDescription('Dump translations what stores in memcached')
            ->setHelp("
The <info>uber:translations:dump</info> command dump all translations from memcahced and output to console:

  <info>./app/console uber:translations:dumpe</info>

Command example:

  <info>./app/console uber:translations:dump</info>

            ");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uberMemcached = $this->getContainer()->get('uber.memcached'); // get uber memcached
        $memcachedKeys = $uberMemcached->getAllKeys();
        $output->writeln("\033[37;43m Translations from memcache: \033[0m");
        foreach ($memcachedKeys as $key) { // run through locales
            $output->writeln("\033[94mLocale: $key \033[0m");
            $translations = $uberMemcached->getItem($key);
            foreach ($translations as $domain => $messages) {
                $output->writeln("-------------------------");
                $output->writeln("\033[96mDomain: $domain \033[0m");
                $output->writeln("-------------------------");
                $output->writeln("\033[92m| Key | Value | \033[0m");
                foreach ($messages as $key => $value) {
                    $output->writeln("| $key | $value |");
                }
            }
        }
    }
}
