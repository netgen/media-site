<?php

declare(strict_types=1);

namespace AppBundle;

use AppBundle\DependencyInjection\CompilerPass;
use Netgen\Bundle\SiteBundle\NetgenSiteProjectBundleInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AppBundle extends Bundle implements NetgenSiteProjectBundleInterface
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompilerPass\DisableLegacyContentViewFallbackPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 20);
        $container->addCompilerPass(new CompilerPass\XslRegisterPass());
        $container->addCompilerPass(new CompilerPass\DisableVerticalWhitespacePass());
    }
}
