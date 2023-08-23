<?php

namespace Deployer;

require 'recipe/symfony.php';
require 'contrib/rsync.php';

require __DIR__ . '/deploy/hosts.php';
require __DIR__ . '/deploy/tasks/server.php';
require __DIR__ . '/deploy/tasks/database.php';
require __DIR__ . '/deploy/tasks/rsync.php';
require __DIR__ . '/deploy/tasks/deploy_log.php';
require __DIR__ . '/deploy/tasks/sentry.php';
require __DIR__ . '/deploy/tasks/logs.php';
require __DIR__ . '/deploy/tasks/overrides.php';
require __DIR__ . '/deploy/tasks/assets.php';
require __DIR__ . '/deploy/tasks/graphql.php';
require __DIR__ . '/deploy/tasks/git.php';
require __DIR__ . '/deploy/tasks/app.php';
require __DIR__ . '/deploy/parameters.php';
// optional: slack integration
//require __DIR__ . '/deploy/tasks/slack.php';

putenv("DEPLOYER_ROOT=. vendor/bin/dep taskname`");

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

set('writable_recursive', true);

set('update_code_strategy', 'clone');

/** Execution */
task('deploy', [
    'deploy:confirm',
    // Upload server specific .env.local file. Those files are NOT to be committed to the repository
    'server:upload_env',
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:shared',
    // build and upload assets
    'app:assets:deploy',
    'app:graphql:deploy',
    // copy vendors folder between releases before running composer install to speed it up
    'deploy:copy_dirs',
    'deploy:vendors',
    //'deploy:assets:install',
    'deploy:sentry:symfony',
    'deploy:sentry',
    'git:tag:add',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:writable',
    // Migrate database before symlink new release.
    //  'database:migrate',
    // Optional: if the project uses kaliop migrations
    'database:kaliop:migrate',
    'deploy:symlink',
    // Netgen specific setup, comment out what's not needed
    'server:symlink_public',
    'cachetool:clear:opcache',
    // Cleanup and finish the deploy
    'deploy:unlock',
    'deploy:cleanup',
])->desc('Deploy your project');

// after successful deploy
after('deploy', 'httpcache:invalidate');
after('deploy', 'deploy:log:remote');
after('deploy', 'deploy:success');

// If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('rollback', 'cachetool:clear:opcache');
after('rollback', 'httpcache:invalidate');
after('rollback', 'deploy:log:rollback:remote');
