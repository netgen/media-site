<?php

declare(strict_types=1);

namespace AppBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;

final class WebpackEncorePass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasAlias(EntrypointLookupInterface::class)) {
            $container->removeAlias(EntrypointLookupInterface::class);
        }
    }
}
