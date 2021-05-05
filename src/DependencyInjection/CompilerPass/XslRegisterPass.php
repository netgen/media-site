<?php

declare(strict_types=1);

namespace App\DependencyInjection\CompilerPass;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function array_merge;

final class XslRegisterPass implements CompilerPassInterface
{
    /**
     * Registers various Docbook XSL files as custom XSL stylesheets for ezrichtext field type.
     */
    public function process(ContainerBuilder $container): void
    {
        $scopes = array_merge(
            [ConfigResolver::SCOPE_DEFAULT],
            $container->getParameter('ezpublish.siteaccess.list'),
        );

        foreach ($scopes as $scope) {
            if ($container->hasParameter("ezsettings.{$scope}.fieldtypes.ezrichtext.output_custom_xsl")) {
                $xslConfig = $container->getParameter("ezsettings.{$scope}.fieldtypes.ezrichtext.output_custom_xsl");
                $xslConfig[] = ['path' => __DIR__ . '/../../../assets/docbook/output/core.xsl', 'priority' => 10000];
                $container->setParameter("ezsettings.{$scope}.fieldtypes.ezrichtext.output_custom_xsl", $xslConfig);
            }

            if ($container->hasParameter("ezsettings.{$scope}.fieldtypes.ezrichtext.edit_custom_xsl")) {
                $xslConfig = $container->getParameter("ezsettings.{$scope}.fieldtypes.ezrichtext.edit_custom_xsl");
                $xslConfig[] = ['path' => __DIR__ . '/../../../assets/docbook/edit/core.xsl', 'priority' => 10000];
                $container->setParameter("ezsettings.{$scope}.fieldtypes.ezrichtext.edit_custom_xsl", $xslConfig);
            }
        }
    }
}
