<?php

declare(strict_types=1);

namespace AppBundle\DependencyInjection\CompilerPass;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class XslRegisterPass implements CompilerPassInterface
{
    /**
     * Registers ezxml_tags.xsl as custom XSL stylesheet for ezxmltext field type.
     */
    public function process(ContainerBuilder $container): void
    {
        $scopes = array_merge(
            [ConfigResolver::SCOPE_DEFAULT],
            $container->getParameter('ezpublish.siteaccess.list')
        );

        // Adding ezxml_tags.xsl to all scopes
        foreach ($scopes as $scope) {
            if (!$container->hasParameter("ezsettings.{$scope}.fieldtypes.ezxml.custom_xsl")) {
                continue;
            }

            $xslConfig = $container->getParameter("ezsettings.{$scope}.fieldtypes.ezxml.custom_xsl");
            $xslConfig[] = ['path' => __DIR__ . '/../../Resources/xsl/ezxml_tags.xsl', 'priority' => 10000];
            $container->setParameter("ezsettings.{$scope}.fieldtypes.ezxml.custom_xsl", $xslConfig);
        }
    }
}
