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
            ->setName('uber:import')
            ->setDefinition(array(
                new InputArgument('locales', InputArgument::REQUIRED, 'import locales e.g. en,fr,uk'),
                new InputArgument('bundle', InputArgument::REQUIRED, 'The bundle name'),
            ))
            ->setDescription('Import translations into memcached');
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
            $currentCatalogue = new MessageCatalogue($locale); // Load defined messages for given locale
            if (is_dir($bundle->getPath() . '/Resources/translations')) {
                $loader->loadMessages($bundle->getPath() . '/Resources/translations', $currentCatalogue); // load messages from catalogue
                $catalogues[$locale] = $currentCatalogue; // pass MessageCatalogue instance into $catalogues array
            }
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
    }
}
