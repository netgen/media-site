<?php

namespace Netgen\Bundle\MoreDemoBundle\DependencyInjection\Compiler;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class XslRegisterPass implements CompilerPassInterface
{
    /**
     * Compiler pass to register ezxml_tags.xsl as custom XSL stylesheet for
     * XmlText field type.
     *
     * Avoids having it in %kernel.root_dir%/Resources folder
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $scopes = array_merge(
            array(ConfigResolver::SCOPE_DEFAULT),
            $container->getParameter('ezpublish.siteaccess.list')
        );

        // Adding ezxml_tags.xsl to all scopes
        foreach ($scopes as $scope) {
            $xslConfig = array();
            if ($container->hasParameter("ezsettings.$scope.fieldtypes.ezxml.custom_xsl")) {
                $xslConfig = $container->getParameter("ezsettings.$scope.fieldtypes.ezxml.custom_xsl");
            }

            $xslConfig[] = array('path' => __DIR__ . '/../../Resources/xsl/ezxml_tags.xsl', 'priority' => 10000);
            $container->setParameter("ezsettings.$scope.fieldtypes.ezxml.custom_xsl", $xslConfig);
        }
    }
}
