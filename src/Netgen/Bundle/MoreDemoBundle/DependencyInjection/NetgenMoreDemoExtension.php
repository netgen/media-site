<?php

namespace Netgen\Bundle\MoreDemoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use DirectoryIterator;

class NetgenMoreDemoExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('templates.yml');
        $loader->load('services.yml');
        $loader->load('legacy.yml');

        foreach (new DirectoryIterator(__DIR__ . '/../Resources/config/legacy') as $legacyConfig) {
            if ($legacyConfig->isDir() || $legacyConfig->isDot()) {
                continue;
            }

            $loader->load('legacy/' . $legacyConfig->getBasename());
        }
    }
}
