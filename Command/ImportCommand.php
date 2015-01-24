<?php

namespace Sleepness\UberTranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * Upload all translations for specified locales from given bundle to memcache
 */
class ImportCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('uber:translations:import')
            ->setDefinition(array(
                new InputArgument('locales', InputArgument::REQUIRED, 'Locales of translations (e.g. en,fr,uk)'),
                new InputArgument('bundle', InputArgument::REQUIRED, 'Name of the bundle'),
            ))
            ->setDescription('Import translations into memcached')
            ->setHelp("
The <info>uber:translations:import</info> command import translations of your bundle into memcache:

  <info>./app/console uber:translations:import locales VendorNameYourBundle</info>

For example text in command line may look like

  <info>./app/console uber:translations:import en,uk AcmeDemoBundle</info>

            ");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $locales = $input->getArgument('locales');
        $parsedLocales = explode(',', $locales); // prepare locales from parameters
        $bundle = $this->getContainer()->get('kernel')->getBundle($input->getArgument('bundle')); // get bundle props
        $loader = $this->getContainer()->get('translation.loader'); // get translator loader
        $uberMemcached = $this->getContainer()->get('uber.memcached'); // get uber memcached
        $catalogues = array(); // prepare array for catalogues
        foreach ($parsedLocales as $key => $locale) { // run through locales
            if (!preg_match('/^[a-z]{2}$/', $locale)) {
                $output->writeln("\033[37;41m Make sure you define all locales properly \033[0m   \n");
                return;
            }
            $currentCatalogue = new MessageCatalogue($locale); // Load defined messages for given locale
            if (!is_dir($bundle->getPath() . '/Resources/translations')) {
                $output->writeln("\033[37;41m There is not folder with translations in " . $input->getArgument('bundle') . " \033[0m   \n");
                return;
            }
            $loader->loadMessages($bundle->getPath() . '/Resources/translations', $currentCatalogue); // load messages from catalogue
            $catalogues[$locale] = $currentCatalogue; // pass MessageCatalogue instance into $catalogues array
        }
        foreach ($catalogues as $locale => $catalogue) { // run over all catalogues
            $catalogueMessages = $catalogue->all(); // get messages from current catalogue
            $memcacheMessages = $uberMemcached->getItem($locale); // get existing messages from the memcache by locale
            if ($memcacheMessages == false) { // if for now no messages in memcache
                $uberMemcached->addItem($locale, $catalogueMessages); // just upload them
            } else {
                foreach ($memcacheMessages as $domain => $messagesArray) { // run over messages in cache
                    foreach ($catalogueMessages as $d => $catMess) { // run over all in catalogue
                        if ($domain == $d) { // if domains equals
                            foreach ($messagesArray as $messKey => $trans) { //run over messages
                                foreach ($catMess as $ymlKey => $transl) {
                                    if ($messKey == $ymlKey) { // if keys equals
                                        unset($catalogueMessages[$d][$messKey]); // unset the value because we don`t want to repeated values
                                    }
                                }
                            }
                        }
                    }
                }
                $mergedMessages = array_merge_recursive($memcacheMessages, $catalogueMessages); // merge recursive message arrays
                $uberMemcached->addItem($locale, $mergedMessages); // set merged messages to memcache
            }
        }
        $output->writeln("\033[37;42m Translations for " . $input->getArgument('bundle') . " successful! \033[0m");
    }
}
