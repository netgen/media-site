<?php

declare(strict_types=1);

namespace App;

use App\DependencyInjection\AppExtension;
use App\DependencyInjection\CompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompilerPass\DisableLegacyContentViewFallbackPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 20);
        $container->addCompilerPass(new CompilerPass\XslRegisterPass());
        $container->addCompilerPass(new CompilerPass\DisableVerticalWhitespacePass());
    }

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir() . '/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir() . '/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir() . '/config';

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/overrides/**/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');

        $loader->load($confDir . '/app/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/app/{services}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/app/{services}' . self::CONFIG_EXTS, 'glob');

        $serverEnvironment = $container->getParameter('server_environment');
        $loader->load($confDir . '/app/server/' . $serverEnvironment . self::CONFIG_EXTS, 'glob');

        if ($this->environment === 'dev' && $container->getParameter('profiler_storage') === 'redis') {
            $loader->load($confDir . '/packages/profiler_storage/redis' . self::CONFIG_EXTS, 'glob');
        }

        $container->registerExtension(new AppExtension());
        $container->loadFromExtension('app');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS, '/', 'glob');

        $routes->import($confDir . '/app/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/app/{routes}' . self::CONFIG_EXTS, '/', 'glob');
    }
}
