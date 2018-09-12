<?php

declare(strict_types=1);

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

final class AppKernel extends Kernel
{
    /**
     * @var \Symfony\Component\Config\Loader\LoaderInterface
     */
    private $containerLoader;

    public function registerBundles(): array
    {
        $bundles = [
            // Symfony
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            // Dependencies
            new Hautelook\TemplatedUriBundle\HautelookTemplatedUriBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new FOS\HttpCacheBundle\FOSHttpCacheBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
            new Oneup\FlysystemBundle\OneupFlysystemBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            // eZ Systems
            new EzSystems\PlatformHttpCacheBundle\EzSystemsPlatformHttpCacheBundle(),
            new eZ\Bundle\EzPublishCoreBundle\EzPublishCoreBundle(),
            new eZ\Bundle\EzPublishLegacySearchEngineBundle\EzPublishLegacySearchEngineBundle(),
            new eZ\Bundle\EzPublishIOBundle\EzPublishIOBundle(),
            new eZ\Bundle\EzPublishRestBundle\EzPublishRestBundle(),
            new eZ\Bundle\EzPublishLegacyBundle\EzPublishLegacyBundle($this),
            new EzSystems\EzSupportToolsBundle\EzSystemsEzSupportToolsBundle(),
            new EzSystems\PlatformInstallerBundle\EzSystemsPlatformInstallerBundle(),
            new EzSystems\RepositoryFormsBundle\EzSystemsRepositoryFormsBundle(),
            new EzSystems\EzPlatformSolrSearchEngineBundle\EzSystemsEzPlatformSolrSearchEngineBundle(),
            new EzSystems\EzPlatformXmlTextFieldTypeBundle\EzSystemsEzPlatformXmlTextFieldTypeBundle(),
            new EzSystems\EzPlatformDesignEngineBundle\EzPlatformDesignEngineBundle(),
            new EzSystems\EzPlatformAdminUiBundle\EzPlatformAdminUiBundle(),
            new EzSystems\EzPlatformAdminUiModulesBundle\EzPlatformAdminUiModulesBundle(),
            new EzSystems\EzPlatformAdminUiAssetsBundle\EzPlatformAdminUiAssetsBundle(),
            new EzSystems\EzPlatformCronBundle\EzPlatformCronBundle(),

            // Netgen dependencies
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Lolautruche\EzCoreExtraBundle\EzCoreExtraBundle(),
            new Http\HttplugBundle\HttplugBundle(),
        ];

        if ($this->getEnvironment() === 'prod') {
            $bundles[] = new Sentry\SentryBundle\SentryBundle();
        }

        switch ($this->getEnvironment()) {
            case 'test':
            case 'behat':
                $bundles[] = new EzSystems\BehatBundle\EzSystemsBehatBundle();
                $bundles[] = new EzSystems\PlatformBehatBundle\EzPlatformBehatBundle();
            // no break, test also needs dev bundles
            case 'dev':
                $bundles[] = new eZ\Bundle\EzPublishDebugBundle\EzPublishDebugBundle();
                $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
                $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
                $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Netgen\Bundle\BlockManagerDebugBundle\NetgenBlockManagerDebugBundle();
        }

        $bundles[] = new Netgen\Bundle\EzFormsBundle\NetgenEzFormsBundle();
        $bundles[] = new Netgen\Bundle\OpenGraphBundle\NetgenOpenGraphBundle();
        $bundles[] = new Netgen\Bundle\MetadataBundle\NetgenMetadataBundle();
        $bundles[] = new Netgen\Bundle\ContentTypeListBundle\NetgenContentTypeListBundle();
        $bundles[] = new Netgen\Bundle\BirthdayBundle\NetgenBirthdayBundle();
        $bundles[] = new Netgen\TagsBundle\NetgenTagsBundle();
        $bundles[] = new Netgen\Bundle\EnhancedSelectionBundle\NetgenEnhancedSelectionBundle();
        $bundles[] = new Netgen\Bundle\SiteAccessRoutesBundle\NetgenSiteAccessRoutesBundle();
        $bundles[] = new Netgen\Bundle\SiteGeneratorBundle\NetgenSiteGeneratorBundle();
        $bundles[] = new Netgen\Bundle\SiteInstallerBundle\NetgenSiteInstallerBundle();
        $bundles[] = new Netgen\Bundle\SiteBundle\NetgenSiteBundle();
        $bundles[] = new Netgen\Bundle\RichTextDataTypeBundle\NetgenRichTextDataTypeBundle();
        $bundles[] = new Netgen\Bundle\EzPlatformSiteApiBundle\NetgenEzPlatformSiteApiBundle();
        $bundles[] = new Netgen\Bundle\AdminUIBundle\NetgenAdminUIBundle();
        $bundles[] = new Netgen\Bundle\SiteLegacyBundle\NetgenSiteLegacyBundle();
        $bundles[] = new Netgen\Bundle\InformationCollectionBundle\NetgenInformationCollectionBundle();
        $bundles[] = new Netgen\Bundle\EzPlatformSearchExtraBundle\NetgenEzPlatformSearchExtraBundle();

        $bundles[] = new Netgen\Bundle\ContentBrowserBundle\NetgenContentBrowserBundle();
        $bundles[] = new Netgen\Bundle\ContentBrowserEzPlatformBundle\NetgenContentBrowserEzPlatformBundle();
        $bundles[] = new Netgen\Bundle\ContentBrowserUIBundle\NetgenContentBrowserUIBundle();
        $bundles[] = new Netgen\Bundle\BlockManagerBundle\NetgenBlockManagerBundle();
        $bundles[] = new Netgen\Bundle\BlockManagerStandardBundle\NetgenBlockManagerStandardBundle();
        $bundles[] = new Netgen\Bundle\BlockManagerUIBundle\NetgenBlockManagerUIBundle();
        $bundles[] = new Netgen\Bundle\BlockManagerAdminBundle\NetgenBlockManagerAdminBundle();
        $bundles[] = new Netgen\Bundle\EzPublishBlockManagerBundle\NetgenEzPublishBlockManagerBundle();
        $bundles[] = new Netgen\Bundle\SiteAPIBlockManagerBundle\NetgenSiteAPIBlockManagerBundle();

        $bundles[] = new AppBundle\AppBundle();

        return $bundles;
    }

    public function getRootDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        if (!empty($_SERVER['SYMFONY_TMP_DIR'])) {
            return rtrim($_SERVER['SYMFONY_TMP_DIR'], '/') . '/var/cache/' . $this->getEnvironment();
        }

        // On platform.sh place each deployment cache in own folder to rather cleanup old cache async
        if ($this->getEnvironment() === 'prod' && ($platformTreeId = getenv('PLATFORM_TREE_ID'))) {
            return dirname(__DIR__) . '/var/cache/prod/' . $platformTreeId;
        }

        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir(): string
    {
        if (!empty($_SERVER['SYMFONY_TMP_DIR'])) {
            return rtrim($_SERVER['SYMFONY_TMP_DIR'], '/') . '/var/logs';
        }

        return dirname(__DIR__) . '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getRootDir() . '/config/' . $this->getEnvironment() . '/config.yml');

        // We save the loader to a variable in order
        // not to recreate it later in buildContainer
        $this->containerLoader = $loader;
    }

    protected function buildContainer(): ContainerBuilder
    {
        $container = parent::buildContainer();

        $serverEnvironment = $container->getParameter('server_environment');
        $this->containerLoader->load($this->getRootDir() . '/config/server/' . $serverEnvironment . '.yml');

        return $container;
    }
}
