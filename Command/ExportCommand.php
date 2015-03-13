<?php

namespace Sleepness\UberTranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Dumper;

/**
 * Export all translations from memcache into YAML files of given bundle
 *
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class ExportCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('uber:translations:export')
            ->setDefinition(array(
                new InputArgument('bundle', InputArgument::REQUIRED, 'Name of the bundle'),
            ))
            ->setDescription('Export translations into YAML files')
            ->setHelp("
The <info>uber:translations:export</info> command exports translations from memcache into YAML files of given bundle:

  <info>./app/console uber:translations:export VendorNameYourBundle</info>

Command example:

  <info>./app/console uber:translations:export AcmeDemoBundle</info>

            ");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundleName = $input->getArgument('bundle');
        $bundlePath = $this->getContainer()->get('kernel')->getBundle($bundleName)->getPath();
        $dateTime = new \DateTime();
        $formattedDateTime = $dateTime->format('Y-m-d_H-i');
        $exportResource = $bundlePath . '/Resources/translations/' . $formattedDateTime;
        $uberMemcached = $this->getContainer()->get('uber.memcached');
        $locales = $uberMemcached->getAllKeys();

        if ($locales) {
            $numberOfLocales = null;
            foreach ($locales as $locale) {
                if (preg_match('/^[a-z]{2}$/', $locale) || preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
                    if (!file_exists($exportResource)) {
                        mkdir($exportResource, 0777, true);
                    }
                    $numberOfLocales++;
                    $memcacheMessages = $uberMemcached->getItem($locale);
                    foreach ($memcacheMessages as $domain => $messagesArray) {
                        $yamlArr = array();
                        $indent = 0;
                        foreach ($messagesArray as $key => $value) {
                            $delimArray = explode('.', $key);
                            $indent = count($delimArray);
                            array_push($delimArray, $value);
                            $expArray = $this->expand($delimArray);
                            foreach ($expArray as $expArrayKey => $expArrayVal) {
                                if (array_key_exists($expArrayKey, $yamlArr)) {
                                    $yamlArr[$expArrayKey] = array_replace_recursive($yamlArr[$expArrayKey], $expArrayVal);
                                } else {
                                    $yamlArr[$expArrayKey] = $expArrayVal;
                                }
                            }
                        }
                        $dumper = new Dumper();
                        $yaml = $dumper->dump($yamlArr, $indent);
                        file_put_contents($exportResource . '/' . $domain . '.' . $locale . '.yml', $yaml);
                    }
                }
            }
            if ($numberOfLocales) {
                $output->writeln("\033[37;42m Translations exported successfully in \""
                    . $bundleName . '/Resources/translations/' . $formattedDateTime . "\"! \033[0m");
            } else {
                $output->writeln("\033[37;43m No translations in Memcache! \033[0m");
            }
        } else {
            $output->writeln("\033[37;43m Memcache is empty! \033[0m");
        }
    }

    /**
     * Expand array to multidimensional array
     *
     * @param $array
     * @param int $level
     * @return array
     */
    private function expand($array, $level = 0)
    {
        $result = array();
        $next = $level + 1;
        if (count($array) == $level + 2) {
            $result[$array[$level]] = $array[$next];
        } else {
            $result[$array[$level]] = $this->expand($array, $next);
        }

        return $result;
    }
}
