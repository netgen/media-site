<?php

declare(strict_types=1);

namespace AppBundle;

use AppBundle\DependencyInjection\CompilerPass\WebpackEncorePass;
use Netgen\Bundle\SiteBundle\NetgenSiteProjectBundleInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AppBundle extends Bundle implements NetgenSiteProjectBundleInterface
{
    public function build(ContainerBuilder $container): void
    {
        // Temporary fix until https://github.com/symfony/webpack-encore-bundle/pull/59 is merged
        $container->addCompilerPass(new WebpackEncorePass(), PassConfig::TYPE_BEFORE_REMOVING);
    }
}
