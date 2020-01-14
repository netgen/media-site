<?php

declare(strict_types=1);

namespace AppBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DisableLegacyContentViewFallbackPass implements CompilerPassInterface
{
    private const SiteApiLegacyFallbackContentViewProvider = 'netgen_site_legacy.site_api.content_view_provider';
    private const SiteApiLegacyFallbackLocationViewProvider = 'netgen_site_legacy.site_api.location_view_provider';
    private const EzPlatformLegacyFallbackContentViewProvider = 'ezpublish_legacy.content_view_provider';
    private const EzPlatformLegacyFallbackLocationViewProvider = 'ezpublish_legacy.location_view_provider';
    private const LegacyFallbackApiContentExceptionListener = 'ezpublish_legacy.content_exception_handler';

    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition(self::SiteApiLegacyFallbackContentViewProvider)) {
            $container->removeDefinition(self::SiteApiLegacyFallbackContentViewProvider);
        }

        if ($container->hasDefinition(self::SiteApiLegacyFallbackLocationViewProvider)) {
            $container->removeDefinition(self::SiteApiLegacyFallbackLocationViewProvider);
        }

        if ($container->hasDefinition(self::EzPlatformLegacyFallbackContentViewProvider)) {
            $container->removeDefinition(self::EzPlatformLegacyFallbackContentViewProvider);
        }

        if ($container->hasDefinition(self::EzPlatformLegacyFallbackLocationViewProvider)) {
            $container->removeDefinition(self::EzPlatformLegacyFallbackLocationViewProvider);
        }

        if ($container->hasDefinition(self::LegacyFallbackApiContentExceptionListener)) {
            $container->removeDefinition(self::LegacyFallbackApiContentExceptionListener);
        }
    }
}
