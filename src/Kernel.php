<?php

declare(strict_types=1);

namespace App;

use App\DependencyInjection\AppExtension;
use App\DependencyInjection\CompilerPass;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use function preg_match;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait {
        registerContainerConfiguration as public registerKernelContainerConfiguration;
        configureContainer as private configureKernelContainer;
        configureRoutes as private configureKernelRoutes;
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompilerPass\XslRegisterPass());
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $this->registerKernelContainerConfiguration($loader);

        $loader->load(
            static function (ContainerBuilder $container): void {
                $container->registerExtension(new AppExtension());
                $container->loadFromExtension('app');
            },
        );
    }

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $this->configureKernelContainer($container, $loader, $builder);

        $configDir = $this->getConfigDir();

        $container->import($configDir . '/app/{packages}/*.yaml');
        $container->import($configDir . '/app/{services}/*.yaml');
        $container->import($configDir . '/app/services.yaml');

        $serverEnvironment = $_SERVER['SERVER_ENVIRONMENT'];
        if (preg_match('/^\w+$/', $serverEnvironment) !== 1) {
            throw new RuntimeException('Server environment contains an invalid format. Valid format contains only alpha-numeric characters and an underscore.');
        }

        $container->import($configDir . '/app/server/' . $serverEnvironment . '.yaml');
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $this->configureKernelRoutes($routes);

        $configDir = $this->getConfigDir();

        $routes->import($configDir . '/app/{routes}/*.yaml');
        $routes->import($configDir . '/app/routes.yaml');
    }
}
