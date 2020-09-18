<?php

namespace Deployer;

set('application', 'project_name');

set('repository', 'git@bitbucket.org:netgen/example.git');
set('branch', 'master');

set('repository_name', 'netgen/example');

add('shared_files', ['.env.local']);
add('shared_dirs', ['public/var/site/storage']);

add('writable_dirs', ['var/encore']);
set('writable_use_sudo', false);

set('keep_releases', 5);

set('http_cache_invalidate_method', 'fos');

set('varnish_use_sudo', false);
set('apache_use_sudo', true);

set('symfony_error_log', 'var/log/prod.log');

set('reindexing_siteaccess', 'admin');

set('sentry_file', 'config/packages/sentry.yaml');
set('sentry_organization', 'netgen');
set('sentry_token', 'yourtokenhere');
set('sentry_projects', ['netgenlabscom']);
set('sentry_dsn', 'https://yourtoken@sentry.io/here');

set('deploy_log', 'DEPLOY_LOG.md');

//set('slack_webhook', 'https://hooks.slack.com/services/S0M3/3XAMP7E');
