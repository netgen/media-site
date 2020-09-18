<?php

namespace Deployer;

use Symfony\Component\Console\Input\InputOption;

desc('Upload appropriate .env.local file to the server');
task('server:upload_env', function() {
    $stage = 'dev';
    if (input()->hasArgument('stage')) {
        $stage = input()->getArgument('stage');
    }

    $path = get('deploy_path');
    $localFile = 'deploy/files/.env.local.'.$stage;

    if (!file_exists($localFile)) {
        writeln('<info>.env.local file for selected stage does not exist. Skipping...</info>');

        return;
    }

    if (!test("[ -d ".$path.'/shared/'." ]")) {
        run("mkdir -p ".$path.'/shared/');
    }

    upload($localFile, $path.'/shared/.env.local');
});

desc('Symlink public folder to appropriate place');
task('server:symlink_public', function() {
    run("{{bin/symlink}} {{release_path}}/public {{deploy_path}}");
});

set('varnish_ban_hosts', []);
function invalidateVarnish($varnishBanHosts = null)
{
    $varnishBanHosts = !empty($varnishBanHosts) ? [$varnishBanHosts] : get('varnish_ban_hosts');

    $useSudo = get('varnish_use_sudo');
    foreach ($varnishBanHosts as $banHost) {
        $command = 'varnishadm "ban req.http.host ~ '.$banHost.'"';
        $command = $useSudo ? 'sudo '.$command : $command;

        run($command);
    }
}

set('http_invalidate_tag', 'ez-all');
function invalidateFOSHttpCache($tag = null)
{
    $tag = !empty($tag) ? $tag : get('http_invalidate_tag');

    run("{{bin/php}} {{bin/console}} fos:httpcache:invalidate:tag {$tag} {{console_options}}");
}

desc('Invalidate content on varnish per host');
option('ban_host', null, InputOption::VALUE_REQUIRED, 'Overrides varnish_ban_hosts configuration and uses provided value');
task('varnish:ban', function() {
    $varnishBanHosts = input()->getOption('ban_host');

    invalidateVarnish($varnishBanHosts);
});

desc('Invalidate content via FOSHttpCache by tag');
option('invalidate_tag', null, InputOption::VALUE_REQUIRED, 'Overrides default tag which to invalidate on http cache and uses provided value');
task('httpcache:invalidate:tag', function() {
    $invalidateTag = input()->getOption('invalidate_tag');

    invalidateFOSHttpCache($invalidateTag);
});

set('http_cache_invalidate_method', 'fos');
desc('Invalidate http cache');
task('httpcache:invalidate', function() {
    $method = get('http_cache_invalidate_method');

    if ($method === 'fos') {
        invalidateFOSHttpCache();
    } else if ($method === 'varnish') {
        invalidateVarnish();
    }
});


set('apache_use_sudo', true);
desc('Reload Apache');
task('server:reload_apache', function() {
    $command = 'service apache2 reload';
    $command = get('apache_use_sudo') ? 'sudo '.$command : $command;

    run($command);
});

desc('List crontab configuration');
task('crontab:list', function () {
    $result = run('crontab -l');

    write($result);
});

desc('Reindex solr');
task('solr:reindex', function () {
    $siteaccess = get('reindexing_siteaccess');
    $result = run("{{bin/php}} {{bin/console}} ezplatform:reindex --processes=1 --siteaccess={$siteaccess} {{console_options}}");

    writeln($result);
});
