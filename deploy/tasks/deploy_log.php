<?php

namespace Deployer;

function getDeployLogInformation($commitData)
{
    $time = date('Y-m-d H:i:s');
    $target = get('target');
    $user = get('user');
    $releasePath = get('release_path');

    $string = <<<"EOF"
[{$time}] DEPLOY on '{$target}' by {$user} to '{$releasePath}'
==============================================================
{$commitData}


EOF;

    return $string;
}

desc('Writes deploy time, commit and user to the log file on the server');
task('deploy:log:remote', function () {
    $filename = get('deploy_log');
    $releasesList = get('releases_list');

    $newReleaseFilename = get('deploy_path') . '/' . $releasesList[0] . '_' . $filename;

    $commitData = run('cd {{release_path}} && git log origin/{{branch}} -1');
    $string = getDeployLogInformation($commitData);

    $tmpFile = tempnam(sys_get_temp_dir(), 'tmp_deploy');
    file_put_contents($tmpFile, $string);
    upload($tmpFile, $newReleaseFilename);
    unlink($tmpFile);

    $oldestRelease = $releasesList[get('keep_releases')-1] - 1;
    $oldestReleaseFilename = get('deploy_path') . '/' . $oldestRelease . '_' . $filename;

    if (test("[ -f {$oldestReleaseFilename} ]")) {
        run("rm {$oldestReleaseFilename}");
    }
});

desc('Read log file from the server');
task('deploy:log:read', function () {
    $releasesList = get('releases_list');
    $file = get('deploy_path') . '/' . $releasesList[0] . '_' . get('deploy_log');

    try {
        $fileContent = run("cat {$file}");
    } catch (\Throwable $e) {
        writeln('<error>Deploy log file could not be read from the server. Make sure it exists</error>');

        return -1;
    }

    writeln($fileContent);
});


desc('Rollbacks deploy log');
task('deploy:log:rollback:remote', function () {
    $releasesList = get('releases_list');

    $file = get('deploy_path') . '/' . $releasesList[0] . '_' . get('deploy_log');

    if (test("[ -f {$file} ]")) {
        run("rm {$file}");
    }
});
