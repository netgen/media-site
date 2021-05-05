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
use function dirname;
use function is_file;
use function preg_match;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait {
        registerContainerConfiguration as public registerKernelContainerConfiguration;
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

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        } elseif (is_file($path = dirname(__DIR__) . '/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }

        $container->import('../config/app/{packages}/*.yaml');
        $container->import('../config/app/{services}/*.yaml');
        $container->import('../config/app/services.yaml');

        $serverEnvironment = $_SERVER['SERVER_ENVIRONMENT'];
        if (preg_match('/^\w+$/', $serverEnvironment) !== 1) {
            throw new RuntimeException('Server environment contains an invalid format. Valid format contains only alpha-numeric characters and an underscore.');
        }

        $container->import('../config/app/server/' . $serverEnvironment . '.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (is_file($path = dirname(__DIR__) . '/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }

        $routes->import('../config/app/{routes}/*.yaml');
        $routes->import('../config/app/routes.yaml');
    }
}
