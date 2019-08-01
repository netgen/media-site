<?php

namespace Deployer;

require 'recipe/symfony3.php';
require 'vendor/deployer/recipes/recipe/cachetool.php';
require 'vendor/deployer/recipes/recipe/rsync.php';
require 'vendor/deployer/recipes/recipe/sentry.php';
require 'vendor/deployer/recipes/recipe/slack.php';

require __DIR__ . '/deploy/parameters.php';
require __DIR__ . '/deploy/hosts.php';
require __DIR__ . '/deploy/tasks/server.php';
require __DIR__ . '/deploy/tasks/database.php';
require __DIR__ . '/deploy/tasks/rsync.php';
require __DIR__ . '/deploy/tasks/deploy_log.php';
require __DIR__ . '/deploy/tasks/sentry.php';
require __DIR__ . '/deploy/tasks/logs.php';
require __DIR__ . '/deploy/tasks/overrides.php';
// optional: slack integration
//require __DIR__ . '/deploy/tasks/slack.php';

/** Parameters */
set('git_tty', true);

add('copy_dirs', ['vendor']);

set('use_relative_symlink', false);

set('sentry', [
    'organization' => get('sentry_organization'),
    'token' => get('sentry_token'),
    'projects' => get('sentry_projects'),
    'version' => getReleaseVersion(),
    'commits' => getCommitsInformation()
]);

/** Execution */
task('deploy', [
    // Upload server specific parameters.yml file. Those file are NOT to be committed to the repository
    'server:upload_parameters',
    // Upload server specific .env file. Those file are NOT to be committed to the repository
    'server:upload_env',
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:assets',
    // copy vendors folder between releases before running composer install to speed it up
    'deploy:copy_dirs',
    'deploy:vendors',
    'deploy:assets:install',
    'deploy:sentry:symfony',
    'deploy:sentry',
    'deploy:sentry:capture',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:writable',
    // Migrate database before symlink new release.
    'database:migrate',
    // Optional: if the project uses kaliop migrations
    //database:kaliop:migrate
    'deploy:symlink',
    // Netgen specific setup
    'server:symlink_web',
    'cachetool:clear:opcache',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');

// after successful deploy
after('deploy', 'varnish:ban');
after('deploy', 'deploy:log:remote');

// If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('rollback', 'cachetool:clear:opcache');
after('rollback', 'varnish:ban');
after('rollback', 'deploy:log:rollback:remote');
