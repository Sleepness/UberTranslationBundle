<?php

namespace Sleepness\UberTranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\MessageCatalogue;

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
        $catalogues = array(); // prepare array for catalogues
        foreach ($memcachedKeys as $key) { // run through locales
            if (!preg_match('/^[a-z]{2}_[a-zA-Z]{2}$|[a-z]{2}/', $key)) {
                continue;
            }
            $catalogues[] = $uberMemcached->getItem($key);
        }
        $output->writeln("\033[37;43m Translations from memcache: \033[0m \n\n");
        $output->writeln(var_dump($catalogues));
    }
}
