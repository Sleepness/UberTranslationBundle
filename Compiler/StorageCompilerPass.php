<?php

namespace Sleepness\UberTranslationBundle\Storage;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compile container with proper storage service (according to bundle configuration)
 */
class StorageCompilerPass extends CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // @TODO implement method
    }
}
