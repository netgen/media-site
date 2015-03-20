<?php

namespace Netgen\Bundle\MoreDemoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AsseticBundlesResetPass implements CompilerPassInterface
{
    /**
     * Compiler pass to reset assetic.bundles configuration
     *
     * We don't want to specify each and every Assetic bundle as a potential source
     * for stylesheets and javascripts
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process( ContainerBuilder $container )
    {
        if ( $container->hasParameter( 'kernel.bundles' ) )
        {
            $container->setParameter(
                'assetic.bundles',
                array_keys(
                    $container->getParameter( 'kernel.bundles' )
                )
            );
        }
    }
}
