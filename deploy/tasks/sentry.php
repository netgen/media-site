<?php

namespace Deployer;

use Closure;
use DateTime;
use Symfony\Component\Yaml\Yaml;

desc('Update release version in Sentry configuration on Symfony');
task('deploy:sentry:symfony', function () {
    $sentryConfig = getSentryConfig();
    $sentryConfig['sentry']['options']['release'] = getReleaseVersion()();
    $sentryYaml = Yaml::dump($sentryConfig);

    $tmpFile = tempnam(sys_get_temp_dir(), 'tmp_sentry');
    file_put_contents($tmpFile, $sentryYaml);

    $file = get('release_path').'/'.get('sentry_file');
    upload($tmpFile, $file);

    unlink($tmpFile);
});

function getSentryConfig(): array
{
    $file = get('release_path').'/'.get('sentry_file');

    $ymlString = run('cat '.$file);
    $sentryConfig = Yaml::parse($ymlString);

    return $sentryConfig;
}

/**
 * Copy of getReleaseGitRef()
 * Added target and release number information into release version tag
 *
 * @return \Closure
 */
function getReleaseVersion(): Closure
{
    return static function ($config = []): string {
        cd('{{release_path}}');

        if(isset($config['git_version_command'])){
            return trim(run($config['git_version_command']));
        }

        $user = get('user');
        $user = preg_replace('/\s+/', '_', $user);

        $releasePath = get('release_path');
        $releaseNumber = explode('/', $releasePath);
        $releaseNumber = end($releaseNumber);

        return get('target').'-'.$releaseNumber.'-'.trim(run('git log -n 1 --format="%h"')).'-'.$user;
    };
}

/**
 * Copy of original getGitCommitsRefs()
 * Added git commit message to the commit information sent to sentry
 *
 * @return \Closure
 */
function getCommitsInformation(): Closure
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
        $result = run(sprintf('git rev-list --pretty="%s" %s', 'format:%H#%s#%an#%ae#%at', $commitRange));
        $lines = array_filter(
            // limit number of commits for first release with many commits
            array_map('trim', array_slice(explode("\n", $result), 0, 200)),
            static function (string $line): bool {
                return !empty($line) && strpos($line, 'commit') !== 0;
            }
        );

        return array_map(
            static function (string $line): array {
                [$ref, $subject, $authorName, $authorEmail, $timestamp] = explode('#', $line);

                return [
                    'id' => $ref,
                    'author_name' => $authorName,
                    'author_email' => strpos($authorEmail, '@') !== false ? $authorEmail : 'missing@email.com',
                    'message' => $subject,
                    'timestamp' => date(DateTime::ATOM, (int) $timestamp),
                    'repository' => get('repository_name') ?: get('repository')
                ];
            },
            $lines
        );
    };
}
