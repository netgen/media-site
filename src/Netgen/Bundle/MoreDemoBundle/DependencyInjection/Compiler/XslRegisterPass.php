<?php

namespace Netgen\Bundle\MoreDemoBundle\DependencyInjection\Compiler;

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
        if (!$container->hasParameter('ezsettings.default.fieldtypes.ezxml.custom_xsl') ||
            !$container->hasParameter('ezpublish.siteaccess.list')
        ) {
            return;
        }

        // Adding ezxml_tags.xsl to all declared siteaccesses.
        foreach ($container->getParameter('ezpublish.siteaccess.list') as $siteAccess) {
            if (!$container->hasParameter("ezsettings.$siteAccess.fieldtypes.ezxml.custom_xsl")) {
                continue;
            }

            $xslConfig = $container->getParameter("ezsettings.$siteAccess.fieldtypes.ezxml.custom_xsl");
            $xslConfig[] = array('path' => __DIR__ . '/../../Resources/xsl/ezxml_tags.xsl', 'priority' => 10000);
            $container->setParameter("ezsettings.$siteAccess.fieldtypes.ezxml.custom_xsl", $xslConfig);
        }
    }
}
