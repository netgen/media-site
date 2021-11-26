<?php

namespace Deployer;

set('application', 'project_name');

set('repository', 'git@bitbucket.org:netgen/example.git');
set('branch', 'master');

set('repository_name', 'netgen/example');

add('shared_files', ['.env', 'app/config/parameters.yml', 'app/config/remote_media.yml']);
add('shared_dirs', ['ezpublish_legacy/var/site/storage', 'ezpublish_legacy/var/storage']);

add('writable_dirs', ['var/encore', 'ezpublish_legacy/var']);
set('writable_use_sudo', false);

set('keep_releases', 5);

set('http_cache_invalidate_method', 'fos');

set('varnish_use_sudo', false);
set('apache_use_sudo', true);

set('legacy_error_log', 'web/var/log/error.log');
set('symfony_error_log', 'var/logs/prod.log');

set('reindexing_siteaccess', 'ngadminui');

set('sentry_file', 'app/config/sentry.yml');
set('sentry_organization', 'netgen');
set('sentry_token', 'yourtokenhere');
set('sentry_projects', ['netgenlabscom']);
set('sentry_dsn', 'https://yourtoken@sentry.io/here');

set('deploy_log', 'DEPLOY_LOG.md');

//set('slack_webhook', 'https://hooks.slack.com/services/S0M3/3XAMP7E');
