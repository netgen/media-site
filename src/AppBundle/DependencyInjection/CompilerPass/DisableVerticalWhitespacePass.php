<?php

declare(strict_types=1);

namespace AppBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DisableVerticalWhitespacePass implements CompilerPassInterface
{
    /**
     * Unregister deprecated vertical whitespace plugin
     */
    public function process(ContainerBuilder $container): void
    {
        $container->removeDefinition('ngsite.layouts.block.plugin.vertical_whitespace');
    }
}
