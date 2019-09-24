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

    public function registerBundles(): iterable
    {
        $bundles = [
            // Symfony
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\WebServerBundle\WebServerBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\WebpackEncoreBundle\WebpackEncoreBundle(),
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
            new EzSystems\DoctrineSchemaBundle\DoctrineSchemaBundle(),
            new EzSystems\RepositoryFormsBundle\EzSystemsRepositoryFormsBundle(),
            new EzSystems\EzPlatformSolrSearchEngineBundle\EzSystemsEzPlatformSolrSearchEngineBundle(),
            new EzSystems\EzPlatformXmlTextFieldTypeBundle\EzSystemsEzPlatformXmlTextFieldTypeBundle(),
            new EzSystems\EzPlatformDesignEngineBundle\EzPlatformDesignEngineBundle(),
            new EzSystems\EzPlatformRichTextBundle\EzPlatformRichTextBundle(),
            new EzSystems\EzPlatformAdminUiBundle\EzPlatformAdminUiBundle(),
            new EzSystems\EzPlatformUserBundle\EzPlatformUserBundle(),
            new EzSystems\EzPlatformAdminUiModulesBundle\EzPlatformAdminUiModulesBundle(),
            new EzSystems\EzPlatformAdminUiAssetsBundle\EzPlatformAdminUiAssetsBundle(),
            new EzSystems\EzPlatformCronBundle\EzPlatformCronBundle(),
            new EzSystems\EzPlatformEncoreBundle\EzSystemsEzPlatformEncoreBundle(),
            new EzSystems\EzPlatformGraphQL\EzSystemsEzPlatformGraphQLBundle(),
            // Matrix field type bundle disabled for now since it is incompatible with eZ Publish Legacy data type
            // new EzSystems\EzPlatformMatrixFieldtypeBundle\EzPlatformMatrixFieldtypeBundle(),
            // OverblogGraphQLBundle has to be loaded after EzSystemsEzPlatformGraphQLBundle
            new Overblog\GraphQLBundle\OverblogGraphQLBundle(),

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
                $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Overblog\GraphiQLBundle\OverblogGraphiQLBundle();
                $bundles[] = new Snc\RedisBundle\SncRedisBundle();
                $bundles[] = new Netgen\Bundle\LayoutsDebugBundle\NetgenLayoutsDebugBundle();
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
        $bundles[] = new Netgen\Bundle\LayoutsBundle\NetgenLayoutsBundle();
        $bundles[] = new Netgen\Bundle\LayoutsStandardBundle\NetgenLayoutsStandardBundle();
        $bundles[] = new Netgen\Bundle\LayoutsUIBundle\NetgenLayoutsUIBundle();
        $bundles[] = new Netgen\Bundle\LayoutsAdminBundle\NetgenLayoutsAdminBundle();
        $bundles[] = new Netgen\Bundle\LayoutsEzPlatformRelationListQueryBundle\NetgenLayoutsEzPlatformRelationListQueryBundle();
        $bundles[] = new Netgen\Bundle\LayoutsEzPlatformTagsQueryBundle\NetgenLayoutsEzPlatformTagsQueryBundle();
        $bundles[] = new Netgen\Bundle\LayoutsEzPlatformBundle\NetgenLayoutsEzPlatformBundle();
        $bundles[] = new Netgen\Bundle\LayoutsEzPlatformSiteApiBundle\NetgenLayoutsEzPlatformSiteApiBundle();

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

        if ($this->getEnvironment() === 'dev' && $container->getParameter('profiler_storage') === 'redis') {
            $this->containerLoader->load($this->getRootDir() . '/config/dev/profiler_storage/redis.yml');
        }

        return $container;
    }
}
