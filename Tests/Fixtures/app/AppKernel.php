<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * AppKernel to execute PHPUnit test classes that inherit KernelTestCase
 *
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Sleepness\UberTranslationBundle\SleepnessUberTranslationBundle(),
            new Sleepness\UberTranslationBundle\Tests\Fixtures\TestApp\TestBundle\TestBundle(), // required for testing environment
        );

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yml');
    }
}
