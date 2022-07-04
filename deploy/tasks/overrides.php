<?php

namespace Deployer;

use Closure;
use DateTime;
use Deployer\Utility\Httpie;

set('bin/cachetool', function(){
    return 'cachetool-7.0.0.phar';
});

// overridden to make sure public folder is correctly symlinked (L79)
desc('Rollback to previous release');
task('rollback', function () {
    $releases = get('releases_list');
    if (isset($releases[1])) {
        $releaseDir = "{{deploy_path}}/releases/{$releases[1]}";
        // Symlink to old release.
        run("cd {{deploy_path}} && {{bin/symlink}} $releaseDir current");

        // Symlink public folder again to correct release
        run("{{bin/symlink}} {$releaseDir}/public {{deploy_path}}");

        // Remove release
        run("rm -rf {{deploy_path}}/releases/{$releases[0]}");
        if (isVerbose()) {
            writeln("Rollback to `{$releases[1]}` release was successful.");
        }
    } else {
        writeln("<comment>No more releases you can revert to.</comment>");
    }
});

desc('Notifying Sentry of deployment');
task(
    'deploy:sentry:notify',
    function () {
        $now = date('c');

        $defaultConfig = [
            'version' => getReleaseGitRefLocal(),
            'refs' => [],
            'ref' => null,
            'commits' => getGitCommitsRefsLocal(),
            'url' => null,
            'date_released' => $now,
            'date_deploy_started' => $now,
            'date_deploy_finished' => $now,
            'sentry_server' => 'https://sentry.io',
            'previous_commit' => null,
            'environment' => get('symfony_env', 'prod'),
            'deploy_name' => null
        ];

        $config = array_merge($defaultConfig, (array) get('sentry'));
        array_walk(
            $config,
            static function (&$value) use ($config) {
                if (is_callable($value)) {
                    $value = $value($config);
                }
            }
        );

        if (
            !isset($config['organization'], $config['token'], $config['version'])
            || (empty($config['projects']) || !is_array($config['projects']))
        ) {
            throw new \RuntimeException(
                <<<EXAMPLE
Required data missing. Please configure sentry:
set(
    'sentry',
    [
        'organization' => 'exampleorg',
        'projects' => [
            'exampleproj',
            'exampleproje2'
        ],
        'token' => 'd47828...',
    ]
);"
EXAMPLE
            );
        }

        $releaseData = array_filter(
            [
                'version' => $config['version'],
                'refs' => $config['refs'],
                'ref' => $config['ref'],
                'url' => $config['url'],
                'commits' => array_slice($config['commits'], 0), // reset keys to serialize as array in json
                'dateReleased' => $config['date_released'],
                'projects' => $config['projects'],
                'previousCommit' => $config['previous_commit'],
            ]
        );

        $releasesApiUrl = $config['sentry_server'] . '/api/0/organizations/' . $config['organization'] . '/releases/';
        $response = Httpie::post(
            $releasesApiUrl
        )
            ->header(sprintf('Authorization: Bearer %s', $config['token']))
            ->body($releaseData)
            ->getJson();

        if (!isset($response['version'], $response['projects'])) {
            throw new \RuntimeException(sprintf('Unable to create a release: %s', print_r($response, true)));
        }

        writeln(
            sprintf(
                '<info>Sentry:</info> Release of version <comment>%s</comment> ' .
                'for projects: <comment>%s</comment> created successfully.',
                $response['version'],
                implode(', ', array_column($response['projects'], 'slug'))
            )
        );

        $deployData = array_filter(
            [
                'environment' => $config['environment'],
                'name' => $config['deploy_name'],
                'url' => $config['url'],
                'dateStarted' => $config['date_deploy_started'],
                'dateFinished' => $config['date_deploy_finished'],
            ]
        );

        $response = Httpie::post(
            $releasesApiUrl . $response['version'] . '/deploys/'
        )
            ->header(sprintf('Authorization: Bearer %s', $config['token']))
            ->body($deployData)
            ->getJson();

        if (!isset($response['id'], $response['environment'])) {
            throw new \RuntimeException(sprintf('Unable to create a deployment: %s', print_r($response, true)));
        }

        writeln(
            sprintf(
                '<info>Sentry:</info> Deployment <comment>%s</comment> ' .
                'for environment <comment>%s</comment> created successfully',
                $response['id'],
                $response['environment']
            )
        );
    }
);

function getReleaseGitRefLocal(): Closure
{
    return static function ($config = []): string {
        cd('{{release_path}}');

        if(isset($config['git_version_command'])){
            return trim(run($config['git_version_command']));
        }

        return trim(run('git log -n 1 --format="%h"'));
    };
}

function getGitCommitsRefsLocal(): Closure
{
    return static function ($config = []): array {
        $previousReleaseRevision = null;

        if (has('previous_release')) {
            cd('{{previous_release}}');
            $previousReleaseRevision = trim(run('git rev-parse HEAD'));
        }

        if ($previousReleaseRevision === null) {
            $commitRange = 'HEAD';
        } else {
            $commitRange = $previousReleaseRevision . '..HEAD';
        }

        cd('{{release_path}}');
        $result = run(sprintf('git rev-list --pretty="%s" %s', 'format:%H#%an#%ae#%at', $commitRange));
        $lines = array_filter(
        // limit number of commits for first release with many commits
            array_map('trim', array_slice(explode("\n", $result), 0, 200)),
            static function (string $line): bool {
                return !empty($line) && strpos($line, 'commit') !== 0;
            }
        );


        return array_map(
            static function (string $line): array {
                [$ref, $authorName, $authorEmail, $timestamp] = explode('#', $line);

                return [
                    'id' => $ref,
                    'author_name' => $authorName,
                    'author_email' => $authorEmail,
                    'timestamp' => date(DateTime::ATOM, (int) $timestamp),
                ];
            },
            $lines
        );
    };
}
