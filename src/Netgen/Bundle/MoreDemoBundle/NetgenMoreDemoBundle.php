<?php

namespace Netgen\Bundle\MoreDemoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Netgen\Bundle\MoreBundle\NetgenMoreProjectBundleInterface;
use Netgen\Bundle\MoreDemoBundle\DependencyInjection\Compiler\XslRegisterPass;

class NetgenMoreDemoBundle extends Bundle implements NetgenMoreProjectBundleInterface
{
    /**
     * Builds the bundle
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new XslRegisterPass());
    }
}
