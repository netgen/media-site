<?php

namespace Deployer;

use Symfony\Component\Console\Input\InputOption;

desc('Upload appropriate .env file to the server');
task('server:upload_env', function() {
    $stage = 'dev';
    if (input()->hasArgument('stage')) {
        $stage = input()->getArgument('stage');
    }

    $path = get('deploy_path');
    $localFile = 'deploy/files/.env.'.$stage;

    if (!file_exists($localFile)) {
        writeln('<info>.env file for selected stage does not exist. Skipping...</info>');

        return;
    }

    if (!test("[ -d ".$path.'/shared/'." ]")) {
        run("mkdir -p ".$path.'/shared/');
    }

    upload($localFile, $path.'/shared/.env');
});

desc('Upload appropriate parameters.yml file to the server');
task('server:upload_parameters', function() {
    $stage = 'dev';
    if (input()->hasArgument('stage')) {
        $stage = input()->getArgument('stage');
    }

    $path = get('deploy_path');
    $localFile = 'deploy/files/parameters.'.$stage.'.yml';

    if (!file_exists($localFile)) {
        writeln('<info>parameters.yml file for selected stage does not exist. Skipping...</info>');

        return;
    }

    if (!test("[ -d ".$path.'/shared/app/config/'." ]")) {
        run("mkdir -p ".$path.'/shared/app/config/');
    }

    upload($localFile, $path.'/shared/app/config/parameters.yml');
});

desc('Symlink web folder to appropriate place');
task('server:symlink_web', function() {
    run("{{bin/symlink}} {{release_path}}/web {{deploy_path}}");
});

set('varnish_ban_hosts', []);
desc('Invalidate content on varnish per host');
option('ban_host', null, InputOption::VALUE_REQUIRED, 'Overrides varnish_ban_hosts configuration and uses provided value');
task('varnish:ban', function() {
    $varnishBanHosts = input()->getOption('ban_host');
    $varnishBanHosts = !empty($varnishBanHosts) ? [$varnishBanHosts] : get('varnish_ban_hosts');

    $useSudo = get('varnish_use_sudo');

    foreach ($varnishBanHosts as $banHost) {
        $command = 'varnishadm "ban req.http.host ~ '.$banHost.'"';

        $command = $useSudo ? 'sudo '.$command : $command;

        run($command);
    }
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
